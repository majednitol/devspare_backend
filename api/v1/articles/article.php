<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


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
        // Create a new article
        $data = json_decode(file_get_contents("php://input"));
        $articleController->create($userId, $data->title, $data->content, $data->tags, $data->cover_pic);
        break;
    case 'GET':

        if (isset($_GET['id'])) {
            $articleController->readSingle($_GET['id']);
        }elseif (isset($_GET['tags'])) {
            $articleController->readByTags($_GET['tags']);
        }elseif (isset($_GET['allTags'])) {
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
