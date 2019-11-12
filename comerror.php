<?php
$dd = 0;
if (strlen($com) > 150) {
    $result = "Comment to long must be less than 150 characters.";
    $dd = 1;
}

if ($dd != 1) {
    $tst->execute(array(':id' => $_GET['id'], ':user' => $_SESSION['username'], ':com' => $com));

    include_once 'connection.php';

    $sql = "SELECT userid FROM gallery WHERE id = :id";
    $st = $dp->prepare($sql);
    $st->execute(array(':id' => $_GET['id']));

    $userid = $st->fetch();

    $sql = "SELECT * FROM users WHERE id = :id";
    $st = $dp->prepare($sql);
    $st->execute(array(':id' => $userid[0]));

    if ($row = $st->fetch()) {
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
                Someone commented on one of your posts.<br>
                '.$com.'<br>
                If this is not you, please ignore this email.
            </body>
            ';

            $ch = mail($email, $subject, $message, $header);
        }
    }
}
?>