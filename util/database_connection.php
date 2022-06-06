<?php

include("properties_util.php");

class DatabaseConnection {
    private $connection;

    function __construct() {
        $propertiesUtil = new PropertiesUtil();
        $properties = $propertiesUtil->readProperties();

        if ($properties === null) {
            error_log("Failed to read properties file or it is not present");
            exit();
        }

        $databaseConnectionString = $properties["database"]["driver"] . ":host=" . $properties["database"]["host"] . 
            (!empty($properties["database"]["port"]) ? ";port=" . $properties["database"]["port"] : "") . ";dbname=" . $properties["database"]["name"];

        $this->connection = new PDO($databaseConnectionString, $properties["database"]["user"], $properties["database"]["user_password"]);
    }

    public function getConnection() {
        return $this->connection;
    }
}

?>