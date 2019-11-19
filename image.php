<?php
include_once 'session.php';
include_once 'connection.php';

if (!empty($_POST['baseimage'])) {

    try {
        $filenew = uniqid('', true ).".jpg";
        $image = explode(',', $_POST['baseimage']);
        $test = base64_decode($image[1]);
        file_put_contents("upload/".$filenew, $test);

        $sql = "INSERT INTO upload (imgname, userid, up_date)
        VALUES (:imgname, :userid, now())";
        $st = $dp->prepare($sql);
        $st->execute(array(':imgname' => $filenew, ':userid' => $_SESSION['id']));
        header('location: upload.php');
    } catch (PDOException $e) {
        $result = $e->getMessage();
    }

    try {

        $sql = "SELECT * FROM upload WHERE imgname = :imgname";
        $st = $dp->prepare($sql);
        $st->execute(array(':imgname' => $filenew));
        $row = $st->fetch();
        header('location: upload.php?id='.$row['id'].'');
    } catch (PDOException $e) {
        $result = $e->getMessage();
    }
}
else {
    header('location: index.php');
}
?>