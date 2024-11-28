<?php

class Reply {
    private $conn;
    private $table = 'replies';

    public $reply_id;
    public $comment_id;
    public $user_id;
    public $content;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new reply
    public function create() {
        $query = "INSERT INTO " . $this->table . " (comment_id, user_id, content) VALUES (:comment_id, :user_id, :content)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':comment_id', $this->comment_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':content', $this->content);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all replies for a specific comment
    public function readByCommentId($comment_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE comment_id = :comment_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->execute();
        return $stmt;
    }

    // Update a reply
    public function update() {
        $query = "UPDATE " . $this->table . " SET content = :content WHERE reply_id = :reply_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':reply_id', $this->reply_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a reply
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE reply_id = :reply_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reply_id', $this->reply_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
