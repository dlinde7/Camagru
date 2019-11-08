<?php
include_once 'session.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Home</title>
</head>
<body>

    <?php if(!isset($_SESSION['username'])): ?>
    <a href="login.php">Login</a>
    <br>
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="accountset.php">Profile settings</a>
    <a href="logout.php">Logout</a>
    <?php endif ?>
</body>
</html>