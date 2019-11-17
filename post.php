<?php
include_once 'session.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Home</title>
</head>
<body>

    <a href="index.php">Home</a>
    <?php if(!isset($_SESSION['username'])): ?>
    <a href="login.php">Login</a>
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="account.php">Profile</a>
    <a href="upload.php">Upload</a>
    <a href="logout.php">Logout</a>
    <div class="gg">
    <?php
    include_once 'connection.php';

        $sql = "SELECT * FROM upload WHERE id = :id";
        $st = $dp->prepare($sql);
        $st->execute(array(':id' => htmlentities($_GET['id'])));

        if ($row = $st->fetch()) {
            $file = 'upload/'.$row['imgname'];
            $filenew = 'gallery/'.$row['imgname'];
            $name = $row['imgname'];
            echo '<img src="upload/'.$row['imgname'].'">';
        }
        ?>
        <?php 
        if (isset($result)) {
            echo $result;
        } ?>
        <form method="post" action="">
        <table>
            <tr><td>Title</td>
            <td><input type="text" name="title" placeholder="Enter Title"></td></tr>
            <tr><td>Description</td>
            <td><textarea name="des" placeholder="Enter description..."></textarea></td></tr>
            <tr><td><input type="submit" name="del" value="Delete"></td><td><input type="submit" name="post" value="Post"></td></tr>
        </table>
        <?php
        include_once 'connection.php';

        if (isset($_POST['post'])) {
            $title = htmlentities($_POST['title']);
            $des = htmlentities($_POST['des']);
            copy($file, $filenew);
        
            try {
                $sql = "INSERT INTO gallery (imgname, imgtitle, imgdes, userid, up_date)
                VALUES (:imgname, :imgtitle, :imgdes, :userid, now())";
                $tst = $dp->prepare($sql);
                $tst->execute(array(':imgname' => $name, ':imgtitle' => $title, ':imgdes' => $des, ':userid' => $_SESSION['id']));
                header('location: upload.php');
            } catch (PDOException $e) {}
        }
        if (isset($_POST['del'])) {
            unlink('upload/'.$row['imgname']);

            try {
                $sql = "DELETE FROM upload WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':id' => htmlentities($_GET['id'])));
                header('location: upload.php');
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        ?>
    </div>
    <?php endif ?>
</body>
</html>
