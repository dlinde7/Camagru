<?php
$c = 0;
$result = array();
$ch = array();
if (isset($user)) {
    if (strlen($user) < 4) {
        array_push($result, "Username to short");
    }
    else {
        try {
            $sql = "UPDATE users SET username = :user WHERE id = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':user' => $user, ':id' => $row['id']));
            $_SESSION['username'] = $user;
            array_push($ch, "Username");
        } catch (PDOException $e) {
            array_push($result, "Username already in use");
        }
    }
}
if (isset($email) && $emal != null) {
    $hold = $email;
    $hold = filter_var($hold, FILTER_SANITIZE_EMAIL);
    if (filter_var($hold, FILTER_VALIDATE_EMAIL) === false) {
        array_push($result, "Not a valid email");
    }
    else {
        try {
            $sql = "UPDATE users SET email = :email WHERE id = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':email' => $email, ':id' => $row['id']));
            array_push($ch, "Email");
        } catch (PDOException $e) {
            array_push($result, "Email already in use");
        }
    }
}
else {
    $email = $row['email'];
}
if (isset($pwd) && isset($pwd2) && $pwd != null) {
    if ($pwd != $pwd2) {
        array_push($result, "Passwords do not match");
        $c = 1;
    }
    if (strlen($pwd) < 8) {
        array_push($result, "Passwords to short must be between 8-16 characters");
        $c = 1;
    }
    if (strlen($pwd) > 16) {
        array_push($result, "Passwords to long must be between 8-16 characters");
        $c = 1;
    }
    if(!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $pwd)){
        array_push($result, "Password must contain at least one uppercase character and at least one number.");
        $c = 1;
    }
    if ($c == 0) {
        $hpwd = password_hash($pwd, PASSWORD_DEFAULT);
        try {
            $sql = "UPDATE users SET password = :pwd WHERE id = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':pwd' => $hpwd, ':id' => $row['id']));
            array_push($ch, "Password");
        } catch (PDOException $e) {
            array_push($result, "Error in creating new Password");
        }
    }
}
if (isset($pref)) {
    if ($pref != $_SESSION['preference']) {
        if ($pref == "ON") {
            try {
                $sql = "UPDATE users SET preference = :pref WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':pref' => "Y", ':id' => $row['id']));
                $_SESSION['preference'] = "ON";
                array_push($ch, "Preference");
            } catch (PDOException $e) {}
        }
        if ($pref == "OFF") {
            try {
                $sql = "UPDATE users SET preference = :pref WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':pref' => "N", ':id' => $row['id']));
                $_SESSION['preference'] = "OFF";
                array_push($ch, "Preference");
            } catch (PDOException $e) {}
        }
    }
}

if ($ch) {

    $subject = "Sticket Account Changes";

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
        The following has been changes on your profile:<br>
        ';
    foreach ($ch as $value) {
            $message .= $value.'<br>';
        };
    $message .= 'If this is not you and you suspect a secerity breach please let us know.
    </body>
    ';

    $ch = mail($email, $subject, $message, $header);
    if ($ch == true)
    $result2 = "Your changes have been sent to ".$email;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="accountset.php">Profile settings</a></li>
    <li style="float:right"><a href="logout.php">Logout</a></li>
    <li style="float:right"><a href="upload.php">Upload</a></li>
    </ul>
    <br><br>
    <?php if (!isset($result2) && $result == NULL) {
        echo '<div class="page">
            <p>Page not Accesible</p>
            </div>';
    } ?>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
</body>
</html>