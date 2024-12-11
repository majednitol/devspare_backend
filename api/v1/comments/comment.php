<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Respond with OK
    exit();
}


include_once __DIR__ . '/../../../middleware/auth.php';
include_once __DIR__ . '/../../../controllers/CommentController.php';


$commentController = new CommentController();

$user = authenticate();
$userId = $user->user_id;

// Handle different request methods
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST':
        // Create a new comment
        $data = json_decode(file_get_contents("php://input"));
        $commentController->create($userId, $data->article_id, $data->content);
        break;
    case 'GET':
        // Read comments for a specific article
        if (isset($_GET['article_id'])) {
            $commentController->readByArticle($_GET['article_id']);
        } else {
            echo json_encode(["message" => "Article ID is required"]);
        }
        break;
    case 'PUT':
        // Update a comment
        $data = json_decode(file_get_contents("php://input"));
        $commentController->update($userId, $data->comment_id, $data->content);
        break;
    case 'DELETE':
        // Delete a comment
        $data = json_decode(file_get_contents("php://input"));
        $commentController->delete($userId, $data->comment_id);
        break;
    default:
        echo json_encode(["message" => "Invalid request method."]);
        http_response_code(405);
        break;
}
?>
