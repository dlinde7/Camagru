<?php
include_once 'session.php';
include_once 'connection.php';

if (isset($_POST['cpwd'])) {
    $cpwd = $_POST['cpwd'];
    $user = $_POST['new_user'];
    $email = $_POST['email'];
    $pwd = $_POST['new_pwd'];
    $pwd2 = $_POST['repwd'];
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

$dp = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru Home</title>
</head>
<body>

    <?php if(!isset($_SESSION['username'])): ?>
    <p>Must be Loged in to acces this page</p>
    <?php else: ?>
    <h4><?php echo $_SESSION['username']?></h4>
​
    <?php
    if (isset($result2)) {
        echo $result2;
    }
    if (isset($result)) {
        foreach ($result as $value) {
            echo "$value <br>";
        }
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
            
            <tr><td></td><td><input style='float:right'type="submit" name="ResetBtn" value="Update profile"></td></tr>
        </table>
        </form>
    <p><a href="account.php">Back</a><br><a href="logout.php">Log out</a></p>
    <?php endif ?>
</body>
</html>