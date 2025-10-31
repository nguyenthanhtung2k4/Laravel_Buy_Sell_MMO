<?php
define("IN_SITE", true);
require_once("../core/DB.php");
require_once("../core/helpers.php");
$tokenResult = $TN->get_row("SELECT * FROM `bank` WHERE `short_name` = 'ACB'");
if ($tokenResult) {
    $token = $tokenResult['token'];
} else {
    echo "Không tìm thấy token ACB!";
    exit;
}
$result = curl_get("https://api.sieuthicode.net/api/historymb/$token");
$result = json_decode($result, true);
foreach ($result['data'] as $data) {
    $comment        = $data['description'];                 // NỘI DUNG CHUYỂN TIỀN
    $tranId         = $data['transactionNumber'];                  // MÃ GIAO DỊCH
    $amount         = $data['amount'];                  // SỐ TIỀN CHUYỂN
    $user_id        = parse_order_id($comment, $TN->site('noidungnap'));          // TÁCH NỘI DUNG CHUYỂN TIỀN
    // XỬ LÝ AUTO
    if ($getUser = $TN->get_row(" SELECT * FROM `users` WHERE `id` = '$user_id' ")) {
        if ($TN->num_rows(" SELECT * FROM `invoices` WHERE `trans_id` = '$tranId' ") == 0) {
            $insertSv2 = $TN->insert("invoices", array(
                'trans_id'               => $tranId,
                'payment_method'    => "ACB",
                'user_id'           => $getUser['id'],
                'description'       => $comment,
                'amount'            => $amount,
                'status'            => 1,
                'create_time'       => time()
            ));
            if ($insertSv2) {
                $isCong = PlusCredits($getUser['id'], $amount, "Nạp tiền tự động qua MB (#$tranId - $comment - $amount)");
                if ($isCong) {
                    echo '[<b style="color:green">-</b>] Xử lý thành công 1 hoá đơn.' . PHP_EOL;
                }
            }
        }
    }
}

