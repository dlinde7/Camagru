<?php
include_once 'connection.php';

if (isset($_GET['id'])) {
    $sql = "SELECT userid FROM gallery WHERE id = :id";
    $st = $dp->prepare($sql);
    $st->execute(array(':id' => $_GET['id']));

    $userid = $st->fetch();

    $sql = "SELECT * FROM users WHERE id = :id";
    $st = $dp->prepare($sql);
    $st->execute(array(':id' => $userid[0]));

    if (($row = $st->fetch()) && $tp == 0) {
        $user = $row['username'];
        $email = $row['email'];
        $pref = $row['preference'];

        if ($pref == 'Y') {
            $subject = "Sticket Notification";

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
                Someone liked one of your posts.<br>
                If this is not you, please ignore this email.
            </body>';

            $ch = mail($email, $subject, $message, $header);
        }
    }
    else if($tp == 1) {
        $user = $row['username'];
        $email = $row['email'];
        $pref = $row['preference'];

        if ($pref == 'Y') {
            $subject = "Sticket Notification";

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
                Someone un-liked one of your posts.<br>
                If this is not you, please ignore this email.
            </body>';

            $ch = mail($email, $subject, $message, $header);
        }
    }
}
else {
    header('location: index.php');
}
?>