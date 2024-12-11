<?php
header('Access-Control-Allow-Origin: http://127.0.0.1:5500'); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle OPTIONS requests for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Return HTTP 200 OK for OPTIONS
    exit;
}

include_once __DIR__ . '/../../../controllers/ArticleController.php';
include_once __DIR__ . '/../../../middleware/auth.php';

$articleController = new ArticleController();

// Authenticate user
$user = authenticate();
$userId = $user->user_id;

// Handle different request methods
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST':
        if (isset($_FILES['cover_pic']) && $_FILES['cover_pic']['error'] === UPLOAD_ERR_OK) {
            // Handle file upload for cover_pic
            $targetDir = __DIR__ . '/../../../file/public/upload/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $targetFile = $targetDir . basename($_FILES["cover_pic"]["name"]);

            if ($_FILES["cover_pic"]["size"] == 0) {
                echo json_encode(["message" => "The uploaded file is empty."]);
                http_response_code(400);
                return;
            }
            if (move_uploaded_file($_FILES["cover_pic"]["tmp_name"], $targetFile)) {
                // Extract additional article data
                $title = $_POST['title'] ?? null;
                $content = $_POST['content'] ?? null;
                $tags = $_POST['tags'] ?? null;
               $coverPic_uri =  $articleController->uploadCoverPicture($userId, $targetFile);
                if ($title && $content && $tags && $coverPic_uri) {
                    // Create the article with the uploaded cover_pic path
                    $articleController->create($userId, $title, $content, $tags, $coverPic_uri);
                } else {
                    echo json_encode(["message" => "Invalid or missing article data."]);
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
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $articleController->readSingle($_GET['id']);
        } elseif (isset($_GET['tags'])) {
            $articleController->readByTags($_GET['tags']);
        } elseif (isset($_GET['allTags'])) {
            // Fetch all unique tags
            $articleController->getAllTags();
        } else {
            // Otherwise, read all articles
            $articleController->read();
        }
        break;
    case 'PUT':
        // Update an article
        $data = json_decode(file_get_contents("php://input"));
        $articleController->update($data->article_id, $data->title, $data->content, $data->tags, $data->cover_pic);
        break;
    case 'DELETE':
        // Delete an article
        $data = json_decode(file_get_contents("php://input"));
        $articleController->delete($data->article_id);
        break;
    default:
        echo json_encode(["message" => "Invalid request method."]);
        http_response_code(405); // Method Not Allowed
        break;
}
?>
