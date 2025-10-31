<?php

if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

if (isset($_COOKIE["token"])) {
    $getUser = $TN->get_row(" SELECT * FROM `users` WHERE `token` = '".check_string($_COOKIE['token'])."' ");
    if (!$getUser) {
        header("location: ".BASE_URL('client/logout'));
        exit();
    }
    $_SESSION['login'] = $getUser['token'];
}
if (!isset($_SESSION['login'])) {
    $my_username = false;
    $my_level = NULL;
} else {
    $getUser = $TN->get_row(" SELECT * FROM `users` WHERE `token` = '".check_string($_SESSION['login'])."'  ");
    if (!$getUser) {
        redirect(BASE_URL('client/login'));
    }
    $my_username =True;
    $my_level = $getUser['level'];
    if ($getUser['banned'] != 0) {
    }
    if ($getUser['money'] < 0) {
        $TN->update("users", [
            'banned' => 1
        ], " `id` = '".$getUser['id']."' ");
    }
     $TN->update("users", [
        'time_session'  => time()
    ], " `id` = '".$getUser['id']."' ");
}
function CheckLogin()
{
    global $my_username;
    if($my_username != True)
    {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "'.BASE_URL('client/login').'" }, 0);</script>');
    }
}
function CheckAdmin()
{
    global $my_level;
    if($my_level != '1')
    {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "'.BASE_URL('').'" }, 0);</script>');
    }
}
