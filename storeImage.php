<?php
    
    $img = $_POST['image'];
    $folderPath = "upload/";
  
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.png';
  
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);

    include_once 'session.php';
    include_once 'connection.php';

    $sql = "INSERT INTO upload (imgname, userid, up_date)
    VALUES (:imgname, :userid, now())";
    $st = $dp->prepare($sql);
    $st->execute(array(':imgname' => $fileName, ':userid' => $_SESSION['id']));
    header('location: upload.php');
?>