<?php
    include_once 'connection.php';

    if ($_GET['re']) {

        $sql = "SELECT * FROM users WHERE username = :user";
        $st = $dp->prepare($sql);
        $st->execute(array(':user' => htmlentities($_GET['re'])));
    
        if($row = $st->fetch()){
            $id = $row['id'];
            $email = $row['email'];
            $username = $row['username'];
            $token = $row['token'];
        }

        $url = "localhost:8080/Camagru/";
    
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
        if ($ch == true) {
            header('location: login.php');
        }
        else {
            echo "ERROR";
        }

        $dp = null;
    }
    else {
        header('location: login.php');
    }
    ?>