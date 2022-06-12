<?php

include(__DIR__ . "/database_connection.php");
include_once(__DIR__ . "/properties_util.php");

function displayMessage($text) {
    echo "<p>" . $text . "</p>";
}

$dbConnection = new DatabaseConnection();
$conn = $dbConnection->getConnection();

$propertiesUtil = new PropertiesUtil();
$properties = $propertiesUtil->readProperties();

// Create database
$query = "CREATE DATABASE IF NOT EXISTS " . $properties["database"]["name"];
$stmt = $conn->prepare($query);
$result = $stmt->execute(array());

if (!$result) {
    displayMessage("Error creating database");
    exit();
}

displayMessage("Database '" . $properties["database"]["name"] . "' created or already exists");

$stmt = $conn->prepare("USE " . $properties["database"]["name"]);
$stmt->execute(array());

// Create users table
$createTableQuery = 'CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `email` varchar(250) NOT NULL,
    `password` varchar(500) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username_unique` (`username`),
    UNIQUE KEY `email_unique` (`email`)
   )';

$createConversionRecordsTableQuery = 'CREATE TABLE IF NOT EXISTS `conversions` (
    `id` int(11) AUTO_INCREMENT,
    `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `username` varchar(50) NOT NULL,
    `comment` varchar(500),
    `original_file_name` varchar(100),
    `result_file_name` varchar(100),
    `conversion_type` varchar(50),
    PRIMARY KEY(`id`)
)';

$stmt = $conn->prepare($createTableQuery);
$result = $stmt->execute(array());

if (!$result) {
    displayMessage("Error creating users table");
    exit();
}

displayMessage("Users table created or already exists");

$conversionTableStmt = $conn->prepare($createConversionRecordsTableQuery);
$resultConversionTable = $conversionTableStmt->execute(array());

if (!$resultConversionTable) {
    displayMessage("Error creating conversions table");
    exit();
}

displayMessage("Conversions table created or already exists");

?>