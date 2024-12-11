<?php  
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');

// Handle OPTIONS request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Respond with OK
    exit();
}

include_once __DIR__ . '/../../../middleware/auth.php';
include_once __DIR__ . '/../../../controllers/UserController.php';

$userController = new UserController();
$user = authenticate(); 

if ($user) {
    $authUserId = $user->user_id; // Extract userId from authentication
    
    // Get the userId from query parameters
    $queryUserId = isset($_GET['userId']) ? $_GET['userId'] : null;

    // Use userId from query parameter if provided, otherwise fallback to authenticated userId
    $userId = $queryUserId ?? $authUserId;

    // Handle GET request
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userController->getUserById($userId);
    } else {
        echo json_encode(["message" => "Invalid request method."]);
        http_response_code(405); 
    }
} else {
    echo json_encode(["message" => "Unauthorized."]);
    http_response_code(401); // Unauthorized
}  
?>
