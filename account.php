<?php
include_once 'session.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Home</title>
</head>
<body>
    <a href="index.php">Home</a>
    <?php if(!isset($_SESSION['username'])): ?>
    <a href="login.php">Login</a>
    <br>
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="accountset.php">Profile settings</a>
    <a href="logout.php">Logout</a>
    <div class="gg">
    <?php

    include_once 'connection.php';

    $sql = "SELECT * FROM gallery WHERE userid = :userid ORDER BY id DESC";
    $st = $dp->prepare($sql);
    $st->execute(array(':userid' => $_SESSION['id']));

    While ($row = $st->fetch()) {
        echo '<a href="com.php?id='.$row['id'].'">
            <img src="gallery/'.$row['imgname'].'">
            </a>';
    }
        ?>
    </div>
    <?php endif ?>
</body>
</html>