<?php

define("IN_SITE", true);
require_once "../../core/DB.php";
require_once "../../core/helpers.php";
require_once '../../core/class/class.smtp.php';
require_once '../../core/class/PHPMailerAutoload.php';
require_once '../../core/class/class.phpmailer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'forgotPassword') {
        if (check_xss($_POST['email'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'KhangOri Chào Bạn!'
            ]));
        }
        if (empty($_POST['email'])) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập email']));
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
        if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `email` = '" . xss($_POST['email']) . "'")) {
            die(json_encode(['status' => 'error', 'msg' => 'Địa chỉ email không tồn tại trong hệ thống']));
        }
        $otp = random('0123456789', '6');
        $TN->update("users", array(
            'otp' => $otp,
        ), " `id` = '" . $getUser['id'] . "' ");
        $guitoi = $getUser['email'];
        $subject = 'KHÔI PHỤC MẬT KHẨU';
        $bcc = "";
        $hoten = 'Client';
        $noi_dung = '<h3>Có ai đó vừa yêu cầu gửi otp khôi phục mật khẩu bằng Email này, nếu là bạn thì otp bên dưới dùng để xác thực khôi phục</h3>
        <table>
        <tbody>
        <tr>
        <td style="font-size:20px;">OTP:</td>
        <td><b style="color:blue;font-size:30px;">' . $otp . '</b></td>
        </tr>
        </tbody>
        </table>';
        sendCSM($guitoi, $hoten, $subject, $noi_dung, $bcc);
        die(json_encode(['status' => 'success', 'msg' => 'Chúng tôi đã gửi 1 mã OTP khôi phục đến email của bạn']));
    }
    if (isset($_POST['action']) && $_POST['action'] == 'resetPassword') {
        $otp = xss($_POST['otp']);
        $repassword = xss($_POST['repassword']);
        $password = xss($_POST['password']);
        if (check_xss($otp)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'KhangOri Chào Bạn!'
        ]));
    }
    if (check_xss($password)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'KhangOri Chào Bạn!'
        ]));
    }
    if (check_xss($repassword)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'KhangOri Chào Bạn!'
        ]));
    }
        if (empty($otp)) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập otp']));
        }
        if (empty($password)) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập mật khẩu mới']));
        }
        if (empty($repassword)) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng xác minh lại mật khẩu']));
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
        $row = $TN->get_row(" SELECT * FROM `users` WHERE `otp` = '$otp' ");
        if (!$row) {
            die(json_encode(['status' => 'error', 'msg' => 'OTP không tồn tại trong hệ thống']));
        }
        if ($password != $repassword) {
            die(json_encode(['status' => 'error', 'msg' => 'Nhập lại mật khẩu không đúng']));
        }
        if (strlen($password) < 6) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập mật khẩu có ích nhất 6 ký tự']));
        }
        $TN->update("users", [
            'otp' => null,
            'password' => sha1($password),
        ], " `id` = '" . $row['id'] . "' ");
        $TN->insert("logs", [
            'user_id' => $row['id'],
            'ip' => myip(),
            'device' => $_SERVER['HTTP_USER_AGENT'],
            'create_date' => time(),
            'action' => 'Khôi phục mật khẩu thành công',
        ]);
        die(json_encode(['status' => 'success', 'msg' => 'Đã khôi phục mật khẩu thành công']));
    }
} else {
    die('The Request Not Found');
}
