<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once __DIR__ . '/../../../controllers/UserController.php';


$userController = new UserController();

$data = json_decode(file_get_contents("php://input"));

// Check if all required fields are provided
if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
    $userController->register($data->name, $data->email, $data->password);
} else {
    echo json_encode(["message" => "Incomplete data. 'name', 'email', and 'password' are required."]);
}
?>
