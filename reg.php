<?php
include 'config/database.php';

try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {}

if (isset($_POST['user'])) {
    $user = $_POST['user'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $pwd2 = $_POST['repwd'];

    $hpwd = password_hash($pwd, PASSWORD_DEFAULT);
    try {
        $sql = "INSERT INTO users (username, email, password, reg_date)
        VALUES (:username, :email, :password, now())";

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
</head>
<body>
    <a href="index.php">Home</a>
    <br>

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
        <td><input type="tesxt" name="user" value="" placeholder="Enter Username" required></td></tr>
        <tr><td>Email</td>
        <td><input type="tesxt" name="email" value="" placeholder="Enter Email" required></td></tr>
        <tr><td>Password</td>
        <td><input type="password" name="pwd" value="" placeholder="Enter Password" required></td></tr>
        <tr><td>Re-Password</td>
        <td><input type="password" name="repwd" value="" placeholder="Re-Enter Password" required></td></tr>
        <tr><td></td><td><input type="submit" value="Sign up"></td></tr>
    </table>
    </form>
</body>
</html>