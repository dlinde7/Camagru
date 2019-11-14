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
</head>
<body>
    <a href="index.php">Home</a>
    <br>
    <?php echo $result ?>
    <?php if ($result == "Account not verified") {?>
    <a href="verify.php?re=<?php echo $user ?>"> re-send verification email?</a>    
    <?php }?>
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