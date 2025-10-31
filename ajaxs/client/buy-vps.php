<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = xss($_POST['action'] ?? '');

    // ✅ Trả về đơn giá cấu hình (cho frontend hiển thị real-time)
    if ($action === 'get-price') {
        $cpuPrice  = 30000;  // 30k / core
        $ramPrice  = 20000;  // 20k / GB
        $diskPrice = 10000;  // 10k / 10GB

        echo json_encode([
            'status'     => 'success',
            'cpu_price'  => $cpuPrice,
            'ram_price'  => $ramPrice,
            'disk_price' => $diskPrice,
        ]);
        exit;
    }

    // ✅ Xử lý thanh toán mua VPS
    $csrf_token  = xss($_POST['csrf_token'] ?? '');
    $vpsId       = xss($_POST['vpsId'] ?? '');
    $billing     = xss($_POST['billingcycle'] ?? 'monthly');
    $cpu         = intval($_POST['cpu'] ?? 1);
    $ram         = intval($_POST['ram'] ?? 1);
    $disk        = intval($_POST['disk'] ?? 1);
    $coupon      = xss($_POST['coupon'] ?? '');
    $os          = xss($_POST['os'] ?? '');

    if (empty($csrf_token)) die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập!']));
    if (!$user = $TN->get_row("SELECT * FROM `users` WHERE `token` = '$csrf_token' AND `banned` = 0"))
        die(json_encode(['status' => 'error', 'msg' => 'Token không hợp lệ!']));
    if (!$vps = $TN->get_row("SELECT * FROM `tbl_list_vps` WHERE `id` = '$vpsId'"))
        die(json_encode(['status' => 'error', 'msg' => 'Gói VPS không tồn tại!']));

    // Giá cấu hình đơn vị
    $cpuPrice  = 30000;
    $ramPrice  = 20000;
    $diskPrice = 10000;

    // Tính tổng giá cấu hình
    $cpuTotal  = $cpu * $cpuPrice;
    $ramTotal  = $ram * $ramPrice;
    $diskTotal = intval($disk / 10) * $diskPrice;
    $basePrice = $vps['price'];

    $subTotal = $basePrice + $cpuTotal + $ramTotal + $diskTotal;

    // Chu kỳ thanh toán
    $billingFactor = ($billing == 'monthly') ? 1 : (($billing == 'quarterly') ? 3 : 12);
    $total = $subTotal * $billingFactor;

    // Áp dụng mã giảm giá nếu có
    $discount = 0;
    if (!empty($coupon)) {
        if ($cou = $TN->get_row("SELECT * FROM `coupons` WHERE `id` = '$coupon' AND `status` = 1")) {
            $discount = $total * $cou['discount_percent'] / 100;
            $total -= $discount;
        }
    }

    if ($user['money'] < $total)
        die(json_encode(['status' => 'error', 'msg' => 'Số dư không đủ: cần ' . format_cash($total) . 'đ']));

    // Trừ tiền
    if (!RemoveCredits($user['id'], $total, "Thanh toán VPS ({$vps['name']}) - $billing"))
        die(json_encode(['status' => 'error', 'msg' => 'Lỗi khi trừ tiền. Vui lòng thử lại.']));

    // Ghi lịch sử giao dịch
    $today = date('Y-m-d');
    $end = date('Y-m-d', strtotime("+$billingFactor months"));
    $vpsName = $vps['name'] . " - $cpu CPU / $ram GB RAM / $disk GB Disk";

    $TN->insert("tbl_his_vps", [
        'user_id'     => $user['id'],
        'namevps'     => $vpsName,
        'price'       => $total,
        'cpu'         => $cpu,
        'ram'         => $ram,
        'disk'        => $disk,
        'os'          => $os,
        'create_date' => $today,
        'end_date'    => $end,
        'status'      => 'pending'
    ]);

    // Log
    $TN->insert("logs", [
        'user_id'     => $user['id'],
        'ip'          => myip(),
        'device'      => $_SERVER['HTTP_USER_AGENT'],
        'create_date' => time(),
        'action'      => "Thanh toán VPS ($vpsName)"
    ]);

sendTele("
<b>" . $TN->site('domain') . " - THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>💻 VPS Mới Được Mua</b>\n
👤 <b>Tài Khoản:</b> <code>{$user['username']}</code>\n
📦 <b>Gói:</b> <code>{$vpsName}</code>\n
💰 <b>Giá:</b> <code>" . format_cash($total) . "đ</code>\n
⏳ <b>Chu kỳ:</b> <code>$billing</code>\n
🖥 <b>OS:</b> <code>$os</code>\n
🌐 <b>IP:</b> <code>" . myip() . "</code>");
         
    echo json_encode([
        'status' => 'success',
        'msg'    => "Mua VPS thành công!"
    ]);
}
?>
