<?php

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';


// define('ALGORITHM', 'HS256');

// function authenticate() {
//     $config = Config::getInstance();
//     // Check for token in cookies
//     $access_token = isset($_COOKIE['access_token']) ? $_COOKIE['access_token'] : '';
//     $activation_token = isset($_COOKIE['activation_token']) ? $_COOKIE['activation_token'] : '';
//     $jwt = !empty($access_token) ? $access_token : $activation_token;
    
//     if (!$jwt) {
//         echo json_encode(["message" => "Access denied. No token provided."]);
//         http_response_code(401);
//         exit();
//     }
    
//     try {
//         $decoded = JWT::decode($jwt, new Key($access_token ?$config->access_token_secret:$config->activation_secret , ALGORITHM));
//         return $decoded;
//     } catch (ExpiredException $e) {
//         echo json_encode(["message" => "Access denied. Token has expired.", "error" => $e->getMessage()]);
//         http_response_code(401);
//         exit();
//     } catch (SignatureInvalidException $e) {
//         echo json_encode(["message" => "Access denied. Invalid token signature.", "error" => $e->getMessage()]);
//         http_response_code(401);
//         exit();
//     } catch (BeforeValidException $e) {
//         echo json_encode(["message" => "Access denied. Token not valid yet.", "error" => $e->getMessage()]);
//         http_response_code(401);
//         exit();
//     } catch (Exception $e) {
//         echo json_encode(["message" => "Access denied.", "error" => $e->getMessage()]);
//         http_response_code(401);
//         exit();
//     }
// }

$config = Config::getInstance();
define('SECRET_KEY', $config->access_token_secret);
define('ALGORITHM', 'HS256');
function authenticate() {
    global $key;
    $config = Config::getInstance();
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    $jwt = str_replace('Bearer ', '', $authHeader);
    
    if (!$jwt) {
        echo json_encode(["message" => "Access denied. No token provided."]);
        http_response_code(401);
        exit();
    }
    
    try {
        $decoded = JWT::decode($jwt, new Key($config->access_token_secret, 'HS256'));
        return $decoded;
    } catch (ExpiredException $e) {
        echo json_encode(["message" => "Access denied. Token has expired.", "error" => $e->getMessage()]);
        http_response_code(401);
        exit();
    } catch (SignatureInvalidException $e) {
        echo json_encode(["message" => "Access denied. Invalid token signature.", "error" => $e->getMessage()]);
        http_response_code(401);
        exit();
    } catch (BeforeValidException $e) {
        echo json_encode(["message" => "Access denied. Token not valid yet.", "error" => $e->getMessage()]);
        http_response_code(401);
        exit();
    } catch (Exception $e) {
        echo json_encode(["message" => "Access denied.", "error" => $e->getMessage()]);
        http_response_code(401);
        exit();
    }
}
// Middleware for Role-based Authorization
function authorize($user, $requiredRole) {
    if ($user->role !== $requiredRole) {
        echo json_encode(["message" => "Only $requiredRole can access this resource."]);
        http_response_code(403);
        exit();
    }
}
