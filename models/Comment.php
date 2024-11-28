<?php
class Comment {
    private $conn;
    private $table = "comments";

    public $comment_id;
    public $article_id;
    public $user_id;
    public $content;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new comment
    public function create() {
        $query = "INSERT INTO " . $this->table . " (article_id, user_id, content) VALUES (:article_id, :user_id, :content)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":article_id", $this->article_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":content", $this->content);

        if ($stmt->execute()) {
            $this->comment_id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Read comments by article
    public function readByArticle() {
        $query = "SELECT * FROM " . $this->table . " WHERE article_id = :article_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":article_id", $this->article_id);
        $stmt->execute();
        return $stmt;
    }

    // Update comment
    public function update() {
        $query = "UPDATE " . $this->table . " SET content = :content WHERE comment_id = :comment_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":comment_id", $this->comment_id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    // Delete comment
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE comment_id = :comment_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":comment_id", $this->comment_id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }
}
?>
