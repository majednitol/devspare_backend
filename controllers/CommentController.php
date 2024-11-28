<?php
include_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Database.php';
class CommentController {
    private $db;
    private $config;

    private $comment;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = Config::getInstance();
 
        $this->comment = new Comment($this->db);

    }

    // Create a new comment
    public function create($userId, $articleId, $content) {
        $this->comment->user_id = $userId;
        $this->comment->article_id = $articleId;
        $this->comment->content = $content;

        if ($this->comment->create()) {
            echo json_encode(["message" => "Comment created successfully"]);
        } else {
            echo json_encode(["message" => "Failed to create comment"]);
        }
    }

    // Read comments for a specific article
    public function readByArticle($articleId) {
        $this->comment->article_id = $articleId;
        $stmt = $this->comment->readByArticle();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($comments);
    }

    // Update an existing comment
    public function update($userId, $commentId, $content) {
        $this->comment->user_id = $userId;
        $this->comment->comment_id = $commentId;
        $this->comment->content = $content;

        if ($this->comment->update()) {
            echo json_encode(["message" => "Comment updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update comment"]);
        }
    }

    // Delete a comment
    public function delete($userId, $commentId) {
        $this->comment->user_id = $userId;
        $this->comment->comment_id = $commentId;

        if ($this->comment->delete()) {
            echo json_encode(["message" => "Comment deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete comment"]);
        }
    }
}
?>
