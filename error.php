<?php
if (isset($_POST['reg'])) {
    if ($email != null) {
        $hold = $email;
        $hold = filter_var($hold, FILTER_SANITIZE_EMAIL);
        if (filter_var($hold, FILTER_VALIDATE_EMAIL) === false) {
            $c = 1;
        }
        else {
            $c = 0;
        }
    }

    $er = 0;
    $result = array();
    if ($pwd != $pwd2) {
        array_push($result, "Passwords do not match");
        $er = 1;
    }
    if (strlen($pwd) < 8) {
        array_push($result, "Passwords to short must be between 8-16 characters");
        $er = 1;
    }
    if (strlen($pwd) > 16) {
        array_push($result, "Passwords to long must be between 8-16 characters");
        $er = 1;
    }
    if(!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $pwd)){
        array_push($result, "Password must contain at least one uppercase character and at least one number.");
        $er = 1;
    }
    if (strlen($user) < 4) {
        array_push($result, "Username to short");
        $er = 1;
    }
    if (strlen($user) > 20) {
        array_push($result, "Username to long");
        $er = 1;
    }
    if ($c == 1) {
        array_push($result, "Not a valid email");
        $er = 1;
    }

    if ($er != 1) {
        $tst->execute(array(':username' => $user, ':email' => $email, ':password' => $hpwd, ':token' => $token));

        $subject = "Sticket Account Verification";

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
            To validate your acount, please click the link below <br>
            <a href="http://'.$url.'validate.php?token='.$token.'">Verify my email</a><br>
            Alternatively, if the link does not work, paste the url:<br> http://'.$url.'validate.php?token='.$token.'<br>
            If this is not you, please ignore this email.
        </body>
        ';

        $ch = mail($email, $subject, $message, $header);
        if ($ch == true)
        $result2 = "Verification mail has been sent to: ".$email;
        else
        $result2 = "Error";
    }
}
else {
    header('location: index.php');
}
?>