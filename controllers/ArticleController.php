<?php
require __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Database.php';
require_once __DIR__ . '/../models/User.php';
require __DIR__ . '/../file/coludinary.php';
class ArticleController
{
    private $db;
    private $config;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = Config::getInstance();
    }

    // Create a new article
    public function create($user_id, $title, $content, $tags, $cover_pic)
    {
        $article = new Article($this->db);
        $article->user_id = $user_id;
        $article->title = $title;
        $article->content = $content;
        $article->tags = $tags;
        $article->cover_pic = $cover_pic;

        if ($article->createArticle()) {
            echo json_encode(["message" => "Article created successfully."]);
        } else {
            echo json_encode(["message" => "Article creation failed."]);
        }
    }

    // Read all articles
    public function read()
    {
        $article = new Article($this->db);
        $stmt = $article->readArticles();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($articles);
    }

    // Read a single article
    public function readSingle($article_id)
    {
        $article = new Article($this->db);
        $article->article_id = $article_id;
        $stmt = $article->readArticle();
        $articleData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($articleData) {
            echo json_encode($articleData);
        } else {
            echo json_encode(["message" => "Article not found."]);
        }
    }

    // Update an article
    public function update($article_id, $title, $content, $tags, $cover_pic)
    {
        $article = new Article($this->db);
        $article->article_id = $article_id;
        $article->title = $title;
        $article->content = $content;
        $article->tags = $tags;
        $article->cover_pic = $cover_pic;

        if ($article->updateArticle()) {
            echo json_encode(["message" => "Article updated successfully."]);
        } else {
            echo json_encode(["message" => "Article update failed."]);
        }
    }

    // Delete an article
    public function delete($article_id)
    {
        $article = new Article($this->db);
        $article->article_id = $article_id;

        if ($article->deleteArticle()) {
            echo json_encode(["message" => "Article deleted successfully."]);
        } else {
            echo json_encode(["message" => "Article deletion failed."]);
        }
    }

    public function readByTags($tags)
    {
        $article = new Article($this->db);
        $stmt = $article->getArticlesByTags($tags);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($articles);
    }

    public function getAllTags()
    {
        $article = new Article($this->db);
        $stmt = $article->getAllTags();
        $tagsArray = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        // Extract and deduplicate tags
        $allTags = [];
        foreach ($tagsArray as $tags) {
            $individualTags = array_map('trim', explode(',', $tags));
            $allTags = array_merge($allTags, $individualTags);
        }
        $uniqueTags = array_unique($allTags);

        echo json_encode(array_values($uniqueTags));
    }

    public function uploadCoverPicture($user_id, $imagePath)
    {
        try {
            // Initialize user model
            $user = new User($this->db);
            $user->user_id = $user_id;
    
            // Define Cloudinary parameters
            $imageType = 'cover';
            $publicId = "{$imageType}_user_{$user_id}";
            $folder = "{$imageType}_pics";
    
            // Log the upload process
            echo "Preparing to upload image for user ID {$user_id} with public ID {$publicId}...\n";
    
            // Upload the image to Cloudinary
            $cloudinaryUrl = uploadToCloudinary($imagePath, $publicId, $folder);
    
            // Check if the upload was successful
            if ($cloudinaryUrl) {
                echo "Image successfully uploaded to Cloudinary: {$cloudinaryUrl}\n";
                return $cloudinaryUrl;
            } else {
                // Handle upload failure
                echo json_encode(["message" => "Failed to upload image to Cloudinary."]);
                http_response_code(500);
                return null;
            }
        } catch (Exception $e) {
            // Handle unexpected errors
            echo json_encode(["message" => "An error occurred: " . $e->getMessage()]);
            http_response_code(500);
            return null;
        }
    }
    
}
