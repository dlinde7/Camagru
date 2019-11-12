<?php
include_once 'session.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Home</title>
</head>
<body>
    <a href="">Home</a>
    <?php if(!isset($_SESSION['username'])): ?>
    <a href="login.php">Login</a>
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="account.php">Profile</a>
    <a href="logout.php">Logout</a>
    <?php endif ?>
    <div class="gg">
    <?php

    include_once 'connection.php';

    $sql = "SELECT * FROM gallery ORDER BY id DESC";
    $st = $dp->prepare($sql);
    $st->execute();

    While ($row = $st->fetch()) {
        echo '<a href="com.php?id='.$row['id'].'">
            <img src="gallery/'.$row['imgname'].'">
            </a>';
    }
        ?>
    </div>
</body>
</html>