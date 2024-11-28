<?php
require_once '../vendor/autoload.php';

use Predis\Client;
use Dotenv\Dotenv;
use Predis\Connection\ConnectionException;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

class RedisClient {
    private $client;

    public function __construct() {
        try {
            // Get Redis URL from environment variable
            // $redisUrl = getenv('REDIS_URL');
            $redisUrl = "rediss://default:Ae9xAAIjcDE0MmZkZmRmOTA0MDQ0YTgyOWE4ZjE1MzJkZjkzMmNlNHAxMA@hopeful-bass-61297.upstash.io:6379";

            if ($redisUrl) {
                $parsedUrl = parse_url($redisUrl);

                // Extract connection details from parsed URL
                $host = $parsedUrl['host'];
                $port = $parsedUrl['port'];
                $password = $parsedUrl['pass'] ?? null;

                // Attempt to connect to Redis
                $this->client = new Client([
                    'scheme'   => 'tls',      // Encrypted connection
                    'host'     => $host,
                    'port'     => $port,
                    'password' => $password,
                ]);

                // Test the connection
                $this->client->connect();
                echo "Connected to Redis successfully.";
            } else {
                throw new Exception("REDIS_URL is not defined in the environment variables.");
            }
        } catch (ConnectionException $e) {
            echo "Could not connect to Redis: " . $e->getMessage();
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }

    public function getClient() {
        return $this->client;
    }
}
?>
