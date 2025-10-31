<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (check_xss($_POST['username'])) {
        echo json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập Tài khoản!']);
        exit;
    }

    if (check_xss($_POST['email'])) {
        echo json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập Email!']);
        exit;
    }

    if (check_xss($_POST['password'])) {
        echo json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập Mật khẩu!']);
        exit;
    }

    if (empty($_POST['TermsUse'])) {
        echo json_encode(['status' => 'error', 'msg' => 'Vui Lòng Chấp Nhận Điều Khoản Và Chính Sách Bảo Mật']);
        exit;
    }

    if ($TN->site('status_captcha') == 1) {
        $captcha_response = $_POST['captcha_response'] ?? '';
        if (empty($captcha_response)) {
            echo json_encode(['status' => 'error', 'msg' => 'Vui lòng xác minh CAPTCHA.']);
            exit;
        }

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
            echo json_encode(['status' => 'error', 'msg' => 'CAPTCHA không hợp lệ. Vui lòng thử lại.']);
            exit;
        }
    }

    $username = xss($_POST['username']);
    $email = xss($_POST['email']);
    $password = xss($_POST['password']);

    if (!check_username($username)) {
        echo json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập định dạng tài khoản hợp lệ']);
        exit;
    }

    if (!check_email($email)) {
        echo json_encode(['status' => 'error', 'msg' => 'Định dạng Email không đúng']);
        exit;
    }

    if ($TN->num_rows("SELECT * FROM `users` WHERE `username` = '$username' ") > 0) {
        echo json_encode(['status' => 'error', 'msg' => 'Tên đăng nhập đã tồn tại trong hệ thống']);
        exit;
    }

    if ($TN->num_rows("SELECT * FROM `users` WHERE `email` = '$email' ") > 0) {
        echo json_encode(['status' => 'error', 'msg' => 'Địa chỉ email đã tồn tại trong hệ thống']);
        exit;
    }

    if ($TN->num_rows("SELECT * FROM `users` WHERE `ip` = '" . myip() . "' ") >= 3) {
        echo json_encode(['status' => 'error', 'msg' => 'IP của bạn đã đạt giới hạn tạo tài khoản cho phép']);
        exit;
    }

    $token = md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 12) . time());
    $apikey = md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 12) . time());
    $isCreate = $TN->insert("users", [
        'token'         => $token,
        'apikey'         => $apikey,
        'username'      => $username,
        'email'         => $email,
        'type'          => 'Website',
        'password'      => sha1($password),
        'ip'            => myip(),
        'device'        => $_SERVER['HTTP_USER_AGENT'],
        'create_date'   => time(),
        'update_date'   => time(),
        'time_session'  => time()
    ]);

    if ($isCreate) {
        $user_id = $TN->get_row("SELECT id FROM `users` WHERE `token` = '$token' ")['id'];
        $TN->insert("logs", [
            'user_id'       => $user_id,
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'   => time(),
            'action'        => 'Đăng ký tài khoản thành công'
        ]);

        $TN->update("users", [
            'time_session' => time(),
        ], " `id` = '$user_id' ");

        setcookie("token", $token, time() + $TN->site('session_login'), "/");
        $_SESSION['login'] = $token;
        
sendTele("
<b>" . $TN->site('domain') . " THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>Hành Động:</b> <code>Đăng ký tài khoản mới</code>\n
<b>Username:</b> <code>$username</code>\n
<b>Email:</b> <code>$email</code>\n
<b>IP:</b> <code>" . myip() . "</code>");
        
        echo json_encode(['status' => 'success', 'msg' => 'Đăng ký thành công']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Tạo tài khoản thất bại, vui lòng thử lại']);
        exit;
    }
}
