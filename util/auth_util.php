<?php

include(__DIR__ . "/database_connection.php");

class AuthUtil {
    private $dbConnection;

    function __construct() {
        $this->dbConnection = new DatabaseConnection();
    }

    public function registerUser($username, $email, $password) {
        $conn = $this->dbConnection->getConnection();
        $errors = array();

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $result = $stmt->execute(array($username));

        if ($result && $stmt->rowCount() > 0) {
            array_push($errors, "Потребител с това потребителско име вече съществува");
            return $errors;
        }

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $result = $stmt->execute(array($email));

        if ($result && $stmt->rowCount() > 0) {
            array_push($errors, "Потребител с този имейл вече съществува");
            return $errors;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query)->execute([$username, $email, $hashedPassword]);

        return true;
    }

    public function authenticateUser($username, $password) {
        $conn = $this->dbConnection->getConnection();

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $result = $stmt->execute(array($username));

        if ($result && $stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = $username;

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}


?>