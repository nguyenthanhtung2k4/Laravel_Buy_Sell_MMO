<?php
$TN = new DB;



function buiducthanh_enc($string) {
	$encrypt_method = "AES-256-CBC";
	$key = hash('sha256', 'buiducthanh');
	$iv = substr(hash('sha256', 'buiducthanh.info'), 0, 32);
	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($output);
    return $output;
}

function buiducthanh_dec($string) {
	$encrypt_method = "AES-256-CBC";
	$key = hash('sha256', 'buiducthanh');
	$iv = substr(hash('sha256', 'buiducthanh.info'), 0, 16);
	//$iv = substr(hash('sha256', 'buiducthanh.info'), 0, 32); 
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function timeAgo3($time_ago)
{
    $time_ago = date("Y-m-d H:i:s", $time_ago);
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây";
    }
    //Minutes
    else if ($minutes <= 60) {
        return "$minutes phút";
    }
    //Hours
    else if ($hours <= 24) {
        return "$hours tiếng";
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Hôm qua";
        } else {
            return "$days ngày";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        return "$weeks tuần";
    }
    //Months
    else if ($months <= 12) {
        return "$months tháng";
    }
    //Years
    else {
        return "$years năm";
    }
}
function timeAgo2($time_ago)
{
    // Nếu $time_ago không phải dạng timestamp, thì đảm bảo nó là timestamp
    if (!is_int($time_ago)) {
        $time_ago = strtotime($time_ago);
    }

    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);

    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây trước";
    }
    // Minutes
    else if ($minutes <= 60) {
        return "$minutes phút trước";
    }
    // Hours
    else if ($hours <= 24) {
        return "$hours tiếng trước";
    }
    // Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Hôm qua";
        } else {
            return "$days ngày trước";
        }
    }
    // Weeks
    else if ($weeks <= 4.3) {
        return "$weeks tuần trước";
    }
    // Months
    else if ($months <= 12) {
        return "$months tháng trước";
    }
    // Years
    else {
        return "$years năm trước";
    }
}

function check_xss($data) {
    $xss_patterns = [
        '<script',
        'javascript:',
        'onerror=',
        'onload=',
        'alert(',
        'document.cookie',
        'document.write',
        '<iframe',
        '<img',
        'src=',
        'eval(',
    ];

    foreach ($xss_patterns as $pattern) {
        if (stripos($data, $pattern) !== false) {
            return true;
        }
    }

    return false;
}
function status_code($code)
{
if($code = 0)
{
return 'OFF';
}
if($code = 1)
{
return 'ON';
}
}
function status_hosting($data)
{
    if ($data == 'dangtao') {
        $show = '<span class="status-badge dangtao">Đang khởi tạo</span>';
    } else if ($data == 'dahuy') {
        $show = '<p class="status-badge dahuy">Đã hủy</p>';
    } else if ($data == 'hoatdong') {
        $show = '<p class="text-success hoatdong">Hoạt động</p>';
    } else if ($data == 'hethan') {
        $show = '<p class="status-badge hethan">Hết hạn</p>';
    } else {
        $show = '<p class="status-badge ">Khác</p>';
    }
    return $show;
}
function status_vps($data)
{
    if ($data == 'dangtao') {
        $show = '<span class="status-badge dangtao">Đang khởi tạo</span>';
    } else if ($data == 'dahuy') {
        $show = '<p class="status-badge dahuy">Đã hủy</p>';
    } else if ($data == 'hoatdong') {
        $show = '<p class="text-success hoatdong">Hoạt động</p>';
    } else if ($data == 'hethan') {
        $show = '<p class="status-badge hethan">Hết hạn</p>';
    } else {
        $show = '<p class="status-badge ">Khác</p>';
    }
    return $show;
}
function status_domain($data)
{
    if ($data == 'pending') {
        $show = '<p class="status-badge pending">Đang xử lý</p>';
    } else if ($data == 'fail') {
        $show = '<p class="status-badge fail">Lỗi</p>';
    } else if ($data == 'success') {
        $show = '<p class="text-success success">Hoạt động</p>';
    } else {
        $show = '<p class="status-badge in-active">Khác</p>';
    }
    return $show;
}
function status_cron($data)
{
    if ($data == 'ON') {
        $show = '<p class="text-success whitespace-no-wrap">Hoạt động</p>';
    } else if ($data == 'STOP') {
        $show = '<p class="text-badge whitespace-no-wrap">Dừng</p>';
    } else if ($data == 'OFF') {
        $show = '<p class="text-badge whitespace-no-wrap">Hết hạn</p>';
    } else {
        $show = '<p class="text-badge whitespace-no-wrap">LỖI</p>';
    }
    return $show;
}
function card($data)
{
    if ($data == 'xuly') {
        $show = '<span class="status-badge pending">Đang xử lý</span>';
    } else if ($data == 'thatbai') {
        $show = '<button class="status-badge in-active">Thẻ sai</button>';
    } else if ($data == 'thanhcong') {
        $show = '<p class="text-success whitespace-no-wrap">Thẻ đúng</p>';
    } else {
        $show = '<button class="status-badge in-active">Khác</button>';
    }
    return $show;
}
function getCode($id, $row){
    global $TN;
    return $TN->get_row("SELECT * FROM `tbl_list_code` WHERE `id` = '$id' ")[$row];
}
function objectToArray($d) 
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}
function create_slug($string)
{
    $search = array(
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        "/[^a-zA-Z0-9\-\_]/",
    );
    $replace = array(
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd',
        'A',
        'E',
        'I',
        'O',
        'U',
        'Y',
        'D',
        '-',
    );
    $string = preg_replace($search, $replace, $string);
    $string = preg_replace('/(-)+/', '-', $string);
    $string = strtolower($string);
    return $string;
}
function curl_get_info($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpcode;
}
function curl_post_info($url)
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    ));
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpcode;
}
function getRowRealtime($table, $id, $row)
{
    global $TN;
    return $TN->get_row("SELECT * FROM `$table` WHERE `id` = '$id' ")[$row];
}
function Banned($user_id, $reason)
{
    global $TN;
    $TN->insert("logs", [
        'user_id'       => $user_id,
        'ip'            => myip(),
        'device'        => $_SERVER['HTTP_USER_AGENT'],
        'create_date'    => time(),
        'action'        => 'Tài khoản bị khoá lý do (' . $reason . ')'
    ]);
    $TN->update("users", [
        'banned' => 1
    ], " `id` = '$user_id' ");
}
function RemoveCredits($user_id, $amount, $reason)
{
    global $TN;
    $TN->insert("log_balance", array(
        'money_before' => getUser($user_id, 'money'),
        'money_change' => $amount,
        'money_after' => getUser($user_id, 'money') - $amount,
        'time' => time(),
        'content' => $reason,
        'user_id' => $user_id
    ));
    $isRemove = $TN->tru("users", "money", $amount, " `id` = '$user_id' ");
    if ($isRemove) {
        return true;
    } else {
        return false;
    }
}
function PlusCredits($user_id, $amount, $reason)
{
    global $TN;
    $TN->insert("log_balance", array(
        'money_before' => getUser($user_id, 'money'),
        'money_change' => $amount,
        'money_after' => getUser($user_id, 'money') + $amount,
        'time' => time(),
        'content' => $reason,
        'user_id' => $user_id
    ));
    $isRemove = $TN->cong("users", "money", $amount, " `id` = '$user_id' ");
    $TN->cong("users", "total_money", $amount, " `id` = '$user_id' ");
    if ($isRemove) {
        return true;
    } else {
        return false;
    }
}
//thông tin user theo id
function getUser($id, $row)
{
    global $TN;
    return $TN->get_row("SELECT * FROM `users` WHERE `id` = '$id' ")[$row];
}
function format_date($time)
{
    return date("H:i:s d/m/Y", $time);
}
function redirect($url)
{
    header("Location: {$url}");
    exit();
}
function CreateToken()
{
    return random('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM', 12).'-'.random('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM', 6) . '-' . random('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM', 4) . '-' . random('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM', 4) . '-' . random('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM', 4);
}
function status_source($data)
{
    if ($data == '1') {
        return '<b style="color:green">Hiển thị</b>';
    } else if ($data == '0') {
        return '<b style="color:red">Ẩn</b>';
    }
}
//hàm bot thông báo
function sendTele($message){
    global $TN;
    
    $tele_token = $TN->site('token_telegram');
    $tele_chatid = $TN->site('chat_id_telegram');

    $data = [
        'chat_id' => $tele_chatid,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    
    $url = 'https://api.telegram.org/bot'.$tele_token.'/sendMessage';

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    
    return $result;
}
function admin($data)
{
    if ($data == 'admin')
    {
        $show = '<span class="badge badge-success">Admin</span>';
    }
    else
    {
        $show = '<span class="badge badge-danger">Thành viên</span>';
    }
    return $show;
}
function sendCSM($mail_nhan, $ten_nhan, $chu_de, $noi_dung, $bcc)
{
    global $TN;
    // PHPMailer Modify
    $mail = new PHPMailer();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = "html";
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $TN->site('email_smtp'); // GMAIL STMP
    $mail->Password = $TN->site('pass_email_smtp'); // PASS STMP
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom($TN->site('email_smtp'), $bcc);
    $mail->addAddress($mail_nhan, $ten_nhan);
    $mail->addReplyTo($TN->site('email_smtp'), $bcc);
    $mail->isHTML(true);
    $mail->Subject = $chu_de;
    $mail->Body = $noi_dung;
    $mail->CharSet = 'UTF-8';
    $send = $mail->send();
    return $send;
}

function BASE_URL($url)
{
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($base_url == 'https://localhost') {
        $base_url = 'https://localhost';
    }
    return $base_url . '/' . $url;
}
function gettime()
{
    return date('Y/m/d H:i:s', time());
}
function xss($data)
{
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);

    // we are done...
    $nhatloc = htmlspecialchars(addslashes(trim($data)));

    return $nhatloc;
}
function check_string($data)
{
    return trim(htmlspecialchars(addslashes($data)));
    //return str_replace(array('<',"'",'>','?','/',"\\",'--','eval(','<php'),array('','','','','','','','',''),htmlspecialchars(addslashes(strip_tags($data))));
}
function format_cash($price)
{
    return str_replace(",", ".", number_format($price));
}
function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
function curl_post($data, $url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
function random($string, $int)
{
    return substr(str_shuffle($string), 0, $int);
}
function pheptru($int1, $int2)
{
    return $int1 - $int2;
}
function phepcong($int1, $int2)
{
    return $int1 + $int2;
}
function phepnhan($int1, $int2)
{
    return $int1 * $int2;
}
function phepchia($int1, $int2)
{
    return $int1 / $int2;
}
function parse_order_id($des, $MEMO_PREFIX)
{
    $re = '/'.$MEMO_PREFIX.'\d+/im';
    preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);
    if (count($matches) == 0) {
        return null;
    }
    // Print the entire match result
    $orderCode = $matches[0][0];
    $prefixLength = strlen($MEMO_PREFIX);
    $orderId = intval(substr($orderCode, $prefixLength));
    return $orderId ;
}
function check_img($img)
{
    $filename = $_FILES[$img]['name'];
    $ext = explode(".", $filename);
    $ext = end($ext);
    $valid_ext = array("png", "jpeg", "jpg", "PNG", "JPEG", "JPG", "gif", "GIF");
    if (in_array($ext, $valid_ext)) {
        return true;
    }
}
function tn_error_alert($text)
{
    return die('<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </svg>
   '.$text.'
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>');
}
function tn_error($text)
{
    return die('<script type="text/javascript">
    cuteToast({
        type: "error",
        message: "' . $text . '",
        timer: 5000
    });
    </script>');
}
function tn_success($text)
{
    return die('<script type="text/javascript">
    cuteToast({
        type: "success",
        message: "' . $text . '",
        timer: 5000
    });
    </script>');
}

function tn_success_time($text, $url, $time)
{
    return die('<script type="text/javascript">
    cuteToast({
        type: "success",
        message: "' . $text . '",
        timer: 5000
    });
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function tn_error_time($text, $url, $time)
{
    return die('<script type="text/javascript">
    cuteToast({
        type: "error",
        message: "' . $text . '",
        timer: 5000
    });
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
// display online
function display_online($time)
{
    if (time() - $time <= 300) {
        return '<span class="badge bg-success-gradient me-1">Online</span>';
    } else {
        return '<span class="badge bg-danger-gradient me-1">Offline</span>';
    }
}
// display online
function online($time)
{
    if (time() - $time <= 300) {
        return '<span class="badge badge-success"><i class="fa-solid fa-circle"></i>Online</span>';
    } else {
        return '<span class="badge bg-soft-danger"><i class="fa-solid fa-circle"></i>Offline</span>';
    }
}
function display_banned($data)
{
    if ($data == 1) {
        $show = '<span class="badge bg-danger-gradient me-1">Bị khóa</span>';
    } else if ($data == 0) {
        $show = '<span class="badge bg-success-gradient me-1">Hoạt động</span>';
    }
    return $show;
}

function getHeader()
{
    $headers = array();
    $copy_server = array(
        'CONTENT_TYPE' => 'Content-Type',
        'CONTENT_LENGTH' => 'Content-Length',
        'CONTENT_MD5' => 'Content-Md5',
    );
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $key = substr($key, 5);
            if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                $headers[$key] = $value;
            }
        } elseif (isset($copy_server[$key])) {
            $headers[$copy_server[$key]] = $value;
        }
    }
    if (!isset($headers['Authorization'])) {
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
            $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
        } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
        }
    }
    return $headers;
}

function check_username($data)
{
    if (preg_match('/^[a-zA-Z0-9_-]{3,16}$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}
function check_email($data)
{
    if (preg_match('/^.+@.+$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}
function check_phone($data)
{
    if (preg_match('/^\+?(\d.*){3,}$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}
function check_url($url)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_HEADER, 1);
    curl_setopt($c, CURLOPT_NOBODY, 1);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);
    if (!curl_exec($c)) {
        return false;
    } else {
        return true;
    }
}
function check_zip($img)
{
    $filename = $_FILES[$img]['name'];
    $ext = explode(".", $filename);
    $ext = end($ext);
    $valid_ext = array("zip", "ZIP");
    if (in_array($ext, $valid_ext)) {
        return true;
    }
}
function myip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
}
function timeAgo($time_ago)
{
    $time_ago = date("Y-m-d H:i:s", $time_ago);
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây trước";
    }
    //Minutes
    else if ($minutes <= 60) {
        return "$minutes phút trước";
    }
    //Hours
    else if ($hours <= 24) {
        return "$hours tiếng trước";
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Hôm qua";
        } else {
            return "$days ngày trước";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        return "$weeks tuần trước";
    }
    //Months
    else if ($months <= 12) {
        return "$months tháng trước";
    }
    //Years
    else {
        return "$years năm trước";
    }
}
function dirToArray($dir)
{

    $result = array();

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $result[] = $value;
            }
        }
    }

    return $result;
}

function realFileSize($path)
{
    if (!file_exists($path)) {
        return false;
    }

    $size = filesize($path);

    if (!($file = fopen($path, 'rb'))) {
        return false;
    }

    if ($size >= 0) { //Check if it really is a small file (< 2 GB)
        if (fseek($file, 0, SEEK_END) === 0) { //It really is a small file
            fclose($file);
            return $size;
        }
    }

    //Quickly jump the first 2 GB with fseek. After that fseek is not working on 32 bit php (it uses int internally)
    $size = PHP_INT_MAX - 1;
    if (fseek($file, PHP_INT_MAX - 1) !== 0) {
        fclose($file);
        return false;
    }

    $length = 1024 * 1024;
    while (!feof($file)) { //Read the file until end
        $read = fread($file, $length);
        $size = bcadd($size, $length);
    }
    $size = bcsub($size, $length);
    $size = bcadd($size, strlen($read));

    fclose($file);
    return $size;
}
function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4),
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3),
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2),
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024,
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1,
        ),
    );

    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}
function GetCorrectMTime($filePath)
{

    $time = filemtime($filePath);

    $isDST = (date('I', $time) == 1);
    $systemDST = (date('I') == 1);

    $adjustment = 0;

    if ($isDST == false && $systemDST == true) {
        $adjustment = 3600;
    } else if ($isDST == true && $systemDST == false) {
        $adjustment = -3600;
    } else {
        $adjustment = 0;
    }

    return ($time + $adjustment);
}
function DownloadFile($file)
{ // $file = include path
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }
}

function callGeminiAPI($message) {
    $API_KEY = "AIzaSyATxLqniWL35JR2KokHfQ95mJGZTdhxuek";
    $API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$API_KEY";

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $message]
                ]
            ]
        ]
    ];

    $ch = curl_init($API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    return $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "⚠️ Xin lỗi, hệ thống đang bận. Vui lòng thử lại sau.";
}