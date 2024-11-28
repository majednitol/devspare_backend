<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once __DIR__ . '/../../../middleware/auth.php';
include_once __DIR__ . '/../../../controllers/LikeController.php'; 

$likeController = new LikeController();

// Authenticate user
$user = authenticate();
$userId = $user->user_id;


$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        // Add a new like
        $data = json_decode(file_get_contents("php://input"));
        $data->user_id = $userId;
        $likeController->addLike($data);
        break;
        
    case 'DELETE':
        // Remove a like
        $data = json_decode(file_get_contents("php://input"));
        $data->user_id = $userId;
        $likeController->removeLike($data);
        break;

    case 'GET':
        // Get total likes for a specific article
        if (isset($_GET['article_id'])) {
            $article_id = $_GET['article_id'];
            $likeController->getTotalLikes($article_id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Article ID not provided."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Invalid request method."]);
        break;
}
?>
