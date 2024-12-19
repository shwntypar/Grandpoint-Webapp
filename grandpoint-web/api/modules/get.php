<?php

require_once 'global.php';

class Get extends GlobalMethods
{
    private $pdo;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* Functions that executes Queries */

    private function get_records($table = null, $conditions = null, $columns = '*', $customSqlStr = null, $params = [])
    {
        if ($customSqlStr != null) {
            $sqlStr = $customSqlStr;
        } else {
            $sqlStr = "SELECT $columns FROM $table";
            if ($conditions != null) {
                $sqlStr .= " WHERE " . $conditions;
            }
        }
        $result = $this->executeQuery($sqlStr, $params);

        if ($result['code'] == 200) {
            return $this->sendPayload($result['data'], 'success', "Successfully retrieved data.", $result['code']);
        }
        return $this->sendPayload(null, 'failed', "Failed to retrieve data.", $result['code']);
    }

    private function executeQuery($sql, $params = [])
    {
        $data = [];
        $errmsg = "";
        $code = 0;

        try {
            $statement = $this->pdo->prepare($sql);
            if ($statement->execute($params)) {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $record) {
                    // Handle BLOB data
                    /* if (isset($record['images'])) {
                        $record['images'] = base64_encode($record['images']);
                    } */
                   /* if(isset($record['images'])){
                    $record['images'] = stream_get_contents($record['images']);
                   } */
                    
                    array_push($data, $record);
                }
                $code = 200;
                return array("code" => $code, "data" => $data);
            } else {
                $errmsg = "No data found.";
                $code = 404;
            }
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 403;
        }
        return array("code" => $code, "errmsg" => $errmsg);
    }
 
    public function getProducts($id = null){    
        $condition = null;
        if ($id != null) {
            $condition = "id=$id";
        }
        return $this->get_records('product', $condition);
    }

    public function getUsers($id = null){
        $condition = null;
        if ($id != null) {
            $condition = "id=$id";
        }
        return $this->get_records('users', $condition);
    }

    public function getSuppliers($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "id=$id";
        }
        return $this->get_records('supplier', $condition);
    }

    public function getProductImages($id = null){
        $condition = null;
        if ($id != null) {
            $condition = "product_id=$id";
        }
        return $this->get_records('product_images', $condition);
    }

    public function getTransactions() {
        try {
            $sql = "SELECT 
                        t.id,
                        t.transaction_date,
                        t.total_amount,
                        t.status,
                        u.username as prepared_by
                    FROM transactions t
                    JOIN users u ON t.prepared_by = u.id
                    ORDER BY t.transaction_date DESC";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get items for each transaction
            foreach ($transactions as &$transaction) {
                $sql = "SELECT 
                            ti.quantity,
                            ti.price,
                            p.product_name,
                            p.id as product_id
                        FROM transaction_items ti
                        JOIN product p ON ti.product_id = p.id
                        WHERE ti.transaction_id = ?";
                        
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$transaction['id']]);
                $transaction['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Calculate subtotals for items
                foreach ($transaction['items'] as &$item) {
                    $item['subtotal'] = $item['quantity'] * $item['price'];
                }
            }

            return $this->sendPayload($transactions, "success", "Transactions retrieved successfully", 200);

        } catch (PDOException $e) {
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

}
