<?php
if ($email != null) {
    $hold = $email;
    $hold = filter_var($hold, FILTER_SANITIZE_EMAIL);
    if (filter_var($hold, FILTER_VALIDATE_EMAIL) === false) {
        $c = 1;
    }
    else {
        $c = 0;
    }
}

$er = 0;
$result = array();
if ($pwd != $pwd2) {
    array_push($result, "Passwords do not match");
    $er = 1;
}
if (strlen($pwd) < 8) {
    array_push($result, "Passwords to short between 8 -16");
    $er = 1;
}
if (strlen($pwd) > 16) {
    array_push($result, "Passwords to long between 8-16");
    $er = 1;
}
if (strlen($user) < 4) {
    array_push($result, "Username to short");
    $er = 1;
}
if ($c == 1) {
    array_push($result, "Not a valid email");
    $er = 1;
}

if ($er != 1) {
    $tst->execute(array(':username' => $user, ':email' => $email, ':password' => $hpwd));
}

?>