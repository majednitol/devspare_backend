<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
include_once __DIR__ . '/../../../controllers/UserController.php';
$user = authenticate();
if ($user) {
    $userId = $user->user_id;
    if ($userId) {
        $userController = new UserController();
        $userController->updateAcessToken();
    }
    else{
        echo json_encode(["message" => "plase login again."]);
        http_response_code(401);
    }
}



?>
