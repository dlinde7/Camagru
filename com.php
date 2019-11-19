<?php
include_once 'session.php';
include_once 'connection.php';

if (isset($_SESSION['username'])) {
    if ((time() - $_SESSION['time']) > 600) {
        header("location: logout.php");
    }
    else {
        $_SESSION['time'] = time();
    }
}
if (isset($_POST['com'])) {
    $com = htmlentities($_POST['com']);
    
    try {
        $sql = "INSERT INTO com (id, user, com, up_date)
        VALUES (:id, :user, :com, now())";

        $tst = $dp->prepare($sql);
        include 'comerror.php';
    } catch (PDOException $e) {}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sticket</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul>
    <li><a href="index.php">Home</a></li>
    <?php if(!isset($_SESSION['username'])): ?>
    <li style="float:right"><a href="login.php">Login</a></li>
    <li style="float:right"><a href="reg.php">Sign Up</a></li>
    </ul>
    <?php else: ?>
    <li><a href="account.php">Profile</a></li>
    <li style="float:right"><a href="logout.php">Logout</a></li>
    <li style="float:right"><a href="upload.php">Upload</a></li>
    </ul>
    <?php endif ?>
    <br><br><br>
    <div class="gg">
    <?php
    include_once 'connection.php';

        $sql = "SELECT * FROM gallery WHERE id = :id";
        $st = $dp->prepare($sql);
        $st->execute(array(':id' => htmlentities($_GET['id'])));

        if ($row = $st->fetch()) {
            echo '<img src="gallery/'.$row['imgname'].'">
                <h1>'.$row["imgtitle"].'</h1>
                <p>'.$row["imgdes"].'</p>
                <a href="thgallery.php?userid='.$row['userid'].'">Their Gallery</a>';
            if ($_SESSION['id'] == $row['userid']) { ?>
                <form method="post" action="">
                <input type="submit" name="del" value="Delete">
                </form>
                <?php
                if (isset($_POST['del'])) {
                    unlink('gallery/'.$row['imgname']);

                    include_once 'connection.php';

                    try {
                        $sql = "DELETE FROM gallery WHERE id = :id";
                        $st = $dp->prepare($sql);
                        $st->execute(array(':id' => htmlentities($_GET['id'])));

                        $sql = "DELETE FROM com WHERE id = :id";
                        $st = $dp->prepare($sql);
                        $st->execute(array(':id' => htmlentities($_GET['id'])));

                        $sql = "DELETE FROM `like` WHERE id = :id";
                        $st = $dp->prepare($sql);
                        $st->execute(array(':id' => htmlentities($_GET['id'])));
                        header('location: index.php');
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                }
            }
        }

        $sql2 = "SELECT `like`, userid FROM `like` WHERE id = :id";
        $st2 = $dp->prepare($sql2);
        $st2->execute(array(':id' => htmlentities($_GET['id'])));

        $lik = 0;
        $lik2 = 0;
        While ($row2 = $st2->fetch()) {
            if ($row2['like'] == 'Y')
            $lik = $lik + 1;
            if ($row2['like'] == 'Y' && $row2['userid'] == $_SESSION['id'] && isset($_SESSION['id'])) {
                $lik2 = 1;
            }
        }

        if ($lik2 == 1) {
            echo '<p>'.$lik.'<a href="like.php?id='.$_GET['id'].'">&#x2764</a></p>';
        }
        else {
            echo '<p>'.$lik.'<a href="like.php?id='.$_GET['id'].'">&#x2661</a></p>';
        }
        ?>
        <?php if(isset($_SESSION['username'])): ?>
        <?php 
        if (isset($result)) {
            echo $result;
        } ?>
        <form method="post" action="">
        <table>
            <tr><td>Comment</td>
            <td><textarea style="resize: none" name="com" placeholder="Enter comment..." required></textarea></td></tr>
            <tr><td></td><td><input type="submit" value="Post"></td><td>Does not take emojies</td></tr>
        </table>
        <?php endif ?>
        <?php
        $sql3 = "SELECT * FROM com WHERE id = :id ORDER BY up_date DESC";
        $st3 = $dp->prepare($sql3);
        $st3->execute(array(':id' => htmlentities($_GET['id'])));

        While ($row3 = $st3->fetch()) {
            echo '<div class="gf">
                <h3>'.$row3["user"].'</h3>
                <p>'.$row3["com"].'</p>
                </div>';
        }
    ?>
    </div>
    <br><br><br><br>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
</body>
</html>

