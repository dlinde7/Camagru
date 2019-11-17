<?php
include_once 'session.php';

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
    <title>Camagru Home</title>
    <link rel="stylesheet" type="text/css" href="videoCss.css">
</head>
<body>
    <a href="index.php">Home</a>
    <?php if(!isset($_SESSION['username'])): ?>
    <a href="login.php">Login</a>
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="accountset.php">Profile settings</a>
    <a href="upload.php">Upload</a>
    <a href="logout.php">Logout</a>

    <div>
		<video id="video" width="400" height="300" autoplay></video>
		<a href="" id="capture">Take photo</a>
		<canvas id="canvas" width="400" height="300"></canvas>
	</div>
​
	<script src="image.js"></script>
    
    <?php if (isset($result)) {
        echo $result;
    }?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit" name="submit">UPLOAD</button>
        </form>
    <div class="gg">
    <?php

    include_once 'connection.php';

    $sql = "SELECT * FROM upload WHERE userid = :userid ORDER BY id DESC";
    $st = $dp->prepare($sql);
    $st->execute(array(':userid' => $_SESSION['id']));

    While ($row = $st->fetch()) {
        echo '<a href="post.php?id='.$row['id'].'">
            <img src="upload/'.$row['imgname'].'">
            </a>';
    }
        ?>
    </div>
    <?php endif ?>
</body>
</html>