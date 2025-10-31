<?php
      require_once("../core/DB.php");
      require_once("../core/helpers.php");

    if (isset($_GET['status']) && $_GET['code'] && $_GET['serial'] && $_GET['trans_id'] && $_GET['telco'] && $_GET['callback_sign'])
    {
        $status = xss($_GET['status']);
        $serial = xss($_GET['serial']);
        $code = xss($_GET['code']);
        $request_id = xss($_GET['request_id']);
        $message = xss($_GET['message']);
        $real_money = xss($_GET['value']);
        $geted_money = xss($_GET['amount']);
        $nhamang = xss($_GET['telco']);
        $trans_id = xss($_GET['trans_id']);
        $check_sign = md5($TN->site('partner_key').$code.$serial);
    
        if ($_GET['callback_sign'] == $check_sign) 
        {
            $row = $TN->get_row(" SELECT * FROM `cards` WHERE `code` = '$request_id' AND `status` = 'xuly' ");
            if(!$row)
            {
                die("Cái quát đờ phắc gì vậy?");
            }
            $row_user = $TN->get_row(" SELECT * FROM `users` WHERE `username` = '".$row['username']."' ");
            if ($status == 1)
            {
                $thucnhan = $geted_money;
                
                $TN->update("cards", array(
                    'status'    => 'thanhcong',
                    'note'      => 'Đúng Mệnh Giá',
                    'thucnhan'  => $thucnhan
                ), " `id` = '".$row['id']."' ");

                $TN->cong("users", "money", $thucnhan, " `id` = '".$row_user['id']."' ");
                $TN->cong("users", "total_money", $thucnhan, " `id` = '".$row_user['id']."' ");

                $TN->insert("dongtien", array(
                    'sotientruoc' => $row_user['money'],
                    'sotienthaydoi' => $thucnhan,
                    'sotiensau' => $row_user['money'] + $thucnhan,
                    'thoigian' => time(),
                    'noidung' => 'Nạp tiền tự động qua card seri ('.$row['seri'].')',
                    'username' => $row_user['username']
                ));
            }
            else if ($status == 2)
            {
                $thucnhan = $geted_money / 2;
                if($row_user['level'] == 'ctv')
                {
                    $thucnhan = $real_money / 2;
                }

                $TN->update("cards", array(
                    'status'    => 'thanhcong',
                    'note'      => 'Sai Mệnh Giá',
                    'thucnhan'  => $thucnhan
                ), " `id` = '".$row['id']."' ");

                $TN->cong("users", "money", $thucnhan, " `id` = '".$row_user['id']."' ");
                $TN->cong("users", "total_money", $thucnhan, " `id` = '".$row_user['id']."' ");

                $TN->insert("dongtien", array(
                    'sotientruoc' => $row_user['money'],
                    'sotienthaydoi' => $thucnhan,
                    'sotiensau' => $row_user['money'] + $thucnhan,
                    'thoigian' => time(),
                    'noidung' => 'Nạp tiền tự động qua thẻ cào seri ('.$row['seri'].')',
                    'username' => $row_user['username']
                ));
            }
            else
            {
                $TN->update("cards", array(
                    'status'    => 'thatbai',
                    'note'      => 'Thẻ sai',
                    'thucnhan'  => '0'
                ), " `id` = '".$row['id']."' ");
            }
        }
        else 
        {
            echo "Cái quát đờ phắc gì vậy?";
        }
    } 
    else 
    {
        echo "Cái quát đờ phắc gì vậy?";
    }

