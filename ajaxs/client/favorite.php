<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = xss($_POST['action'] ?? '');
    $productId = xss($_POST['product_id'] ?? '');
    $csrf_token = xss($_POST['csrf_token'] ?? '');

    if (empty($csrf_token)) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập!']));
    }
    if (!$user = $TN->get_row("SELECT * FROM `users` WHERE `token` = '$csrf_token' AND `banned` = 0")) {
        die(json_encode(['status' => 'error', 'msg' => 'Token không hợp lệ!']));
    }

    if ($action === 'add') {
        $favoriteCheck = $TN->get_row("SELECT * FROM `favorite` WHERE `user_id` = '{$user['id']}' AND `product_id` = '$productId'");
        if ($favoriteCheck) {
            die(json_encode(['status' => 'error', 'msg' => 'Sản phẩm đã có trong danh sách yêu thích!']));
        }

        $TN->insert("favorite", [
            'user_id' => $user['id'],
            'product_id' => $productId
        ]);

        echo json_encode(['status' => 'added', 'msg' => 'Sản phẩm đã được thêm vào danh sách yêu thích.']);
    } elseif ($action === 'remove') {
        $TN->query("DELETE FROM `favorite` WHERE `user_id` = '{$user['id']}' AND `product_id` = '$productId'");

        echo json_encode(['status' => 'removed', 'msg' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích.']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Thao tác không hợp lệ!']);
    }
}
?>
