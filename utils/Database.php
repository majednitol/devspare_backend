<?php
require_once __DIR__ . '/../config/config.php';
class Database {
    private $host;  
    private $db_name;
    private $config;
    private $username; 
    private $password;
    public $conn;
    public function __construct()
    {

        $this->config = Config::getInstance();
        $this->host = $this->config->host;
        $this->db_name = $this->config->db_name;
        $this->username = $this->config->username;
        $this->password = $this->config->password;
    }
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
 