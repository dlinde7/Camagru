<?php
include_once 'config/database.php';

try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {}
?>