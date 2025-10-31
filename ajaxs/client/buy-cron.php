<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $action = $_POST['action'] ?? null;
    $token = xss($_POST['token'] ?? '');
    $id = (int)($_POST['id'] ?? 0);

    if (in_array($action, ['stop', 'play']) && $token && $id) {
        if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `banned` = '0'")) {
            die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập.']));
        }

        $cron = $TN->get_row("SELECT * FROM `tbl_his_cron` WHERE `id` = '$id' AND `user_id` = '" . $getUser['id'] . "'");
        if (!$cron) {
            die(json_encode(['status' => 'error', 'msg' => 'Cron không tồn tại hoặc không thuộc quyền sở hữu.']));
        }

        if ($action == 'stop') {
            $TN->update("tbl_his_cron", ['status' => 'STOP'], "id = '$id'");
            sendTele("
            <b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
            🔴 <b>ĐÃ DỪNG CRON</b>
            👤 Người dùng: <code>" . htmlspecialchars($getUser['username']) . "</code>
            🌐 URL: <code>" . htmlspecialchars($cron['url']) . "</code>
            🖥 Server: <code>#" . $cron['id_server'] . "</code>
            🕒 Thời gian chạy: <code>" . $cron['second'] . " giây</code>
            ");
            die(json_encode(['status' => '2', 'msg' => 'Đã dừng cron thành công.']));
        } elseif ($action == 'play') {
            $TN->update("tbl_his_cron", ['status' => 'ON'], "id = '$id'");
            sendTele("
            <b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
            🟢 <b>ĐÃ KÍCH HOẠT CRON</b>
            👤 Người dùng: <code>" . htmlspecialchars($getUser['username']) . "</code>
            🌐 URL: <code>" . htmlspecialchars($cron['url']) . "</code>
            🖥 Server: <code>#" . $cron['id_server'] . "</code>
            🕒 Thời gian chạy: <code>" . $cron['second'] . " giây</code>
            ");
            die(json_encode(['status' => '2', 'msg' => 'Đã kích hoạt cron thành công.']));
        }
    }
    
    $token = xss($_POST['token']);
    $url = xss($_POST['url']);
    $time = (int)$_POST['time'];
    $server_id = (int)$_POST['server'];
    $months = (int)$_POST['months'];

    if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `banned` = '0'")) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập.']));
    }

    if (empty($url) || empty($time) || empty($server_id) || empty($months)) {
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng nhập đầy đủ thông tin.']));
    }

    $server = $TN->get_row("SELECT * FROM `server_cron` WHERE `id` = '$server_id' AND `status` = 'ON'");
    if (!$server) {
        die(json_encode(['status' => 'error', 'msg' => 'Máy chủ không tồn tại hoặc đã bị tắt.']));
    }
    
    if ($server['quantity'] <= 0) {
        die(json_encode(['status' => 'error', 'msg' => 'Máy chủ này đã hết slot, vui lòng chọn máy chủ khác.']));
    }

    if ($time < $server['limit_second']) {
        die(json_encode(['status' => 'error', 'msg' => 'Thời gian tối thiểu của máy chủ này là ' . $server['limit_second'] . ' giây.']));
    }

    $price_per_month = (int)$server['price'];
    $total_price = $price_per_month * $months;

    if ($getUser['money'] < $total_price) {
        die(json_encode(['status' => 'error', 'msg' => 'Bạn không đủ tiền (' . format_cash($total_price) . 'đ) để thuê.']));
    }

    $isPay = RemoveCredits($getUser['id'], $total_price, "Thuê Cronjob ($months tháng) trên server #" . $server_id);

    if ($isPay) {
        $now = time();
        $expired_time = strtotime("+$months months", $now);

        $TN->insert("tbl_his_cron", [
            'user_id' => $getUser['id'],
            'id_server' => $server_id,
            'url' => $url,
            'second' => $time,
            'status' => 'ON',
            'created_at' => date('Y-m-d H:i:s', $now),
            'expired_date' => date('Y-m-d H:i:s', $expired_time),
            'expired_timestamp' => $expired_time
        ]);

        $TN->query("UPDATE `server_cron` SET `quantity` = `quantity` - 1 WHERE `id` = '$server_id' AND `quantity` > 0");

        sendTele("
        <b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
        👤 Người dùng: <code>" . htmlspecialchars($getUser['username']) . "</code> Thuê cron mới
        🌐 URL: <code>$url</code>
        🕒 Thời gian chạy: <code>$time giây</code>
        🖥 Server: <code>" . htmlspecialchars($server['name']) . "</code>
        📅 Gói: <code>$months tháng</code>
        💰 Thanh toán: <code>" . number_format($total_price) . "đ</code>
        🕓 Hết hạn: <code>" . date('d-m-Y H:i:s', $expired_time) . "</code>
        ");

        die(json_encode(['status' => 'success', 'msg' => 'Thuê Cronjob thành công!']));
    } else {
        die(json_encode(['status' => 'error', 'msg' => 'Không thể xử lý thanh toán.']));
    }
    
}
?>
