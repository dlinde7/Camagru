<?php
include_once 'config/database.php';

try {
    $dp = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {}

$n = 0;
if (isset($_GET['token'])) {
    $sql = "SELECT id, valid FROM users WHERE token = :token";
    $st = $dp->prepare($sql);
    $st->execute(array(':token' => $_GET['token']));

    if($row = $st->fetch()){
        if ($row['valid'] != 'Y') {
            $sql2 = "UPDATE users SET valid = :valid WHERE id = :id";
            $st2 = $dp->prepare($sql2);
            $st2->execute(array(':valid' => 'Y', ':id' => $row['id']));
        }
        else {
            echo "Account already validated";
            $n = 5;
        }
    }
    else {
        echo "Invalid url";
    }

    if ($n != 5) {
        $sql = "SELECT id, valid FROM users WHERE token = :token";
        $st = $dp->prepare($sql);
        $st->execute(array(':token' => $_GET['token']));

        if ($row = $st->fetch()) {
            if ($row['valid'] == 'Y') {
                echo "Account succesfully validated";
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
?>