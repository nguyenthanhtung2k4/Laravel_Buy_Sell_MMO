<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");



function create_token($hmac ){
    $date = date("utf8");
    $token = "";  # random theo  cấu hình như sau  : C25-56547-56567-457567-565-557


    $table =  ("INSERT INTO licenses (license_key, hmac_id, max_devices, expires_at)
                               VALUES ($token,$hmac,5,$date)");
    

        
}



if ($_POST['action'] == 'pay') {
    if (empty($_POST['token'])) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '" . xss($_POST['token']) . "' AND `banned` = '0' ")) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }
    
    $id_username = check_string($getUser['id']);
    $id_product = check_string($_POST['id_product']);
    $row = $TN->get_row("SELECT * FROM `tbl_list_code` WHERE `id` = '$id_product' ");

    $

    $history = $TN->get_row("SELECT * FROM `tbl_his_code` WHERE `user_id` = '$id_username' AND `product_id` = '$id_product'");
    $discount = $row['price'] - ($row['price'] * $row['sale'] / 100);
    $total = $discount - $discount * $getUser['discount'] / 100;
    if (!$row) {
        die(json_encode(['status' => '1', 'msg' => 'Mã nguồn không tồn tại trong hệ thống']));
    }
    if ($history['user_id'] && $history['product_id']) {
        die(json_encode(['status' => '1', 'msg' => 'Bạn đã mua mã nguồn này rồi']));
    }
    if ($getUser['money'] < $total) {
        die(json_encode(['status' => '1', 'msg' => 'Số dư của bạn không đủ ' . format_cash($total) . 'đ, vui lòng nạp thêm để thực hiện']));
    }
    
    $isBuy = RemoveCredits( $getUser['id'], $total, "Mua Mã Nguồn (" . htmlspecialchars(getCode($id_product, 'name')) . ")" );
    
    if ($isBuy) {
        if (getRowRealtime("users", $getUser['id'], "money") < 0) {
            Banned($getUser['id'], 'Gian lận khi mua mã nguồn');
            die(json_encode(['status' => '1', 'msg' => 'Bạn đã bị khoá tài khoản vì gian lận']));
        }
        
        $TN->insert("logs", [
            'user_id'       => $getUser['id'],
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'    => time(),
            'action'        => 'Mua Mã Nguồn (' . htmlspecialchars(getCode($id_product, 'name')) . ')'
         ]);
        
        $TN->cong("tbl_list_code", "sold", 1, " `id` = '".$id_product."' ");
        


        $token = create_token($hmac);

        $TN->insert("tbl_his_code", [
            'user_id'            => $getUser['id'],
            'product_id'        => $id_product,
            'magd'        => random('QWERTYUIOPASDFGHJKZXCVBNM', 2).time(),
            'price'    => $total,
            'create_date'    => time(),
            'token' =>  $token
         ]);
         
sendTele("
<b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>Tài Khoản:</b> <code>" . htmlspecialchars(getUser($id_username, 'username')) . "</code>\n
<b>Hành Động:</b> Mua Mã Nguồn <code>" . htmlspecialchars(getCode($id_product, 'name')) . "</code> Với Giá <code>" . number_format($total) . "đ</code>\n
<b>IP:</b> <code>" . myip() . "</code>
");

        die(json_encode(['status' => '2', 'msg' => 'Thanh toán thành công']));
    }
}
?>




<!-- Note vào  -->



<!-- sql: 
 his-code ->  là magd
 
 
 | down gọi lại his-code <> product_id gọi lại list-code

 list-code ->  thêm hmac_id 
 


-->
 <!-- file:
  buy-code ->  logic thanh toán. 
  user-history 
  add-code ->  admin#
  
-->

<!--  Phía ADMIN  KEY  
  check.php
  clear.php
  admin.php = đổi tên Key.php

  helpers.php
  hmacs.php 
  -->
  <!-- Phía api #
  check.php
  redirect.php?
  helpers.php

-->
