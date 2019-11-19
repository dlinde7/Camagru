<?php
include_once 'session.php';

if (isset($_SESSION['username'])) {
    if ((time() - $_SESSION['time']) > 600) {
        header("location: logout.php");
    }
    else {
        $_SESSION['time'] = time();
    }
}

if (isset($_POST['dd'])) {
    echo $_POST['dd'];
}

if (isset($_POST['submit'])) {
    $file = $_FILES['file'];
    $filenm = $file['name'];
    $filetmpnm = $file['tmp_name'];
    $filesize = $file['size'];
    $fileerror = $file['error'];

    $filex = explode('.', $filenm);
    $filex = strtolower(end($filex));

    $allowed = array('jpeg', 'jpg', 'png', 'gif');

    if (in_array($filex, $allowed)) {
        if ($fileerror === 0) {
            if ($filesize < 1000000) {
                $filenew = uniqid('', true ).'.'.$filex;
                $filedes = 'upload/'.$filenew;
                move_uploaded_file($filetmpnm, $filedes);
                try {
                    include_once 'connection.php';

                    $sql = "INSERT INTO upload (imgname, userid, up_date)
                    VALUES (:imgname, :userid, now())";
                    $st = $dp->prepare($sql);
                    $st->execute(array(':imgname' => $filenew, ':userid' => $_SESSION['id']));
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
                $result = "File to big";
            }
        }
        else {
            $result = "There was an error when uploading this file";
        }
    }
    else {
        $result =  "You can not upload files of this type";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if(!isset($_SESSION['username'])): ?>
    <?php header('location: login.php'); ?>
    <?php else: ?>
    <br><br><br>
    <div class="gg2">
    <div class="booth">
		    <video id="video" width="400" height="300" autoplay></video>
            <canvas id="canvas" width="400" height="300" name="image"></canvas>
            <img id="photo" src="Logo/43.png" alt="" width="400">
            <br>
            <button id="snap" class="booth-capture-button">Take photo</button>
            <button id="upload" class="booth-capture-button">Use</button>
    </div>
    
	<script src="ImageCapture.js"></script>
    <br>
    <?php if (isset($result)) {
        echo $result;
    }?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit" name="submit">Use</button>
    </form>
    <?php
    require 'post.php';
    ?>
    </div>
    <br><br><br>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
    <?php endif ?>
</body>
</html>