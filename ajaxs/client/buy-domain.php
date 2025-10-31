<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token  = xss($_POST['token'] ?? '');
    $id     = xss($_POST['id'] ?? '');
    $domain = xss($_POST['domain'] ?? '');
    $coupon = xss($_POST['coupon'] ?? '');
    $years  = intval($_POST['selectedYears'] ?? 1);
    $ns1 = xss($_POST['ns1'] ?? '');
    $ns2 = xss($_POST['ns2'] ?? '');

    if (empty($token)) die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
    if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `banned` = 0"))
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));

    if (empty($id) || empty($domain) || $years < 1)
        die(json_encode(['status' => 'error', 'msg' => 'Thiếu thông tin thanh toán']));

    if (empty($ns1) || empty($ns2)) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập đầy đủ Nameserver (NS1 và NS2)']));
    }

    if (!$row = $TN->get_row("SELECT * FROM `tbl_list_domain` WHERE `id` = '$id'"))
        die(json_encode(['status' => 'error', 'msg' => 'Tên miền không tồn tại']));

    $base_price     = $row['price'] * $years;
    $discount_price = $base_price;

    if (!empty($coupon)) {
        if ($cou = $TN->get_row("SELECT * FROM `coupons` WHERE `id` = '$coupon' AND `status` = 1")) {
            $discount_price -= $base_price * $cou['discount_percent'] / 100;
        }
    }

    if ($getUser['money'] < $discount_price)
        die(json_encode(['status' => 'error', 'msg' => 'Số dư không đủ, cần ' . format_cash($discount_price) . 'đ']));

    if (!RemoveCredits($getUser['id'], $discount_price, "Thanh toán tên miền: $domain ($years năm)"))
        die(json_encode(['status' => 'error', 'msg' => 'Không thể trừ tiền. Vui lòng thử lại']));

    $TN->insert("tbl_his_domain", [
        'user_id'      => $getUser['id'],
        'domain'       => $domain,
        'nameserver1'       => $ns1,
        'nameserver2'       => $ns2,
        'years'        => $years,
        'price'        => $discount_price,
        'status'       => 'pending',
        'create_time'  => date('Y-m-d H:i:s'),
        'end_time'  => date('Y-m-d H:i:s', strtotime("+$years years"))
    ]);

    $TN->insert("logs", [
        'user_id'     => $getUser['id'],
        'ip'          => myip(),
        'device'      => $_SERVER['HTTP_USER_AGENT'],
        'create_date' => time(),
        'action'      => "Thanh toán tên miền: $domain ($years năm)"
    ]);

sendTele("
<b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>Tài Khoản:</b> <code>" . htmlspecialchars($getUser['username']) . "</code>\n
<b>Hành Động:</b> Mua Tên Miền <code>{$domain}</code>\n
<b>Thời Hạn:</b> <code>{$years} năm</code>\n
<b>Giá:</b> <code>" . number_format($discount_price) . "đ</code>\n
<b>Nameserver:</b> <code>{$ns1}</code> / <code>{$ns2}</code>\n
<b>IP:</b> <code>" . myip() . "</code>");
         

    echo json_encode(['status' => 'success', 'msg' => 'Thanh toán thành công!']);
}
?>
