<?php

include(__DIR__ . "/properties_util.php");

class DatabaseConnection {
    private $connection;
    private $properties;

    function __construct() {
        $propertiesUtil = new PropertiesUtil();
        $this->properties = $propertiesUtil->readProperties();
        $properties = $this->properties;

        if ($properties === null) {
            error_log("Failed to read properties file or it is not present");
            exit();
        }

        $databaseConnectionString = $properties["database"]["driver"] . ":host=" . $properties["database"]["host"] . 
            (!empty($properties["database"]["port"]) ? ";port=" . $properties["database"]["port"] : "");

        $this->connection = new PDO($databaseConnectionString, $properties["database"]["user"], $properties["database"]["user_password"]);
    }

    public function getConnection() {
        return $this->connection;
    }

    public function getConnectionWithSelectedDatabase() {
        $stmt = $this->connection->prepare("USE " . $this->properties["database"]["name"]);
        $stmt->execute(array());

        return $this->connection;
    }
}

?>