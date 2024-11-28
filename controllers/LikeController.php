<?php

include_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Database.php';
class LikeController {
    private $db;
    private $config;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = Config::getInstance();
    }

    // Add a like
    public function addLike($data) {
        $like = new Like($this->db);
        $like->user_id = $data->user_id;
        $like->article_id = $data->article_id;

        if ($like->add()) {
            http_response_code(201);
            echo json_encode(["message" => "Like added successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to add like."]);
        }
    }

    // Remove a like
    public function removeLike($data) {
        $like = new Like($this->db);
        $like->user_id = $data->user_id;
        $like->article_id = $data->article_id;

        if ($like->remove()) {
            echo json_encode(["message" => "Like removed successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to remove like."]);
        }
    }

    // Get total likes for an article
    public function getTotalLikes($article_id) {
        $like = new Like($this->db);
        $like->article_id = $article_id;

        $totalLikes = $like->getTotalLikes();
        echo json_encode(["total_likes" => $totalLikes]);
    }
}
?>
