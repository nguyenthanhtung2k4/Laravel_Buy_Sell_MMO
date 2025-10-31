<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (check_xss($_POST['username'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Username không được để trống!'
        ]));
    }
    if (check_xss($_POST['password'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Mật khẩu không được để trống!'
        ]));
    }
    $username = xss($_POST['username']);
    $password = xss($_POST['password']);
    if (check_username($username) != true) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập định dạng tài khoản hợp lệ']));
    }
    if ($TN->site('status_captcha') == 1) {
    // Lấy token CAPTCHA từ client
    $captcha_response = $_POST['captcha_response'] ?? '';
    if (empty($captcha_response)) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng xác minh CAPTCHA.']));
    }

    // Xác minh CAPTCHA với Cloudflare
    $secret_key = $TN->site('secret_key');
    $verify_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $verify_data = [
        'secret' => $secret_key,
        'response' => $captcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($verify_data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($verify_url, false, $context);
    $result = json_decode($response, true);

    if (!$result['success']) {
        die(json_encode(['status' => 'error', 'msg' => 'CAPTCHA không hợp lệ. Vui lòng thử lại.']));
    }
    }
    $getUser = $TN->get_row("SELECT * FROM `users` WHERE `username` = '$username' ");
    if (!$getUser) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Thông tin đăng nhập không chính xác'
        ]));
    }
    $Check = $TN->get_row("SELECT * FROM `users` WHERE `username` = '$username' AND `password`='".sha1($password)."' ");
    if (!$Check) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Thông tin đăng nhập không chính xác'
        ]));
    }
    if ($getUser['banned'] == 1) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Tài khoản của bạn đã bị khoá truy cập'
        ]));
    }
    $TN->insert("logs", [
        'user_id'       => $getUser['id'],
        'ip'            => myip(),
        'device'        => $_SERVER['HTTP_USER_AGENT'],
        'create_date'    => time(),
        'action'        => 'Đăng nhập thành công vào hệ thống'
     ]);
    $TN->update("users", [
        'ip' => myip(),
        'time_session' => time(),
        'device' => $_SERVER['HTTP_USER_AGENT']
    ], " `id` = '".$getUser['id']."' ");
    setcookie("token", $getUser['token'], time() + $TN->site('session_login'), "/");
    $_SESSION['login'] = $getUser['token'];
    
sendTele("
<b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>Tài Khoản:</b> <code>$username</code>\n
<b>Email:</b> <code>" . htmlspecialchars($getUser['email']) . "</code>\n
<b>Hành Động:</b> <code>Thành Viên Mới Đăng Nhập</code>\n
<b>IP:</b> <code>" . myip() . "</code>
");
        
    die(json_encode([
        'status' => 'success',
        'msg'    => 'Đăng nhập thành công'
    ]));
}
