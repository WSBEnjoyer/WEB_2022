<?php

class DatabaseConnection {
    private $connection;

    function __construct() {
        $this->connection = new PDO("mysql:host=192.168.64.2;dbname=web_course", "webuser", "webuser");
    }

    public function getConnection() {
        return $this->connection;
    }
}

?>