<?php

require_once 'global.php';

class Delete extends GlobalMethods
{

    private $pdo;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function delete_user($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $this->pdo->commit();
            return $this->sendPayload(null, "success", "Successfully deleted user", 200);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $errmsg = $e->getMessage();
            $code = 400;
            return $this->sendPayload(null, "failed", $errmsg, $code);
        }
    }

    public function delete_product($id)
    {
        $sql = "DELETE FROM product WHERE id = ?";
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $this->pdo->commit();
            return $this->sendPayload(null, "success", "Successfully deleted product", 200);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $errmsg = $e->getMessage();
            $code = 400;
            return $this->sendPayload(null, "failed", $errmsg, $code);
        }
    }

    public function delete_supplier($id)
    {
        $sql = "DELETE FROM supplier WHERE id = ?";
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $this->pdo->commit();
            return $this->sendPayload(null, "success", "Successfully deleted supplier", 200);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $errmsg = $e->getMessage();
            $code = 400;
            return $this->sendPayload(null, "failed", $errmsg, $code);
        }
    }

}