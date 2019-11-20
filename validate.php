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
            $result = "Account already validated";
            $n = 5;
        }
    }
    else {
        $result = "Invalid url";
    }

    if ($n != 5) {
        $sql = "SELECT id, valid FROM users WHERE token = :token";
        $st = $dp->prepare($sql);
        $st->execute(array(':token' => htmlentities($_GET['token'])));

        if ($row = $st->fetch()) {
            if ($row['valid'] == 'Y') {
                $result =  "Account succesfully validated";
                 

            }
            else {
                $result = "Error with validation";
            }
        }
        else {
            $result = "Validtion ERROR";
        }
    }
}

$dp = null;
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
    <li style="float:right"><a href="login.php">Login</a></li>
    </ul>
    <br><br><br>
    <?php
        echo $result;
     ?>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
</body>
</html>