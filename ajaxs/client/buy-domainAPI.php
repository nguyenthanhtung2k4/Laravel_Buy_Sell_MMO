<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");
 
function callNamecheapAPI($params) {
    $apiUrl = "https://api.namecheap.com/xml.response";
    $params['ApiUser']   = 'your_api_user';
    $params['ApiKey']    = 'your_api_key';
    $params['UserName']  = 'your_user';
    $params['ClientIp']  = 'your_whitelisted_ip';

    $url = $apiUrl . '?' . http_build_query($params);
    $result = file_get_contents($url);
    return simplexml_load_string($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token  = xss($_POST['token'] ?? '');
    $domain = xss($_POST['domain'] ?? '');
    $years  = intval($_POST['years'] ?? 1);

    if (empty($token)) die(json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập']));
    if (!$getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `banned` = 0"))
        die(json_encode(['status' => 'error', 'msg' => 'Token không hợp lệ']));

    if (empty($domain) || $years < 1)
        die(json_encode(['status' => 'error', 'msg' => 'Thông tin không đầy đủ']));

    $domainInfo = explode('.', $domain);
    if (count($domainInfo) < 2) die(json_encode(['status' => 'error', 'msg' => 'Tên miền không hợp lệ']));
    $ext = $domainInfo[1];

    $extData = $TN->get_row("SELECT * FROM tbl_list_domain WHERE name = '$ext'");
    if (!$extData) die(json_encode(['status' => 'error', 'msg' => 'Phần mở rộng tên miền không được hỗ trợ']));
    $price = $extData['price'] * $years;

    if ($getUser['money'] < $price)
        die(json_encode(['status' => 'error', 'msg' => 'Số dư không đủ để đăng ký tên miền']));

    // Trừ tiền
    if (!RemoveCredits($getUser['id'], $price, "Đăng ký tên miền: $domain"))
        die(json_encode(['status' => 'error', 'msg' => 'Không thể trừ tiền, vui lòng thử lại']));

    // Gọi API Namecheap
    $apiParams = [
        'Command'                 => 'namecheap.domains.create',
        'DomainName'              => $domain,
        'Years'                   => $years,
        'AddFreeWhoisguard'       => 'true',
        'WGEnabled'               => 'true',

        'RegistrantFirstName'     => 'John',
        'RegistrantLastName'      => 'Doe',
        'RegistrantAddress1'      => '123 Main St',
        'RegistrantCity'          => 'Los Angeles',
        'RegistrantStateProvince' => 'CA',
        'RegistrantPostalCode'    => '90001',
        'RegistrantCountry'       => 'US',
        'RegistrantPhone'         => '+1.5555555555',
        'RegistrantEmailAddress'  => $getUser['email'],
        'RegistrantOrganizationName' => 'Personal'
    ];

    $res = callNamecheapAPI($apiParams);

    if (isset($res->CommandResponse->DomainCreateResult['Registered']) && $res->CommandResponse->DomainCreateResult['Registered'] == 'true') {

        $TN->insert("tbl_his_domain", [
            'user_id'      => $getUser['id'],
            'domain'       => $domain,
            'years'        => $years,
            'price'        => $price,
            'create_time'  => date('Y-m-d H:i:s'),
            'status'       => 'success'
        ]);

        echo json_encode(['status' => 'success', 'msg' => 'Đăng ký tên miền thành công!']);
    } else {
        $TN->insert("tbl_his_domain", [
            'user_id'      => $getUser['id'],
            'domain'       => $domain,
            'years'        => $years,
            'price'        => $price,
            'create_time'  => date('Y-m-d H:i:s'),
            'status'       => 'fail'
        ]);

        echo json_encode(['status' => 'error', 'msg' => 'Đăng ký thất bại. Tên miền có thể đã tồn tại hoặc lỗi kết nối.']);
    }
}
?>
