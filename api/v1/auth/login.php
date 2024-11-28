<?php 
header('Access-Control-Allow-Origin: http://127.0.0.1:5500'); 
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once __DIR__ . '/../../../controllers/UserController.php';

$userController = new UserController();
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->email) && !empty($data->password)) {
    $userController->login($data->email, $data->password);
} else {
    echo json_encode(["message" => "Incomplete data. 'email' and 'password' are required."]);
}
