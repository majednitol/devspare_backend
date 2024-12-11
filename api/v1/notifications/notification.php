<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Respond with OK
    exit();
}
include_once __DIR__ . '/../../../middleware/auth.php';
include_once __DIR__ . '/../../../controllers/NotificationController.php';


$notificationController = new NotificationController();

// Authenticate user
$user = authenticate();
$userId = $user->user_id;

// Get request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        // Create new notification
        $data = json_decode(file_get_contents("php://input"));
        $data->user_id = $userId;
        $notificationController->createNotification($data);
        break;

    case 'GET':
        // Get all notifications for user
        $notificationController->getNotifications($userId);
        break;

    case 'PUT':
        // Mark notification as read
        $data = json_decode(file_get_contents("php://input"));
        $notificationController->markNotificationAsRead($data->notification_id);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Invalid request method."]);
        break;
}
?>
