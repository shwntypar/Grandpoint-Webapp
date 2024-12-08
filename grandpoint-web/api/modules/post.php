<?php

require_once 'global.php';

class Post extends GlobalMethods
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function AddUsers($data){
        try{
            $sql = "INSERT INTO users (first_name, last_name, username, email, password, is_superadmin) 
        VALUES (?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data->first_name,
            $data->last_name,
            $data->username,
            $data->email,
            $data->password,
            0
        ]);
        return $this->sendPayload(null, "success" , "User Successfully Added!", 200);
        } catch (PDOException $e){
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

    public function AddProducts($data){
        try{
            $sql = "INSERT INTO product (product_name, price, description, quantity, views, supplier_id) 
                    VALUES (?,?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data->product_name,
                $data->price,
                $data->description,
                $data->quantity,
                $data->views,
                $data->supplier_id
            ]);
            return $this->sendPayload(null, "success", "Product Successfully Added!", 200);
        } catch (PDOException $e){
            error_log("AddProducts Error: " . $e->getMessage());
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

    public function AddSuppliers($data){
        try{
            $sql = "INSERT INTO supplier (supplier_name, contact_person, email, phone, address) 
        VALUES (?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data->supplier_name,
            $data->contact_person,
            $data->email,
            $data->phone,
            $data->address,
        ]);
        return $this->sendPayload(null, "success" , "Supplier Successfully Added!", 200);
        } catch (PDOException $e){
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

    public function userLogin($data)
    {
        try {
            // CHECK IF USER EXISTS
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data->username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !$data->password) {
                return $this->sendPayload(null, "failed", "Invalid username or password", 401);
            }   

            // GENERATE JWT TOKEN
            /* $jwt = new Jwt();
            $payload = [
                "id" => $user['id'],
                "firstName" => $user['first_name'],
                "lastName" => $user['last_name'],
                "email" => $user['email'],
                "role" => $user['role'],
                'exp' => time() + (60 * 60 * 24)
            ]; */

           /*  $token = $jwt->encode($payload);
            return $this->sendPayload([
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name']
                ]
            ], "success", "Login successful", 200); */
            return $this->sendPayload(null, "success", "Login successful", 200);
        } catch (PDOException $e) {
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }



    /* public function AddProductImages($data){
        try{
            if(isset($_FILES['image'])) {
                $file = $_FILES['image'];
                $fileName = time() . '_' . $file['name'];
                
                // Create absolute path to uploads directory
                $uploadDir = __DIR__ . '/../../uploads/';  // Go up two directories from current file
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $uploadPath = $uploadDir . $fileName;
    
                if(move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $sql = "INSERT INTO product_images (product_id, image) VALUES (?,?)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([
                        $data->product_id,
                        $fileName  // Save only filename in database
                    ]);
                    return $this->sendPayload(null, "success", "Product Image Successfully Added!", 200);
                } else {
                    $uploadError = error_get_last();
                    return $this->sendPayload(null, "failed", "Failed to upload image: " . ($uploadError['message'] ?? 'Unknown error'), 400);
                }
            } else {
                return $this->sendPayload(null, "failed", "No image file received", 400);
            }
        } catch (PDOException $e){
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }
 */

}




