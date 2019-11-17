<?php
include_once 'session.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Home</title>
</head>
<body>
    <a href="">Home</a>
    <?php if(!isset($_SESSION['username'])): ?>
    <a href="login.php">Login</a>
    <a href="reg.php">Sign Up</a>
    <?php else: ?>
    <a href="account.php">Profile</a>
    <a href="upload.php">Upload</a>
    <a href="logout.php">Logout</a>
    <?php endif ?>
    <div class="gg">
    <?php
    include_once 'connection.php';

    try{
        $sql = "SELECT * FROM gallery";
        $st = $dp->prepare($sql);
        $st->execute();
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

        $sql = "SELECT * FROM gallery ORDER BY id DESC LIMIT $start, $amount";
        $st = $dp->prepare($sql);
        $st->execute();
        While ($row = $st->fetch()) {
            echo '<a href="com.php?id='.$row['id'].'">
                <img src="gallery/'.$row['imgname'].'">
                </a>';
        }
        echo '<hr>';
        for($page = 1; $page <= $numpages; $page++){
            echo "<a href='index.php?page=".$page."'> ".$page." </a>";
        }
    }
    catch(PDOException $err){
    }
        ?>
    </div>
</body>
</html>