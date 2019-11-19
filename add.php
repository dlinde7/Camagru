<?php
 include_once 'session.php';
include_once 'connection.php';
if (!empty($_GET['id'])) {
    if (empty($_GET['id3'])) {
        $orgin = htmlentities($_GET['id2']);
    }
    else {
        $orgin = htmlentities($_GET['id3']);
    }

    function super_impose($src,$dest,$si)
    {
    $image_1 = imagecreatefromstring(file_get_contents($src));
    $stamp = imagecreatefrompng($si);
    list($width, $height) = getimagesize($src);
    list($width_small, $height_small) = getimagesize($si);
    $nwidth = 400;
    imagecopyresampled($image_1, $image_1, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);
    $marge_right = ($width/2)-($width_small/2);
    if ($_GET['id'] === "sunglasses.png" && $height > 200) {
        $marge_bottom = ($nheight/2)-($height_small);
    }
    else {
        $marge_bottom = ($nheight);
    }
    imagealphablending($image_1, true);
    imagesavealpha($image_1, true);
    imagecopy($image_1, $stamp,  imagesx($image_1) - imagesx($stamp) - $marge_right, imagesy($image_1) - $sy = imagesy($stamp) - $marge_bottom, 0, 0, $width_small, $height_small);
    imagejpeg($image_1, $dest);
    }

    try {
        $sql = "SELECT * FROM  upload WHERE id = :id";
        $st = $dp->prepare($sql);
        $st->execute(array(':id' => htmlentities($_GET['id2'])));
        $row = $st->fetch();
        $og = 'upload/'.$row['imgname'];
        $fi = 'sticker/'.htmlentities($_GET['id']);
    } catch (PDOException $e) {
        $result = $e->getMessage();
    }

    try {
        $name = uniqid('', true ).".sticker".".png";
        $nm = 'upload/'.$name;
        super_impose($og, $nm, $fi);

        $sql = "INSERT INTO upload (imgname, userid, up_date)
        VALUES (:imgname, :userid, now())";
        $st = $dp->prepare($sql);
        $st->execute(array(':imgname' => $name, ':userid' => $_SESSION['id']));
    } catch (PDOException $e) {
        $result = $e->getMessage();
    }

    try {
        $sql = "SELECT id FROM  upload WHERE imgname = :imgname";
        $st = $dp->prepare($sql);
        $st->execute(array(':imgname' => $name));
        $row = $st->fetch();
        header('location: upload.php?id='.$row['id'].'&id2='.$_GET['id2'].'&id3='.$orgin.'');
    } catch (PDOException $e) {
        $result = $e->getMessage();
    }
}
else {
    header('location: index.php');
}
 ?>