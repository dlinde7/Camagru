<?php
include_once 'session.php';
include_once 'connection.php';

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
    <title>Camagru Home</title>
</head>
<body>

    <a href="index.php">Home</a>
    <?php if(!isset($_SESSION['username'])): ?>
    <a href="login.php">Login</a>
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="account.php">Profile</a>
    <a href="logout.php">Logout</a>
    <?php endif ?>
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
                <a href="thgallery.php?userid='.$row['userid'].'">Their Gallery</a><br>';
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
            <td><textarea name="com" placeholder="Enter comment..." required></textarea></td></tr>
            <tr><td></td><td><input type="submit" value="Post"></td></tr>
        </table>
        <?php endif ?>
        <?php
        $sql3 = "SELECT * FROM com WHERE id = :id ORDER BY up_date DESC";
        $st3 = $dp->prepare($sql3);
        $st3->execute(array(':id' => htmlentities($_GET['id'])));

        While ($row3 = $st3->fetch()) {
            echo '<h3>'.$row3["user"].'</h3>
                <p>'.$row3["com"].'</p>';
        }
    ?>
    </div>
</body>
</html>

