<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Config
{
    private static $instance = null;
    public $port;
    public $origin;
    public $node_env;
    public $mongo_uri;
    public $cloud_name;
    public $cloud_api_key;
    public $cloud_secret_key;
    public $redis_url;
    public $activation_secret;
    public $access_token_secret;
    public $refresh_token_secret;
    public $access_token_expire;
    public $refresh_token_expire;
    public $smtp_host;
    public $smtp_port;
    public $smtp_service;
    public $smtp_mail;
    public $smtp_password;
    public $jwt_secret;
    public $host;
    public $db_name;
    public $username;
    public $password;
    public $activation_expire;

    private function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        // Initialize properties
        $this->port = $_ENV['PORT'] ?? '8080';
        $this->origin = json_decode($_ENV['ORIGIN'] ?? '["http://localhost:3000"]');
        $this->node_env = $_ENV['NODE_ENV'] ?? 'production';
        $this->mongo_uri = $_ENV['MONGO_URI'] ?? 'mongodb://localhost:27017/lms';
        $this->cloud_name = $_ENV['CLOUD_NAME'] ?? 'default_cloud_name';
        $this->cloud_api_key = $_ENV['CLOUD_API_KEY'] ?? 'default_cloud_api_key';
        $this->cloud_secret_key = $_ENV['CLOUD_SECRET_KEY'] ?? 'default_cloud_secret_key';
        $this->redis_url = $_ENV['REDIS_URL'] ?? 'default_redis_url';
        $this->activation_secret = $_ENV['ACTIVATION_SECRET'] ?? 'default_activation_secret';
        $this->access_token_secret = $_ENV['ACCESS_TOKEN'] ?? 'default_access_token';
        $this->refresh_token_secret = $_ENV['REFRESH_TOKEN'] ?? 'default_refresh_token';
        $this->access_token_expire = $_ENV['ACCESS_TOKEN_EXPIRE'] ?? '5';
        $this->refresh_token_expire = $_ENV['REFRESH_TOKEN_EXPIRE'] ?? '3';
        $this->smtp_host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
        $this->smtp_port = $_ENV['SMTP_PORT'] ?? '465';
        $this->smtp_service = $_ENV['SMTP_SERVICE'] ?? 'gmail';
        $this->smtp_mail = $_ENV['SMTP_MAIL'] ;
        $this->smtp_password = $_ENV['SMTP_PASSWORD'] ;
        $this->jwt_secret = $_ENV['JWT_SECRET'];
        $this->host = $_ENV['HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'prog';
        $this->username = $_ENV['USERNAME'] ?? 'root';
        $this->password = $_ENV['PASSWORD'] ?? '';
        $this->activation_expire = $_ENV['ACTIVATION_EXPIRE'];
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }
}
