<?php
include_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Database.php';
class NotificationController {
    private $db;
    private $config;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = Config::getInstance();
    }

    // Create a new notification
    public function createNotification($data) {
        $notification = new Notification($this->db);
        $notification->user_id = $data->user_id;
        $notification->title = $data->title;  // Set title
        $notification->message = $data->message;

        if ($notification->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Notification created successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to create notification."]);
        }
    }

    // Get all notifications for a user
    public function getNotifications($user_id) {
        $notification = new Notification($this->db);
        $notifications = $notification->getAllForUser($user_id);
        echo json_encode($notifications);
    }

    // Mark a notification as read and return the updated notification
    public function markNotificationAsRead($notification_id) {
        $notification = new Notification($this->db);
        $notification->notification_id = $notification_id;

        $updatedNotification = $notification->markAsRead();

        if ($updatedNotification) {
            echo json_encode(["message" => "Notification marked as read.", "notification" => $updatedNotification]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to mark notification as read."]);
        }
    }
}
?>
