<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");
require_once("../../core/is_user.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $loaithe = xss($_POST['loaithe']);
    $menhgia = xss($_POST['menhgia']);
    $seri = xss($_POST['seri']);
    $pin = xss($_POST['pin']);
    if ($TN->site('status_card') != '1') {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Hệ thống đã bảo trì nạp thẻ'
        ]));
    }
    if (empty($_POST['token'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (check_xss($loaithe)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'LỖI!'
        ]));
        }
        if (check_xss($menhgia)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'LỖI!'
        ]));
        }
        if (check_xss($seri)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'LỖI!'
        ]));
        }
        if (check_xss($pin)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'LỖI!'
        ]));
        }
    if (empty($loaithe)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Vui lòng chọn loại thẻ'
        ]));
    }
    if (empty($menhgia)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Vui lòng chọn mệnh giá'
        ]));
    }
    if (empty($seri)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Vui lòng nhập seri thẻ'
        ]));
    }
    if (empty($pin)) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Vui lòng nhập mã thẻ'
        ]));
    }
    if ($TN->site('status_captcha') == 1) {
    $captcha_response = $_POST['captcha_response'] ?? '';
    if (empty($captcha_response)) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng xác minh CAPTCHA.']));
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
        die(json_encode(['status' => 'error', 'msg' => 'CAPTCHA không hợp lệ. Vui lòng thử lại.']));
    }
    }
    if (strlen($seri) < 5 || strlen($pin) < 5) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Mã thẻ hoặc seri không đúng định dạng!'
        ]));
    }

    $request_id = rand(100000000, 999999999);

    $POSTGET = array();

    $POSTGET['request_id'] = $request_id;

    $POSTGET['code'] = $pin;

    $POSTGET['partner_id'] = $TN->site('partner_id');

    $POSTGET['serial'] = $seri;

    $POSTGET['telco'] = $loaithe;

    $POSTGET['command'] = "charging";

    ksort($POSTGET);

    $mysign = md5($TN->site('partner_key') . $pin . $seri);

    $POSTGET['amount'] = $menhgia;

    $POSTGET['sign'] = $mysign;

    $data = http_build_query($POSTGET);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://doithe1s.vn/chargingws/v2');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $SERVER_NAME = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    curl_setopt($ch, CURLOPT_REFERER, $SERVER_NAME);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    curl_close($ch);

    $return = json_decode($result);
    
    if (isset($return->status)) {
        if ($return->status == 99) {
            $TN->insert("cards", array(
                'code' => $request_id,
                'seri' => $seri,
                'pin'  => $pin,
                'loaithe' => $loaithe,
                'menhgia' => $menhgia,
                'thucnhan' => '0',
                'username' => $getUser['username'],
                'status' => 'xuly',
                'note' => '',
                'createdate' => date('Y-m-d H:i:s', time())
            ));
            die(json_encode([
                'status'    => 'success',
                'msg'       => 'Gửi thẻ thành công, vui lòng đợi kết quả'
            ]));
        } else{
            die(json_encode([
                'status'    => 'error',
                'msg'       => ''.$return->message.''
            ]));
        }
    } else {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'Lỗi Hệ Thống Nạp Thẻ Vui Lòng Báo Admin Để Xử Lý'
        ]));
    }
}
