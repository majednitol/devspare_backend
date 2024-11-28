<?php
class Follow {
    private $conn;
    private $table = 'follows';

    public $follow_id;
    public $follower_id;
    public $followed_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Follow a user
    public function follow() {
        $query = "INSERT INTO " . $this->table . " (follower_id, followed_id) VALUES (:follower_id, :followed_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':follower_id', $this->follower_id);
        $stmt->bindParam(':followed_id', $this->followed_id);
        return $stmt->execute();
    }

    // Unfollow a user
    public function unfollow() {
        $query = "DELETE FROM " . $this->table . " WHERE follower_id = :follower_id AND followed_id = :followed_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':follower_id', $this->follower_id);
        $stmt->bindParam(':followed_id', $this->followed_id);
        return $stmt->execute();
    }

    // Get all followers of a user
    public function getFollowers($user_id) {
        $query = "SELECT follower_id FROM " . $this->table . " WHERE followed_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all users followed by a user
    public function getFollowedUsers($user_id) {
        $query = "SELECT followed_id FROM " . $this->table . " WHERE follower_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

