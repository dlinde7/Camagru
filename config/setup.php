<?php

include 'database.php';

try {
    $dp = new PDO($DB_DSN_C, $DB_USER, $DB_PASSWORD);
    // set the PDO error mode to exception
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dp->query($sql = "CREATE DATABASE IF NOT EXISTS camagru");
    // use exec() because no results are returned
    echo "Database created successfully<br>";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage() . "<br>";
    }

try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    // set the PDO error mode to exception
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(300) NOT NULL,
    preference VARCHAR(1) NOT NULL DEFAULT 'Y',
    valid VARCHAR(1) NOT NULL DEFAULT 'N',
    token VARCHAR(100) NOT NULL UNIQUE,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    // use exec() because no results are returned
    $dp->query($sql);
    echo "Table users created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$dp = null;
?>