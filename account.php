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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sticket Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if(!isset($_SESSION['username'])): ?>
    <?php header('location: login.php'); ?>
    <?php else: ?>
    <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="accountset.php">Profile settings</a></li>
    <li style="float:right"><a href="logout.php">Logout</a></li>
    <li style="float:right"><a href="upload.php">Upload</a></li>
    </ul>
    <br><br><br>
    <?php
    include_once 'connection.php';

    try{
        $sql = "SELECT * FROM gallery WHERE userid = :userid";
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

        $sql = "SELECT * FROM gallery WHERE userid = :userid ORDER BY id DESC LIMIT $start, $amount";
        $st = $dp->prepare($sql);
        $st->execute(array(':userid' => htmlentities($_SESSION['id'])));
        While ($row = $st->fetch()) {
            echo '<div>
                <a href="com.php?id='.$row['id'].'">
                <img src="gallery/'.$row['imgname'].'">
                </a>
                </div>';
        }
        echo '<hr>';
        echo '<div class="page">';
        for($page = 1; $page <= $numpages; $page++){
            echo "<a href='account.php?page=".$page."'> ".$page." </a>";
        }
        echo '</div>';
    }
    catch(PDOException $err){
    }
        ?>
    <?php endif ?>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
</body>
</html>