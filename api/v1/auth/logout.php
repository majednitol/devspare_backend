<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../../../controllers/UserController.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userController = new UserController();
    $userController->logout();
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
?>
