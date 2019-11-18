<?php
include_once 'session.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Home</title>
</head>
<body>

    <?php if(!isset($_SESSION['username'])): ?>
    <?php header('location: login.php'); ?>
    <?php else: ?>
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
            <tr><td><input type="submit" name="del" value="Delete"></td><td><input type="submit" name="post" value="Post"></td>
            <?php
            if (!empty($_GET['id3'])) {
                echo '<td><input type="submit" name="undo" value="undo"></td>';
            }
            ?></tr>
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
        if (isset($_POST['undo'])) {

            try {
                $sql = "DELETE FROM upload WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':id' => htmlentities($_GET['id'])));
                header('location: upload.php?id='.htmlentities($_GET['id3']).'');
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        if (!empty($_GET['id2'])) {
            
            try {
                $sql = "SELECT imgname FROM upload WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':id' => htmlentities($_GET['id2'])));
                $row = $st->fetch();
                $name = $row['imgname'];
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            if (strstr($name, 'sticker') && $_GET['id2'] != $_GET['id3']) {
                unlink('upload/'.$row['imgname']);

                try {
                    $sql = "DELETE FROM upload WHERE id = :id";
                    $st = $dp->prepare($sql);
                    $st->execute(array(':id' => htmlentities($_GET['id2'])));
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
        }
        ?>
    </div>
    <div>
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
            if ($row['id'] != $_GET['id']) {
                echo '<a href="upload.php?id='.$row['id'].'">
                <img src="upload/'.$row['imgname'].'" width="50">
                </a>';
            }
        }
        echo '<hr>';
        for($page = 1; $page <= $numpages; $page++){
            echo "<a href='upload.php?page=".$page."&id=".htmlentities($_GET['id'])."&id2=".htmlentities($_GET['id2'])."&id3=".htmlentities($_GET['id3'])."'> ".$page." </a>";
        }
    }
    catch(PDOException $err){
    }
        ?>
    </div>
    <?php
    echo '<a href="add.php?id=cloud.png&id2='.htmlentities($_GET['id']).'&id3='.htmlentities($_GET['id3']).'" ><img src="sticker/cloud.png" width="100"></a>
        <a href="add.php?id=clouds-transparent.png&id2='.htmlentities($_GET['id']).'&id3='.htmlentities($_GET['id3']).'"><img src="sticker/clouds-transparent.png" width="100"></a>
        <a href="add.php?id=beard.png&id2='.htmlentities($_GET['id']).'&id3='.htmlentities($_GET['id3']).'"><img src="sticker/beard.png" width="100"></a>
        <a href="add.php?id=sunglasses.png&id2='.htmlentities($_GET['id']).'&id3='.htmlentities($_GET['id3']).'"><img src="sticker/sunglasses.png" width="100"></a>';
    ?>
    <?php endif ?>
</body>
</html>