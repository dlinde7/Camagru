<?php
include_once 'session.php';

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
    <meta charset="utf-8">
	<title>Document</title>

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

    <div class="booth">
		    <video id="video" width="400" height="300" autoplay></video>
            <canvas id="canvas" width="400" height="300" name="image"></canvas>
            <img id="photo" src="Logo/43.png" alt="" width="400">
            <br>
            <button id="snap" class="booth-capture-button">Take photo</button>
            <button id="upload" class="booth-capture-button">Upload</button>
    </div>
    
	<script src="ImageCapture.js"></script>
    
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

    try{
        $sql = "SELECT * FROM upload WHERE userid = :userid";
        $st = $dp->prepare($sql);
        $st->execute(array(':userid' => htmlentities($_SESSION['id'])));
        $total = $st->rowCount();
        if(!isset($_GET['page'])){
            $page = 1;
        }
        else{
            if(is_numeric($_GET['page'])){
                $page = $_GET['page'];
            }
            else{
                $page = 1;
            }
        }
        $amount = 10;
        $numpages = ceil($total/$amount);
        $start = ($page - 1) * $amount;

        $sql = "SELECT * FROM upload WHERE userid = :userid ORDER BY id DESC LIMIT $start, $amount";
        $st = $dp->prepare($sql);
        $st->execute(array(':userid' => htmlentities($_SESSION['id'])));
        While ($row = $st->fetch()) {
            echo '<a href="post.php?id='.$row['id'].'">
                <img src="upload/'.$row['imgname'].'">
                </a>';
        }
        echo '<hr>';
        for($page = 1; $page <= $numpages; $page++){
            echo "<a href='upload.php?page=".$page."'> ".$page." </a>";
        }
    }
    catch(PDOException $err){
    }
        ?>
    </div>
    <?php endif ?>
</body>
</html>