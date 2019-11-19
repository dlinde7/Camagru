<?php
include_once 'connection.php';

if (isset($_POST['reg'])) {
    $user = htmlentities($_POST['user']);
    $email = htmlentities($_POST['email']);
    $pwd = htmlentities($_POST['pwd']);
    $pwd2 = htmlentities($_POST['repwd']);
    $url = $_SERVER['HTTP_HOST'].str_replace("reg.php", "", $_SERVER['REQUEST_URI']);

    $hpwd = password_hash($pwd, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(10));
    try {
        $sql = "INSERT INTO users (username, email, password, token, reg_date)
        VALUES (:username, :email, :password, :token, now())";

        $tst = $dp->prepare($sql);
        include 'error.php';
    } catch (PDOException $e) {
        $dup = "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry";
        if (strncmp($e, $dup, strlen($dup))) {
            if (strstr($e, "username")) {
                $result2 = "Username already exist";
            }
            elseif (strstr($e, "email")) {
                $result2 = "Email already in use";
            }
        }
    }
}

$dp = null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Camagru Sign up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if(!isset($_SESSION['username'])): ?>
    <ul>
    <li><a href="index.php">Home</a></li>
    <li style="float:right"><a href="login.php">Login</a></li>
    <li style="float:right"><a class="active" href="reg.php">Sign Up</a></li>
    </ul>
    <br><br><br><br>

    <?php
    if (isset($result2)) {
        echo $result2;
    }
    if (isset($result)) {
        foreach ($result as $value) {
            echo "$value <br>";
        }
    }
    ?>

    <form method="post" action="">
    <table>
        <tr><td>Username</td>
        <td><input type="text" name="user" value="" placeholder="Enter Username" required></td></tr>
        <tr><td>Email</td>
        <td><input type="text" name="email" value="" placeholder="Enter Email" required></td></tr>
        <tr><td>Password</td>
        <td><input type="password" name="pwd" value="" placeholder="Enter Password" required></td></tr>
        <tr><td>Re-Password</td>
        <td><input type="password" name="repwd" value="" placeholder="Re-Enter Password" required></td></tr>
        <tr><td></td><td><input type="submit" name="reg" value="Sign up"></td></tr>
    </table>
    </form>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
    <?php else: ?>
    <?php header('location: index.php'); ?>
    <?php endif ?>
</body>
</html>