<?php

$allowedOrigins = [
    'http://localhost:5173',
    'http://localhost:4200',
];

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $origin);
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/* APIs */
require_once "./config/database.php";
require_once "./modules/get.php";
require_once "./modules/post.php";
require_once "./src/Jwt.php";
require_once "./vendor/autoload.php";
require_once "./modules/delete.php";

// INITIALIZE ESSENTIAL OBJECTS
$con = new Connection();
$pdo = $con->connect();
$get = new Get($pdo);
$post = new Post($pdo);
$delete = new Delete($pdo);


/* $delete = new Delete($pdo);
$auth = new AuthMiddleware(); */


// Check if 'request' parameter is set in the request
if (isset($_REQUEST['request'])) {
    // Split the request into an array based on '/'
    $request = explode('/', $_REQUEST['request']);
} else {
    // If 'request' parameter is not set, return a 404 response
    echo "Not Found";
    http_response_code(404);
}

switch ($_SERVER['REQUEST_METHOD']){
    case 'OPTIONS':
        http_response_code(200);
        exit();
    
    case 'GET':
        switch($request[0]){
            case 'getUsers':
                if(count($request) > 1){
                    $data = json_decode(file_get_contents("php://input"));
                    echo json_encode($get->getUsers($request[1]));
                }else{
                    echo json_encode($get->getUsers());
                }
                break;
            case 'getSuppliers':
                echo json_encode($get->getSuppliers());
                break;
            case 'getProducts':
                echo json_encode($get->getProducts());
                break;
            case 'getTransactions':
                echo json_encode($get->getTransactions());
                break;
            default:
                echo "Unknown request";
        }
    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        switch($request[0]){
            case 'AddUsers':
                echo json_encode($post->AddUsers($data));
                break;
            case 'AddSuppliers':
                echo json_encode($post->AddSuppliers($data));
                break;
            case 'AddProducts':
                echo json_encode($post->AddProducts($data));
                break;
            case 'login':
                echo json_encode($post->userLogin($data));
                break;
            case 'updateProduct':
                if(count($request) > 1){
                    echo json_encode($post->EditProduct($request[1], null));
                }
                break;
            case 'updateUser':
                echo json_encode($post->EditUser($request[1], $data));
                break;
            case 'updateSupplier':
                echo json_encode($post->EditSupplier($request[1], $data));
                break;
            case 'addTransaction':
                echo json_encode($post->addTransaction($data));
                break;
            /* case 'AddProductImages':
                echo json_encode($post->AddProductImages($data)); */
        }
        
        case 'DELETE':
            switch($request[0]){
                case 'deleteUser':
                    echo json_encode($delete->delete_user($request[1]));
                    break;
                case 'deleteProduct':
                    echo json_encode($delete->delete_product($request[1]));
                    break;
                case 'deleteSupplier':
                    echo json_encode($delete->delete_supplier($request[1]));
                    break;
            }
            
        
}

?>