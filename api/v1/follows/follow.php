<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once __DIR__ . '/../../../middleware/auth.php';
include_once __DIR__ . '/../../../controllers/FollowController.php';


$followController = new FollowController();

// Authenticate user
$user = authenticate();
$userId = $user->user_id;

// Get request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        // Follow a user
        $data = json_decode(file_get_contents("php://input"));
        $followedId = $data->followed_id;
        $followController->followUser($userId, $followedId);
        break;

    case 'DELETE':
        // Unfollow a user
        $data = json_decode(file_get_contents("php://input"));
        $followedId = $data->followed_id;
        $followController->unfollowUser($userId, $followedId);
        break;

    case 'GET':
        // Check if query parameter is 'followers' or 'following'
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            if ($type === 'followers') {
                // Get followers
                $followController->getFollowers($userId);
            } elseif ($type === 'following') {
                // Get users followed by the user
                $followController->getFollowedUsers($userId);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Invalid 'type' parameter."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Missing 'type' parameter."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Invalid request method."]);
        break;
}
?>
