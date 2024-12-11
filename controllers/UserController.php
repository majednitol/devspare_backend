<?php
include_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../mail/activation-mail.php';
require __DIR__ . '/../file/coludinary.php';
require __DIR__ . '/../utils/jwt.php';

use \Firebase\JWT\JWT;

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Database.php';

class UserController
{
    private $db;
    private $config;


    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->config = Config::getInstance();
    }

    // Register a new user
    public function register($name, $email, $password)
    {
        $user = new User($this->db);
        if ($user->isUserAlreadyExist(($email))) {
            http_response_code(400);
            echo json_encode(["message" => "User already exists"]);
        } else {
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $verificationCode = rand(100000, 999999);

            // Create token data with code and expiration
            $tokenData = [
                "name" => $user->name,
                "email" => $user->email,
                "password" => $user->password,
                "code" => $verificationCode,
                "exp" => time() + $this->config->activation_expire,
            ];

            // Generate the activation token
            $activationToken = JWT::encode($tokenData, $this->config->activation_secret, 'HS256');

            // Send activation code via email
            sendVerificationEmail($email, $verificationCode);
            echo json_encode(["message" => "User registered. Please check your email to activate your account.", "activation_token" => $activationToken]);
        }
    }

    public function activateUser($code)
    {

        try {
            $decoded = authenticate();
            $user = new User($this->db);
            $user->email = $decoded->email;
            $user->password = $decoded->password;
            $user->name = $decoded->name;
            $expectedCode = $decoded->code;

            if ($code == $expectedCode) {
                if ($user->createUser()) {
                    echo json_encode(["message" => "Account created successfully."]);

                    http_response_code(201);
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Failed to create account."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Invalid verification code."]);
            }
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid or expired token."]);
        }
    }
    // Log in an existing user
    public function login($email, $password)
    {
        $userData = new User($this->db);
        $userData->email = $email;
        $stmt = $userData->loginUser();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            $accessToken = generateAccessToken($row);
            $refreshToken = generateRefreshToken($row);
            // echo json_encode(["token" => $jwt]);
            echo json_encode(["message" => "Login successful",$accessToken,$refreshToken]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid email or password."]);
        }
    }


    public function logout()
    {
        http_response_code(200);
        echo json_encode(["message" => "Logged out successfully."]);
    }

    // Update user profile picture
    public function updatePicture($user_id, $imagePath)
    {
        $user = new User($this->db);
        $user->user_id = $user_id;
        $imageType = 'profile';
        $publicId = "{$imageType}_user_{$user_id}";
        $folder = "{$imageType}_pics";
        echo $publicId;
        $profile_pic_uri = $user->getProfilePic($user->user_id);
        if ($profile_pic_uri) {
            deleteFromCloudinary($publicId);
        }

        $cloudinaryUrl = uploadToCloudinary($imagePath, $publicId, $folder);
        echo "Uploaded image to Cloudinary...$cloudinaryUrl";
        if ($cloudinaryUrl) {
            if ($user->updateProfilePic($cloudinaryUrl)) {
                echo json_encode(["message" => "Profile picture updated successfully."]);
                http_response_code(200);
            } else {
                echo json_encode(["message" => "Failed to update profile picture in database."]);
                http_response_code(500);
            }
        } else {
            echo json_encode(["message" => "Failed to upload image to Cloudinary."]);
            http_response_code(500);
        }
    }
    public function getUserById($user_id)
    {
        $user = new User($this->db);
        $userData = $user->getUserById($user_id);

        if ($userData) {
            echo json_encode($userData);
        } else {
            echo json_encode(["message" => "User not found."]);
        }
    }

    public function updateAcessToken()
    {
        // Retrieve the refresh token from the request (e.g., Authorization header or POST body)
        $refreshToken = $_POST['refresh_token'] ?? null;
    
        if ($refreshToken) {
            refreshAccessToken($refreshToken);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Refresh token is required."]);
        }
    }
}
