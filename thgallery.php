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
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="account.php">Profile</a>
    <a href="upload.php">Upload</a>
    <a href="logout.php">Logout</a>
    <?php endif ?>
    <?php
    include_once 'connection.php';

    $sql = "SELECT * FROM users WHERE id = :id";
    $st = $dp->prepare($sql);
    $st->execute(array(':id' => htmlentities($_GET['userid'])));
    $row = $st->fetch();
    echo '<h1>'.$row['username'].'</h1>';
    
    ?>
    <div class="gg">
    <?php

    include_once 'connection.php';

    $sql = "SELECT * FROM gallery WHERE userid = :userid ORDER BY id DESC";
    $st = $dp->prepare($sql);
    $st->execute(array(':userid' => htmlentities($_GET['userid'])));

    While ($row = $st->fetch()) {
        echo '<a href="com.php?id='.$row['id'].'">
            <img src="gallery/'.$row['imgname'].'">
            </a>';
    }
        ?>
    </div>
</body>
</html>