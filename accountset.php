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
if (isset($_POST['cpwd'])) {
    $cpwd = htmlentities($_POST['cpwd']);
    $user = htmlentities($_POST['new_user']);
    $email = htmlentities($_POST['email']);
    $pwd = htmlentities($_POST['new_pwd']);
    $pwd2 = htmlentities($_POST['repwd']);
    if(isset($_POST['PrefBtn'])){
        $pref = $_POST['PrefBtn'];
    }

    $cuser = $_SESSION['username'];

    try{   
        $sql = "SELECT * FROM users WHERE username = :user";
        $st = $dp->prepare($sql);
        $st->execute(array(':user' => $cuser));

        if($row = $st->fetch()){
            $h_pwd = $row['password']; 
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    if (password_verify($cpwd, $h_pwd)) {
        require 'acerror.php';
    }
    else {
        $result2 = "Incorrect Current Password!";
    }

}

if (isset($_POST['del'])) {
    $cuser = $_SESSION['username'];

    try{   
        $sql = "SELECT * FROM users WHERE username = :user";
        $st = $dp->prepare($sql);
        $st->execute(array(':user' => $cuser));

        if($row = $st->fetch()){
            $h_pwd = $row['password']; 
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    if (password_verify($cpwd, $h_pwd)) {
        try {
            $sql = "SELECT * FROM gallery WHERE userid = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));

            $test = array();
            While ($row = $st->fetch()) {
                unlink('gallery/'.$row['imgname']);
                unlink('upload/'.$row['imgname']);
                array_push($test, $row['id']);
            }
            
            $n = 0;
            while ($test[$n]) {
                $sql = "DELETE FROM com WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':id' => htmlentities($test[$n])));
        
                $sql = "DELETE FROM `like` WHERE id = :id";
                $st = $dp->prepare($sql);
                $st->execute(array(':id' => htmlentities($test[$n])));

                $n++;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        try {
            $sql = "DELETE FROM gallery WHERE userid = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));

            $sql = "DELETE FROM upload WHERE userid = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));

            $sql = "DELETE FROM users WHERE id = :id";
            $st = $dp->prepare($sql);
            $st->execute(array(':id' => htmlentities($_SESSION['id'])));
            header('location: logout.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    else {
        $result2 = "Incorrect Current Password!";
    }
}

$dp = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sticket Settings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php if(!isset($_SESSION['username'])): ?>
    <?php header('location: login.php'); ?>
    <?php else: ?>
    <ul>
    <li><a href="index.php">Home</a></li>
    <li><a class="active" href="accountset.php">Profile settings</a></li>
    <li style="float:right"><a href="logout.php">Logout</a></li>
    <li style="float:right"><a href="upload.php">Upload</a></li>
    </ul>
    <br><br>
    <h1><?php echo $_SESSION['username']?></h1>
​
    <?php
    if (isset($result2)) {
        echo $result2.'<br>';
    }
    if (isset($result)) {
        foreach ($result as $value) {
            echo "$value <br>";
        }
        echo '<br>';
    }
    ?>
    <form action="" method="post">
        <table>
            <tr><td>Username:</td> <td><input type="text" value="" name="new_user" placeholder="New Username" ></td></tr>
            
            <tr><td>Email:</td> <td><input type="email" value="" name="email" placeholder="New Email" ></td></tr>
            <tr><td>Password:</td> <td><input type="password" value="" name="new_pwd" placeholder="New Password" ></td></tr>
            <tr><td>Re-Password:</td> <td><input type="password" value="" name="repwd" placeholder="Re-Password" ></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td>Notification preference:</td><td><?php echo $_SESSION['preference']?></td></tr>
            <tr><td>ON</td><td><input style='float:right'type="radio" name="PrefBtn" value="ON"></td></tr>
            <tr><td>OFF</td><td><input style='float:right'type="radio" name="PrefBtn" value="OFF"></td></tr>
            <tr><td> Current Password:</td> <td><input type="password" value="" name="cpwd" placeholder="Current Password" required></td></tr>
            
            <tr><td><input style='float:right' type="submit" name="del" value="Delete Profile"></td><td><input style='float:right' type="submit" name="ResetBtn" value="Update profile"></td></tr>
        </table>
        </form>
    <?php endif ?>
    <div class="footer">
        <hr>
        <footer>&copy; Copyright 2019 dlinde</footer>
        <br>
    </div>
</body>
</html>