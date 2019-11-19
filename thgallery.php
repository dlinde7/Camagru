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
    <title>Camagru Home</title>
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
    <?php
    include_once 'connection.php';

    $sql = "SELECT * FROM users WHERE id = :id";
    $st = $dp->prepare($sql);
    $st->execute(array(':id' => htmlentities($_GET['userid'])));
    $row = $st->fetch();
    echo '<div class="gg2"><h1>'.$row['username'].'</h1></div>';
    
    ?>
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
            echo '<div>
                <a href="com.php?id='.$row['id'].'">
                <img src="gallery/'.$row['imgname'].'">
                </a></div>';
        }
        echo '<hr>';
        echo '<div class="page">';
        for($page = 1; $page <= $numpages; $page++){
            echo "<a href='thgallery.php?userid=".$_GET['userid']."&page=".$page."'> ".$page." </a>";
        }
        echo '</div>';
    }
    catch(PDOException $err){
    }
        ?>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
</body>
</html>