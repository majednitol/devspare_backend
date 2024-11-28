<?php


class User
{
    private $conn;
    private $table = 'users';

    public $user_id;
    public $name;
    public $email;
    public $password;
    public $profile_pic;
    public $is_verified;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createUser()
    {
        $query = "INSERT INTO " . $this->table . " (name, email, password) 
                  VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);


        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);


        return $stmt->execute();
    }

    // Login user
    public function loginUser()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        return $stmt;
    }

    // Update profile picture
    public function updateProfilePic($profilePicPath)
    {
        $query = "UPDATE " . $this->table . " SET profile_pic = :profile_pic WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->profile_pic = htmlspecialchars(strip_tags($profilePicPath));

        // Bind parameters
        $stmt->bindParam(':profile_pic', $this->profile_pic);
        $stmt->bindParam(':user_id', $this->user_id);

        return $stmt->execute();
    }
    public function getProfilePic($userId)
    {
        $query = "SELECT profile_pic FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['profile_pic'] : null;
    }
    public function getUserById($userId)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Bind the user_id parameter
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        // Return the user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isUserAlreadyExist($email)
    {
        $stmt = $this->conn->prepare("SELECT is_verified FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $user) 
            return true;
        
    }


    // public function verifyUserByEmail($email)
    // {
    //     // Prepare SQL to check if the user exists and is not already verified
    //     $stmt = $this->conn->prepare("SELECT is_verified FROM users WHERE email = :email");
    //     $stmt->bindParam(':email', $email);
    //     $stmt->execute();
    //     $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //     if (!$user || $user['is_verified']) {
    //         return false;
    //     }
    //     $updateStmt = $this->conn->prepare("UPDATE users SET is_verified = 1 WHERE email = :email");
    //     $updateStmt->bindParam(':email', $email);

    //     return $updateStmt->execute();
    // }
}
