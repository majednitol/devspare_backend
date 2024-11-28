<?php

class Like {
    private $conn;
    private $table = 'likes';

    public $user_id;
    public $article_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add a new like
    public function add() {
        $query = "INSERT INTO " . $this->table . " (user_id, article_id) 
                  VALUES (:user_id, :article_id)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':article_id', $this->article_id);

        if ($stmt->execute()) {
            // Update the like count
            $updateQuery = "UPDATE articles SET like_count = like_count + 1 
                            WHERE article_id = :article_id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':article_id', $this->article_id);
            return $updateStmt->execute();
        }
        return false;
    }

    // Remove a like
    public function remove() {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE user_id = :user_id AND article_id = :article_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':article_id', $this->article_id);

        if ($stmt->execute()) {
            // Decrement the like count
            $updateQuery = "UPDATE articles SET like_count = like_count - 1 
                            WHERE article_id = :article_id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':article_id', $this->article_id);
            return $updateStmt->execute();
        }
        return false;
    }

    // Get total likes for a specific article
    public function getTotalLikes() {
        $query = "SELECT like_count FROM articles WHERE article_id = :article_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':article_id', $this->article_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['like_count'] : 0;
    }
}
?>
