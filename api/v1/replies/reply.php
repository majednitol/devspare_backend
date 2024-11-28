<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once __DIR__ . '/../../../middleware/auth.php';
include_once __DIR__ . '/../../../controllers/ReplyController.php';


$replyController = new ReplyController();

// Authenticate user
$user = authenticate();
$userId = $user->user_id;

// Handle different request methods
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST':
        // Create a new reply
        $data = json_decode(file_get_contents("php://input"));
        $replyData = (object) [
            'comment_id' => $data->comment_id,
            'user_id' => $userId,
            'content' => $data->content
        ];
        $replyController->createReply($replyData);
        break;

    case 'GET':
        // Read replies for a specific comment
        if (isset($_GET['comment_id'])) {
            $replyController->getRepliesByCommentId($_GET['comment_id']);
        } else {
            echo json_encode(["message" => "Comment ID is required"]);
        }
        break;

    case 'PUT':
        // Update a reply
        $data = json_decode(file_get_contents("php://input"));
        $replyData = (object) [
            'reply_id' => $data->reply_id,
            'content' => $data->content
        ];
        $replyController->updateReply($replyData);
        break;

    case 'DELETE':
        // Delete a reply
        $data = json_decode(file_get_contents("php://input"));
        $replyController->deleteReply($data->reply_id);
        break;

    default:
        echo json_encode(["message" => "Invalid request method."]);
        http_response_code(405);
        break;
}
?>
