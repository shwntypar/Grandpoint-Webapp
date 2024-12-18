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

        error_log('POST data: ' . print_r($_POST, true));
        error_log('FILES data: ' . print_r($_FILES, true));

        if (empty($_FILES['images'])) {
            throw new Exception('No image file in request');
        }

        // Handle file upload
        try {
            error_log("Attempting to upload main image");
            // Check if image exists in $_FILES
            if (!isset($_FILES['images'])) {
                error_log("No image file found in request");
                error_log("FILES array content: " . print_r($_FILES, true));
                throw new Exception('No image file received');
            }

            $image_path = $this->HandleImageUpload($_FILES['images']);
            error_log("Main image uploaded successfully: " . $image_path);
        } catch (Exception $e) {
            error_log("Main image upload failed: " . $e->getMessage());
            return $this->sendPayload(null, "failed", "Image upload failed: " . $e->getMessage(), 400);
        }

        // Debug log for received data
        error_log("Processing data for database insertion:");
        error_log("Name: " . (isset($data->name) ? $data->name : 'not set'));
        error_log("Rarity: " . (isset($data->rarity) ? $data->rarity : 'not set'));
        error_log("Type: " . (isset($data->type) ? $data->type : 'not set'));
        error_log("Date Added: " . (isset($data->date_added) ? $data->date_added : 'not set'));

        // Convert form data to object properties if needed
        $product_name = $_POST['product_name'] ?? null;
        $price = $_POST['price'] ?? null;
        $description = $_POST['description'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        $supplier_id = $_POST['supplier_id'] ?? null;
        $url = $_POST['url'] ?? null;
        $views = 0;

        if (!$product_name || !$price || !$description || !$quantity ||  !$supplier_id || !$url) {
            throw new Exception('Missing required fields');
        }

            $sql = "INSERT INTO product (product_name, price, description, quantity, views, image_route, url, supplier_id) 
                    VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $product_name,
                $price,
                $description,
                $quantity,
                $views,
                basename($image_path),
                $url,
                $supplier_id
            ]);
            return $this->sendPayload(null, "success", "Product Successfully Added!", 200);
        } catch (PDOException $e){
            error_log("AddProducts Error: " . $e->getMessage());
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

    /* Handles the Image Uplaod for the AddProducts function */
    public function HandleImageUpload($file){
        error_log("HandleFileUpload received file: " . print_r($file, true));

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('No file uploaded or invalid file');
        }

        $uploadDir = __DIR__ . '/../../static/uploads/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                error_log("Failed to create directory: " . $uploadDir);
                throw new Exception('Failed to create upload directory');
            }
            error_log("Created upload directory: " . $uploadDir);
        }

        // Generate unique filename
        $filename = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $filename;

        error_log("Attempting to move file to: " . $targetPath);
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            error_log("Failed to move uploaded file. Upload error: " . error_get_last()['message']);
            throw new Exception('Failed to move uploaded file');
        }

        error_log("File successfully moved to: " . $targetPath);
        return $filename; // Return just the filename since we'll store that in the database
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
            $jwt = new Jwt();
            $payload = [
                "id" => $user['id'],
                "firstName" => $user['first_name'],
                "lastName" => $user['last_name'],
                "email" => $user['email'],
                "username" => $user['username'],
                'exp' => time() + (60 * 60 * 24)
            ];

            $token = $jwt->encode($payload);
            return $this->sendPayload([
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name']
                ]
            ], "success", "Login successful", 200);
            /* return $this->sendPayload(null, "success", "Login successful", 200); */
        } catch (PDOException $e) {
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

    public function EditProduct($id, $data){
        try {
            error_log('Raw input: ' . file_get_contents("php://input"));
            error_log('POST data: ' . print_r($_POST, true));
            error_log('FILES data: ' . print_r($_FILES, true));

            // Check if data is coming as JSON or FormData
            if (empty($_POST)) {
                // Handle JSON input
                $inputData = json_decode(file_get_contents("php://input"));
                $productName = $inputData->product_name;
                $price = $inputData->price;
                $description = $inputData->description;
                $quantity = $inputData->quantity;
                $supplierId = $inputData->supplier_id;
            } else {
                // Handle FormData input
                $productName = $_POST['product_name'];
                $price = $_POST['price'];
                $description = $_POST['description'];
                $quantity = $_POST['quantity'];
                $supplierId = $_POST['supplier_id'];
            }

            // Basic update without image
            if (!isset($_FILES['images'])) {
                $sql = "UPDATE product SET 
                        product_name = ?, 
                        price = ?, 
                        description = ?, 
                        quantity = ?, 
                        supplier_id = ? 
                        WHERE id = ?";
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $productName,
                    $price,
                    $description,
                    $quantity,
                    $supplierId,
                    $id
                ]);
            } else {
                // Handle image upload using existing function
                try {
                    $image_path = $this->HandleImageUpload($_FILES['images']);
                    error_log("Image uploaded successfully: " . $image_path);
                } catch (Exception $e) {
                    error_log("Image upload failed: " . $e->getMessage());
                    return $this->sendPayload(null, "failed", "Image upload failed: " . $e->getMessage(), 400);
                }
    
                $sql = "UPDATE product SET 
                        product_name = ?, 
                        price = ?, 
                        description = ?, 
                        quantity = ?, 
                        supplier_id = ?,
                        image_route = ? 
                        WHERE id = ?";
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $productName,
                    $price,
                    $description,
                    $quantity,
                    $supplierId,
                    basename($image_path),
                    $id
                ]);
            }
            return $this->sendPayload(null, "success", "Product Successfully Updated!", 200);
        } catch (PDOException $e){
            error_log("Update Error: " . $e->getMessage());
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

    public function EditUser($id, $data){
        try {
            $sql = "UPDATE users SET 
                    first_name = ?, 
                    last_name = ?, 
                    username = ?, 
                    email = ? 
                    WHERE id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data->first_name,
                $data->last_name,
                $data->username,
                $data->email,
                $id
            ]);
            return $this->sendPayload(null, "success", "User Successfully Updated!", 200);
        } catch (PDOException $e){
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

    public function EditSupplier($id, $data){
        try {
            $sql = "UPDATE supplier SET 
                    supplier_name = ?, 
                    contact_person = ?, 
                    email = ?, 
                    phone = ?,
                    address = ? 
                    WHERE id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data->supplier_name,
                $data->contact_person,
                $data->email,
                $data->phone,
                $data->address,
                $id
            ]);
            return $this->sendPayload(null, "success", "Supplier Successfully Updated!", 200);
        } catch (PDOException $e){
            return $this->sendPayload(null, "failed", $e->getMessage(), 400);
        }
    }

}




