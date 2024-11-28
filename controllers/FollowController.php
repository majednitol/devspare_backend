<?php
include_once __DIR__ . '/../models/Follow.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Database.php';
class FollowController {
    private $db;
    private $config;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = Config::getInstance();
    }

    // Follow a user
    public function followUser($follower_id, $followed_id) {
        $follow = new Follow($this->db);
        $follow->follower_id = $follower_id;
        $follow->followed_id = $followed_id;

        if ($follow->follow()) {
            echo json_encode(["message" => "User followed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to follow user."]);
        }
    }

    // Unfollow a user
    public function unfollowUser($follower_id, $followed_id) {
        $follow = new Follow($this->db);
        $follow->follower_id = $follower_id;
        $follow->followed_id = $followed_id;

        if ($follow->unfollow()) {
            echo json_encode(["message" => "User unfollowed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to unfollow user."]);
        }
    }

    // Get all followers of a user
    public function getFollowers($user_id) {
        $follow = new Follow($this->db);
        $followers = $follow->getFollowers($user_id);
        echo json_encode($followers);
    }

    // Get all followed users by a user
    public function getFollowedUsers($user_id) {
        $follow = new Follow($this->db);
        $followedUsers = $follow->getFollowedUsers($user_id);
        echo json_encode($followedUsers);
    }
}
?>
