<?php
include_once 'connection.php';

if (isset($_POST['email'])) {
    $email = htmlentities($_POST['email']);
    $pwd = bin2hex(random_bytes(5));

    $hpwd = password_hash($pwd, PASSWORD_DEFAULT);

    $sql = "SELECT id, username, password, valid FROM users WHERE email = :email";
    $st = $dp->prepare($sql);
    $st->execute(array(':email' => $email));

    if($row = $st->fetch()) {
        if ($row['valid'] == 'Y') {
            $sql2 = "UPDATE users SET password = :password WHERE id = :id";
            $st2 = $dp->prepare($sql2);
            $st2->execute(array(':password' => $hpwd, ':id' => $row['id']));

            $user = $row['username'];
        
            $subject = "Sticket Password Reset";

            $header = 'MIME-Version: 1.0'."\r\n";
            $header .= 'Content-type: text/html; charset=UTF-8'."\r\n";
            $header .= 'From: Sticket@NoReply.co.za'."\r\n";

            $message = '
            <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                '.$user.' Welcome to Sticket.<br>
                Use the password below to 
                <a href="http://localhost:8080/Camagru/login.php">login</a> 
                to your account: <br>
                '.$pwd.' <br>
                If this is not you, please inform us if you suspect security breach.
            </body>
            ';

            $ch = mail($email, $subject, $message, $header);
            if ($ch == true)
            header("location: login.php");
            else
            $result2 = "Error";
        }
        else {
            $result2 = "Account not verified: check your email for verefication mail";
        }
    }
    else {
        $result2 = "Not a registered email.";
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
    <li style="float:right"><a class="active" href="login.php">Login</a></li>
    <li style="float:right"><a href="reg.php">Sign Up</a></li>
    </ul>
    <br><br><br><br>

    <?php
    if (isset($result2)) {
        echo $result2;
    }
    ?>

    <form method="post" action="">
    <table>
        <tr><td>Email</td>
        <td><input type="tesxt" name="email" value="" placeholder="Enter Email" required></td></tr>
        <tr><td></td><td><input type="submit" value="Send"></td></tr>
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