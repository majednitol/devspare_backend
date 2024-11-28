<?php
class Notification {
    private $conn;
    private $table = 'notifications';

    public $notification_id;
    public $user_id;
    public $title;
    public $message;
    public $is_read;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new notification
    public function create() {
        $query = "INSERT INTO " . $this->table . " (user_id, title, message) VALUES (:user_id, :title, :message)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':message', $this->message);

        return $stmt->execute();
    }

    // Get all notifications for a user in reverse order (latest first)
    public function getAllForUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mark a notification as read and return the updated notification
    public function markAsRead() {
        $query = "UPDATE " . $this->table . " SET is_read = 1 WHERE notification_id = :notification_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':notification_id', $this->notification_id);

        if ($stmt->execute()) {
            // Retrieve and return the updated notification
            $query = "SELECT * FROM " . $this->table . " WHERE notification_id = :notification_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':notification_id', $this->notification_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    // Delete read notifications older than 30 days
    public function deleteOldReadNotifications() {
        $query = "DELETE FROM " . $this->table . " WHERE is_read = 1 AND created_at < (NOW() - INTERVAL 30 DAY)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
?>
