<?php
include_once 'connection.php';

$n = 0;
if (isset($_GET['token'])) {
    $sql = "SELECT id, valid FROM users WHERE token = :token";
    $st = $dp->prepare($sql);
    $st->execute(array(':token' => htmlentities($_GET['token'])));

    if($row = $st->fetch()){
        if ($row['valid'] != 'Y') {
            $sql2 = "UPDATE users SET valid = :valid WHERE id = :id";
            $st2 = $dp->prepare($sql2);
            $st2->execute(array(':valid' => 'Y', ':id' => $row['id']));
        }
        else {
            echo "Account already validated"; ?>
            <br><a href="login.php">Login</a>
            <?php
            $n = 5;
        }
    }
    else {
        echo "Invalid url";
    }

    if ($n != 5) {
        $sql = "SELECT id, valid FROM users WHERE token = :token";
        $st = $dp->prepare($sql);
        $st->execute(array(':token' => htmlentities($_GET['token'])));

        if ($row = $st->fetch()) {
            if ($row['valid'] == 'Y') {
                echo "Account succesfully validated"; ?>
                 <br><a href="login.php">Login</a>
            <?php
            }
            else {
                echo "Error with validation";
            }
        }
        else {
            echo "Validtion ERROR";
        }
    }
}

$dp = null;
?>