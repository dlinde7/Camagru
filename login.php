<?php
include_once 'session.php';
include_once 'connection.php';

if (isset($_POST['login'])) {
    $user = htmlentities($_POST['user']);
    $pwd = htmlentities($_POST['pwd']);

    $sqldt = "SELECT * FROM users WHERE username = :user";
    $st = $dp->prepare($sqldt);
    $st->execute(array(':user' => $user));

    if($row = $st->fetch()){
        $id = $row['id'];
        $valid = $row['valid'];
        $h_pwd = $row['password'];
        $username = $row['username'];
        $preference= $row['preference'];

        if ($valid == 'Y'){
            if (password_verify($pwd, $h_pwd)) {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['time'] = time();

                if (isset($_POST['rememberme'])) {
                    setcookie("username", $_POST['user'], time() + (365*60*24*24));
                    setcookie("password", $_POST['pwd'], time() + (365*60*24*24));
                }
                if ($preference == 'Y') {
                    $_SESSION['preference'] = "ON";
                }
                else {
                    $_SESSION['preference'] = "OFF";
                }
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

if ($_GET['re']) {
    $sqldt = "SELECT * FROM users WHERE username = :user";
    $st = $dp->prepare($sqldt);
    $st->execute(array(':user' => htmlentities($_GET['re'])));

    if($row = $st->fetch()){
        $id = $row['id'];
        $valid = $row['valid'];
        $h_pwd = $row['password'];
        $username = $row['username'];
        $token = $row['token'];
    }

    $url = $_SERVER['HTTP_HOST'].str_replace("reg.php", "", $_SERVER['REQUEST_URI']);

    $subject = "Sticket Account Verification";

    $header = 'MIME-Version: 1.0'."\r\n";
    $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
    $header .= 'From: Sticket@NoReply.co.za'."\r\n";

    $message = '
    <html>
    <head>
        <title>'.$subject.'</title>
    <head>
    <body>
        '.$username.' Welcome to Sticket.<br>
        To validate your acount, please click the link below <br>
        <a href="http://'.$url.'validate.php?token='.$token.'">Verify my email</a><br>
        Alternatively, if the link does not work, paste the url:<br> http://'.$url.'validate.php?token='.$token.'<br>
        If this is not you, please ignore this email.
    </body>
    ';

    $ch = mail($email, $subject, $message, $header);
    if ($ch == true)
    header('location: index.php');
    }

$dp = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if(!isset($_SESSION['username'])): ?>
    <ul>
    <li><a href="index.php">Home</a></li>
    <li style="float:right"><a class="active" href="login.php">Login</a></li>
    <li style="float:right"><a href="reg.php">Sign Up</a></li>
    </ul>
    <br><br><br><br>
    <?php echo $result ?>
    <?php if ($result == "Account not verified") {?>
    <a href="verify.php?re=<?php echo $user ?>"> re-send verification email?</a>    
    <?php }?>
    <form method="post" action="">
    <table>
        <tr><td>Username</td>
        <td><input type="text" value="<?php if (isset($_COOKIE['username'])) {echo $_COOKIE['username'];} ?>" placeholder="Enter Username" required name="user"></td></tr>
        <tr><td>Password</td>
        <td><input type="password" value="<?php if (isset($_COOKIE['password'])) {echo $_COOKIE['password'];} ?>" placeholder="Enter Password" required name="pwd"></td></tr>
        <tr><td></td><td><input type="checkbox" name="rememberme">Remember me</td></tr>
        <tr><td></td><td><input type="submit" value="Login" name="login"></td></tr>
        <tr><td></td><td>Forgot <a href="pwdre.php">password?</a></td></tr>
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