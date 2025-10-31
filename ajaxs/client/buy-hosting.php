<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

function RandStrings($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token  = xss($_POST['token'] ?? '');
    $id     = xss($_POST['id'] ?? '');
    $domain = xss($_POST['domain'] ?? '');
    $coupon = xss($_POST['coupon'] ?? '');
    $months = intval($_POST['selectedMonths'] ?? 1);

    if (empty($token)) die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
    if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `banned` = 0"))
        die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));

    if (empty($id) || empty($domain) || $months < 1)
        die(json_encode(['status' => 'error', 'msg' => 'Thiếu thông tin cần thiết']));

    if (!$row = $TN->get_row("SELECT * FROM `tbl_list_hosting` WHERE `id` = '$id'"))
        die(json_encode(['status' => 'error', 'msg' => 'Gói hosting không tồn tại']));

    $base_price     = $row['price'] * $months;
    $discount_price = $base_price;
    $discount_label = '';

    if (!empty($coupon)) {
        if ($cou = $TN->get_row("SELECT * FROM `coupons` WHERE `id` = '$coupon' AND `status` = 1")) {
            $discount_price -= $base_price * $cou['discount_percent'] / 100;
            $discount_label = $cou['discount_percent'] . '%';
        }
    }

    if ($getUser['money'] < $discount_price)
        die(json_encode(['status' => 'error', 'msg' => 'Số dư của bạn không đủ ' . format_cash($discount_price) . 'đ, vui lòng nạp thêm để thực hiện']));

    if (!$server = $TN->get_row("SELECT * FROM `server_hosting` WHERE `uname` = '{$row['code']}' AND `status` = 'on'"))
        die(json_encode(['status' => 'error', 'msg' => 'Không tìm thấy máy chủ phù hợp để tạo hosting']));

    $domainParts = explode('.', $domain);
    $taikhoan = 'az' . strtolower(preg_replace('/[^a-z0-9]/', '', $domainParts[0]));
    $matkhau  = 'Az' . RandStrings(7);

    $query = $server['hostname'] . ':2087/json-api/createacct?api.version=1'
        . '&username=' . $taikhoan
        . '&domain=' . $domain
        . '&plan=' . $server['whmusername'] . '_' . $row['code']
        . '&featurelist=default'
        . '&password=' . $matkhau
        . '&ip=n&cgi=1&hasshell=1'
        . '&contactemail=' . $getUser['email']
        . '&cpmod=paper_lantern&language=vi';

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HEADER         => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ["Authorization: Basic " . base64_encode($server['whmusername'] . ":" . $server['whmpassword'])],
        CURLOPT_URL            => $query
    ]);
    $result = curl_exec($curl);
    $data   = json_decode($result, true);
    curl_close($curl);

    if ($result === false) {
        echo json_encode([
            'status' => 'error',
            'msg'    => 'Lỗi kết nối với WHM: ' . curl_error($curl)
        ]);
        exit;
    }

    $TN->insert("logs", [
        'user_id'     => $getUser['id'],
        'ip'          => myip(),
        'device'      => $_SERVER['HTTP_USER_AGENT'],
        'create_date' => time(),
        'action'      => "WHM Response: " . json_encode($data)
    ]);

    if (!empty($data['metadata']['result']) && $data['metadata']['result'] == 1) {
        if (!RemoveCredits($getUser['id'], $discount_price, "Thanh toán Hosting: {$row['name']} ({$months} tháng)"))
            die(json_encode(['status' => 'error', 'msg' => 'Không thể trừ tiền. Vui lòng thử lại']));

        $today   = date('Y-m-d');
        $end_day = date('Y-m-d', strtotime("+$months months"));

        $TN->insert("tbl_his_hosting", [
            'user_id'      => $getUser['id'],
            'name'         => $row['name'],
            'price'        => $discount_price,
            'domain'       => $domain,
            'taikhoan'     => $taikhoan,
            'matkhau'      => $matkhau,
            'email'        => $getUser['email'],
            'server'       => $row['code'],
            'create_date'  => $today,
            'end_day'      => $end_day,
            'status'       => 'hoatdong'
        ]);

        $TN->insert("logs", [
            'user_id'     => $getUser['id'],
            'ip'          => myip(),
            'device'      => $_SERVER['HTTP_USER_AGENT'],
            'create_date' => time(),
            'action'      => "Đăng ký hosting ({$row['name']}) - Gói: ({$row['code']})"
        ]);

        sendTele("
<b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>Tài Khoản:</b> <code>" . htmlspecialchars($getUser['username']) . "</code>\n
<b>Hành Động:</b> Mua Hosting <code>{$row['name']}</code> cho domain <code>$domain</code>\n
<b>Thời Hạn:</b> <code>{$months} tháng</code>\n
<b>Giá:</b> <code>" . number_format($discount_price) . "đ ({$discount_label})</code>\n
<b>IP:</b> <code>" . myip() . "</code>");

        echo json_encode([
            'status' => 'success',
            'msg'    => "Hosting đã được tạo thành công!"
        ]);
    } else {
        $reason = isset($data['metadata']['reason']) ? $data['metadata']['reason'] : 'Không rõ lỗi';
        echo json_encode([
            'status' => 'error',
            'msg'    => "Tạo hosting thất bại: $reason"
        ]);
    }
}
?>
