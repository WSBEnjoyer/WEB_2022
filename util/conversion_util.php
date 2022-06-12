<?php

include(__DIR__ . "/database_connection.php");

class ConversionUtil {
    private $dbConnection;

    function __construct() {
        $this->dbConnection = new DatabaseConnection();
    }

    public function recordConversion($comment, $fileName, $conversionType) {
        $conn = $this->dbConnection->getConnectionWithSelectedDatabase();
        $errors = array();
        $username = $_SESSION["user"];
        $query = "INSERT INTO conversions (username, comment, f_name, conversion_type) VALUES (?, ?, ?, ?)";
        
        $stmtResult = $conn->prepare($query)->execute([$username, $comment, $fileName, $conversionType]);

        return $stmtResult;
    }

    public function getConversionsForCurrentUser() {
        $conn = $this->dbConnection->getConnectionWithSelectedDatabase();
        $errors = array();
        $username = $_SESSION["user"];

        $query = "SELECT date, comment, conversion_type, f_name from conversions where username = ? order by date desc";

        $stmt = $conn->prepare($query);
        $result = $stmt->execute(array($username));
        return $stmt->fetchAll();
    }
}


?>