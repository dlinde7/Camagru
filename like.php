<?php
include_once 'session.php';
include_once 'connection.php';

if (isset($_SESSION['username'])) {
    
    try {
        $sql = "SELECT * FROM `like` WHERE id = :id AND userid = :userid";
        $st = $dp->prepare($sql);
        $st->execute(array(':id' => $_GET['id'], ':userid' => $_SESSION['id']));
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
   
    $td = 0;
    if ($row = $st->fetch()) {
        $userid = $row['userid'];
        $lk = $row['like'];
        $td = 1;
    }

    if ($td == 1) {
        if ($lk == 'Y') {
            $sql = "UPDATE `like` SET `like` = :lk WHERE id = :id AND userid = :userid";
            $st = $dp->prepare($sql);
            $st->execute(array(':lk' => 'N', ':id' => $_GET['id'], ':userid' => $userid));
            $tp = 1;
            require 'lkerror.php';
        }
        else {
            $sql = "UPDATE `like` SET `like` = :lk WHERE id = :id AND userid = :userid";
            $st = $dp->prepare($sql);
            $st->execute(array(':lk' => 'Y', ':id' => $_GET['id'], ':userid' => $userid));
            $tp = 0;
            require 'lkerror.php';
        }
    }
    else {
        echo "test";
        try {
            $sql = "INSERT INTO `like` (id, userid, `like`, up_date)
            VALUES (:id, :userid, :lk, now())";
    
            $tst = $dp->prepare($sql);
            $tst->execute(array(':id' => $_GET['id'], ':userid' => $_SESSION['id'], ':lk' => 'Y'));
            $tp = 0;
            require 'lkerror.php';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
header('location: com.php?id='.$_GET['id'].'');
?>