<?php

define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");
require_once('../../core/class/class.smtp.php');
require_once('../../core/class/PHPMailerAutoload.php');
require_once('../../core/class/class.phpmailer.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'ChangePassword') {
        if (empty($_POST['token'])) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned`=0")) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
        }
        if (check_xss($_POST['password'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'KhangOri Chào Bạn!'
        ]));
        }
        if (check_xss($_POST['newpassword'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'KhangOri Chào Bạn!'
        ]));
        }
        if (check_xss($_POST['renewpassword'])) {
        die(json_encode([
            'status'    => 'error',
            'msg'       => 'KhangOri Chào Bạn!'
        ]));
        }
         if (empty($_POST['password'])) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập mật khẩu hiện tại']));
        }
        if (empty($_POST['newpassword'])) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập mật khẩu mới']));
        }
        if (empty($_POST['renewpassword'])) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng xác thực mật khẩu']));
        }
        if ($_POST['renewpassword'] != $_POST['newpassword']) {
            die(json_encode(['status' => 'error', 'msg' => 'Nhập lại mật khẩu không đúng']));
        }
        $password = xss($_POST['password']);
        $checkPass = $TN->get_row("SELECT * FROM `users` WHERE `password` = '" . sha1($password) . "' AND `token` = '".xss($_POST['token'])."' ");
        if (!$checkPass) {
            die(json_encode(['status' => 'error', 'msg' => 'Mật khẩu cũ không chính xác']));
        }
        $isUpdate = $TN->update("users", [
            'password'  => isset($_POST['newpassword']) ? sha1($_POST['newpassword']) : null,
            'token'     => md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time())
        ], " `token` = '" . xss($_POST['token']) . "' ");
        if ($isUpdate) {
            $TN->insert("logs", [
                'user_id'       => $getUser['id'],
                'ip'            => myip(),
                'device'        => $_SERVER['HTTP_USER_AGENT'],
                'create_date'    => time(),
                'action'        => 'Thay đổi mật khẩu'
            ]);
            die(json_encode(['status' => 'success', 'msg' => 'Đổi mật khẩu thành công']));
        }
        die(json_encode(['status' => 'error', 'msg' => 'Đổi mật khẩu thất bại']));
    }
} else {
    die('The Request Not Found');
}
