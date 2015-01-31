<?php
require_once('../../include/startup.php');

$login = new \Enkeltinnhold\Login();

//debug($_POST);

$user = trim($_POST['email']);
$pass = trim($_POST['password']);

if(is_string($user) && is_string($pass) && mb_strlen($user) && mb_strlen($pass)) {
    if($login->loginUser($user, $pass)) {
        // yay
        require_once('../../include/shutdown.php');
        header('Location: /admin/index.php', true, 200);
        exit;
    }
}
require_once('../../include/shutdown.php');
header('Location: /admin/index.php?#auth_fail', true, 403);
exit;

