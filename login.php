<?php
include_once 'session.php';
include_once 'config/database.php';

try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {}

if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $pwd = $_POST['pwd'];

    $sqldt = "SELECT * FROM users WHERE username = :user";
    $st = $dp->prepare($sqldt);
    $st->execute(array(':user' => $user));

    if($row = $st->fetch()){
        $id = $row['id'];
        $valid = $row['valid'];
        $h_pwd = $row['password'];
        $username = $row['username'];

        if ($valid == 'Y'){
            if (password_verify($pwd, $h_pwd)) {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                header("location: index.php");
            }
            else {
                $result = "Username or Password was incorrect";
            }
        }
        else {
            $result = "Account not verified";
        }
    }
    else {
        $result = "Username or Password was incorrect";
    }
}

$dp = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Login</title>
</head>
<body>
    <a href="index.php">Home</a>
    <br>
    <?php echo $result ?>
    <form method="post" action="">
    <table>
        <tr><td>Username</td>
        <td><input type="tesxt" value="" placeholder="Enter Username" required name="user"></td></tr>
        <tr><td>Password</td>
        <td><input type="password" value="" placeholder="Enter Password" required name="pwd"></td></tr>
        <tr><td></td><td><input type="submit" value="Login" name="login"></td></tr>
        <tr><td><a href="reg.php">Sign up</a></td>
        <td>Forgot <a href="pwdre.php">password?</a></td></tr>
    </table>
    </form>
</body>
</html>