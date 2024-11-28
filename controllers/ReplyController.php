<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Database.php';
include_once __DIR__ . '/../models/Reply.php';
class ReplyController {
    private $db;
    private $config;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = Config::getInstance();
    }

    // Create a new reply
    public function createReply($data) {
        $reply = new Reply($this->db);
        $reply->comment_id = $data->comment_id;
        $reply->user_id = $data->user_id;
        $reply->content = $data->content;

        if ($reply->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Reply created successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to create reply."]);
        }
    }

    // Get replies by comment ID
    public function getRepliesByCommentId($comment_id) {
        $reply = new Reply($this->db);
        $result = $reply->readByCommentId($comment_id);

        $replies = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $replies[] = $row;
        }
        echo json_encode($replies);
    }

    // Update a reply
    public function updateReply($data) {
        $reply = new Reply($this->db);
        $reply->reply_id = $data->reply_id;
        $reply->content = $data->content;

        if ($reply->update()) {
            echo json_encode(["message" => "Reply updated successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to update reply."]);
        }
    }

    // Delete a reply
    public function deleteReply($reply_id) {
        $reply = new Reply($this->db);
        $reply->reply_id = $reply_id;

        if ($reply->delete()) {
            echo json_encode(["message" => "Reply deleted successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to delete reply."]);
        }
    }
}
?>
