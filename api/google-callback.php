<?php
define("IN_SITE", true);
require_once("../core/DB.php");
require_once("../core/helpers.php");
require_once __DIR__ . '/../vendor/autoload.php';
$config = require_once("../core/google.php");

$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_uri']);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $_SESSION['access_token'] = $token['access_token'];

    $oauth2 = new Google_Service_Oauth2($client);
    $userinfo = $oauth2->userinfo->get();

    $_SESSION['user_id'] = $userinfo->id;
    $_SESSION['email'] = $userinfo->email;
    $_SESSION['name'] = $userinfo->name;

    $google_id = $userinfo->id;
    $email = $userinfo->email;
    $name = $userinfo->name;
    $token = json_encode($token);
    $tokenData = json_decode($token, true);
    $accessToken = $tokenData['access_token'];
    
    $tokens = md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time());

   $result = $TN->query("SELECT id FROM users WHERE username = $google_id");
   
    if ($result->num_rows > 0) {

      $stmt = $TN->update("users", array(
                'email'    => $email,
                'name'         => $name,
                'time_session' => time(),
                'type'      => 'Google',
                'token'  => $tokens
            ), " `username` = '".$google_id."' ");
    } else {
        $stmt = $TN->insert("users", [
        'token'         => $tokens,
        'username'      => $google_id,
        'name'      => $name,
        'email'         => $email,
        'password'      => sha1($google_id),
        'ip'            => myip(),
        'type'         => 'Google',
        'device'        => $_SERVER['HTTP_USER_AGENT'],
        'create_date'   => time(),
        'update_date'   => time(),
        'time_session'  => time()
    ]);
    
    }
    if ($stmt) {
        $TN->insert("logs", [
            'user_id'       => $TN->get_row("SELECT * FROM `users` WHERE `token` = '$tokens' ")['id'],
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'    => time(),
            'action'        => 'Đăng nhập vào hệ thống bằng phương thức Google'
         ]);

    }
   setcookie("token", $tokens, time() + $TN->site('session_login'), "/");
        $_SESSION['login'] = $tokens;
    header('Location: /client/home');
    exit();
} else {
    echo 'Mã xác thực không khả dụng';
}
?>
