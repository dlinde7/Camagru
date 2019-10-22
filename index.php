<?php 
  session_start(); 
?>
<html>
<head>
    <title>Camagru</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="navbar">
    <a class="fl" href="">Home</a>
    <a class="fr" href="#login">Login</a>
    <a class="fr" href="#signup">Sign up</a>
    <a class="fl" href="set.php">Settings</a>
</div>
<?php require "register.php"; ?>
<?php require "logup.php"; ?>
</body>
</html>