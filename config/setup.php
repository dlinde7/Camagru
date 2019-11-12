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
    echo "Table users created successfully<br>";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    // set the PDO error mode to exception
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS gallery (
    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    imgname VARCHAR(30) NOT NULL UNIQUE,
    imgtitle VARCHAR(30) NOT NULL,
    imgdes VARCHAR(200) NOT NULL,
    userid INT(10) NOT NULL,
    up_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    // use exec() because no results are returned
    $dp->query($sql);
    echo "Table gallery created successfully<br>";
    }
catch(PDOException $e)
{
    echo $sql . "<br>" . $e->getMessage();
}
try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    // set the PDO error mode to exception
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS com (
    `id` INT(100) NOT NULL , 
    `user` VARCHAR(30) NOT NULL ,
    `com` VARCHAR(150) NOT NULL, 
    up_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    // use exec() because no results are returned
    $dp->query($sql);
    echo "Table com created successfully<br>";
    }
catch(PDOException $e)
{
    echo $sql . "<br>" . $e->getMessage();
}
try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    // set the PDO error mode to exception
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS `like` (
    `id` INT(100) NOT NULL , 
    `userid` INT(10) NOT NULL ,
    `like` VARCHAR(1) NOT NULL, 
    up_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    // use exec() because no results are returned
    $dp->query($sql);
    echo "Table like created successfully";
    }
catch(PDOException $e)
{
    echo $sql . "<br>" . $e->getMessage();
}

$dp = null;
?>