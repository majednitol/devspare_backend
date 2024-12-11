<?php

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once __DIR__ . '/../config/config.php';

function generateAccessToken($row)
{
    $config = Config::getInstance();
    $payload = [
        "user_id" => $row['user_id'],
        "name" => $row['name'],
        "email" => $row['email'],
        "role" => $row['role'],
        "iat" => time(),
        "exp" => time() + $config->access_token_expire // 15 minutes
    ];
    return JWT::encode($payload, $config->access_token_secret, "HS256");
}

function generateRefreshToken($row)
{
    $config = Config::getInstance();
    $payload = [
        "user_id" => $row['user_id'],
        "name" => $row['name'],
        "email" => $row['email'],
        "role" => $row['role'],
        "iat" => time(),
        "exp" => time() + $config->refresh_token_expire // 1 week
    ];
    return JWT::encode($payload, $config->refresh_token_secret, "HS256");
}

function refreshAccessToken($refreshToken)
{
    $config = Config::getInstance();
    try {
        $decoded = JWT::decode($refreshToken, new Key($config->refresh_token_secret, 'HS256'));
        $newAccessToken = generateAccessToken((array)$decoded);

        echo json_encode([
            "message" => "Access token refreshed successfully",
            "access_token" => $newAccessToken
        ]);
        http_response_code(200);
    } catch (ExpiredException $e) {
        echo json_encode(["message" => "Refresh token has expired. Please log in again."]);
        http_response_code(401);
    } catch (Exception $e) {
        echo json_encode(["message" => "Invalid refresh token."]);
        http_response_code(401);
    }
}
