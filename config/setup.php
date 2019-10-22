<?php
$servername = "localhost";
$username = "root";
$password = "Azdcgbjml";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS Camagaru";
if ($conn->query($sql) != TRUE) {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

$dbname = "Camagaru";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(100) NOT NULL
  )";

if ($conn->query($sql) != TRUE) {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>