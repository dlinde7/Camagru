<?php
include_once 'session.php';
include_once 'connection.php';
if (isset($_GET['id'])) {
    if ($_GET['id'] === $_SESSION['token']) {
        try {
            $sql = "SELECT * FROM gallery WHERE userid = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));

            $test = array();
            While ($row = $st->fetch()) {
                unlink('gallery/'.$row['imgname']);
                unlink('upload/'.$row['imgname']);
                array_push($test, $row['id']);
            }
            
            $n = 0;
            while ($test[$n]) {
                $sql = "DELETE FROM com WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':id' => htmlentities($test[$n])));
        
                $sql = "DELETE FROM `like` WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':id' => htmlentities($test[$n])));

                $n++;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        try {
            $sql = "DELETE FROM gallery WHERE userid = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));

            $sql = "DELETE FROM upload WHERE userid = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));

            $sql = "DELETE FROM users WHERE id = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));
            header('location: logout.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    else {
        header('location: index.php');
    }
}
else {
    header('location: index.php');
}
?>
