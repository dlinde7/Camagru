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
    <?php endif ?>
    <?php
    include_once 'connection.php';

    $sql = "SELECT * FROM users WHERE id = :id";
    $st = $dp->prepare($sql);
    $st->execute(array(':id' => htmlentities($_GET['userid'])));
    $row = $st->fetch();
    echo '<h1>'.$row['username'].'</h1>';
    
    ?>
    <div class="gg">
    <?php
    include_once 'connection.php';

    try{
        $sql = "SELECT * FROM gallery WHERE userid = :userid";
        $st = $dp->prepare($sql);
        $st->execute(array(':userid' => htmlentities($_GET['userid'])));
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
        $st->execute(array(':userid' => htmlentities($_GET['userid'])));
        While ($row = $st->fetch()) {
            echo '<a href="com.php?id='.$row['id'].'">
                <img src="gallery/'.$row['imgname'].'">
                </a>';
        }
        echo '<hr>';
        for($page = 1; $page <= $numpages; $page++){
            echo "<a href='thgallery.php?userid=".$_GET['userid']."&page=".$page."'> ".$page." </a>";
        }
    }
    catch(PDOException $err){
    }
        ?>
    </div>
</body>
</html>