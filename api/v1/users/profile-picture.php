<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once __DIR__ . '/../../../controllers/UserController.php';
$userController = new UserController();
$user = authenticate();
$userId = $user->user_id;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . '/../../../file/public/upload/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);  
            echo "Directory created: " . $targetDir . "<br>";
        }
        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
        
        // Debugging: Show target file path
        echo "Target file path: " . $targetFile . "<br>";
        if ($_FILES["fileToUpload"]["size"] == 0) {
            echo json_encode(["message" => "The uploaded file is empty."]);
            http_response_code(400);
            return;  
        }
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            if (!empty($userId) && !empty($targetFile)) {
                $userController->updatePicture($userId, $targetFile);
                echo json_encode(["message" => "File uploaded successfully.", "filePath" => $targetFile]);
            } else {
                echo json_encode(["message" => "User ID or file path is missing."]);
                http_response_code(400);
            }
        } else {
            echo json_encode(["message" => "Error uploading the file."]);
            http_response_code(500);
        }
    } else {
        echo json_encode(["message" => "No file uploaded or there was an error with the upload."]);
        http_response_code(400);
    }
} else {
    echo json_encode(["message" => "Invalid request method. Please use POST."]);
    http_response_code(405);
}
