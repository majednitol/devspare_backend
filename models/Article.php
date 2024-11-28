<?php 
class Article {
    private $conn;
    private $table = 'articles';

    public $article_id;
    public $user_id;
    public $title;
    public $content;
    public $tags;
    public $cover_pic;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new article
    public function createArticle() {
        $query = "INSERT INTO " . $this->table . " (user_id, title, content, tags, cover_pic) VALUES (:user_id, :title, :content, :tags, :cover_pic)";
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->tags = htmlspecialchars(strip_tags($this->tags));
        $this->cover_pic = htmlspecialchars(strip_tags($this->cover_pic));

        // Bind parameters
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':tags', $this->tags);
        $stmt->bindParam(':cover_pic', $this->cover_pic);

        return $stmt->execute();
    }

    // Read all articles
    public function readArticles() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single article by ID
    public function readArticle() {
        $query = "SELECT * FROM " . $this->table . " WHERE article_id = :article_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':article_id', $this->article_id);
        $stmt->execute();
        return $stmt;
    }

    // Update an article
    public function updateArticle() {
        $query = "UPDATE " . $this->table . " SET title = :title, content = :content, tags = :tags, cover_pic = :cover_pic, updated_at = CURRENT_TIMESTAMP WHERE article_id = :article_id";
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->tags = htmlspecialchars(strip_tags($this->tags));
        $this->cover_pic = htmlspecialchars(strip_tags($this->cover_pic));

        // Bind parameters
        $stmt->bindParam(':article_id', $this->article_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':tags', $this->tags);
        $stmt->bindParam(':cover_pic', $this->cover_pic);

        return $stmt->execute();
    }

    // Delete an article
    public function deleteArticle() {
        $query = "DELETE FROM " . $this->table . " WHERE article_id = :article_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':article_id', $this->article_id);
        return $stmt->execute();
    }

    public function getArticlesByTags($tags) {
        $query = "SELECT * FROM " . $this->table . " WHERE tags LIKE :tags ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        
        // Format tags to be used in SQL LIKE clause
        $tags = "%" . htmlspecialchars(strip_tags($tags)) . "%";
        $stmt->bindParam(':tags', $tags);
        
        $stmt->execute();
        return $stmt;
    }

    public function getAllTags() {
        $query = "SELECT DISTINCT tags FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
