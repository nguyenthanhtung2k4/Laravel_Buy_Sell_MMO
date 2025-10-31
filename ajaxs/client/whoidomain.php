<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");
require_once("../../core/is_user.php");

function getDomainStatus($domain) {
    $url = "https://whois.net.vn/whois.php?domain=" . $domain;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $httpCode != 200) {
        return 'null';
    }

    $response = trim($response);

    if ($response === '0') {
        return 'true';
    } elseif ($response === '1') {
        return 'false';
    } else {
        return 'null';
    }
}

function resultDomain($domain, $price, $status){
    if($status == 'true'){
    return '<tr>
            <td class="fs-base"> 
              <span class="badge rounded-pill bg-success"> Có Thể Đăng Ký
              </span>
            </td>
            
            <td class="text-center">
              <a class="fw-semibold">
                '.$domain.'
              </a>
            </td>
            
            <td class="text-center">
              <strong> '.format_cash($price).' <sup>đ</sup></strong>
            </td>
            
            <td class="text-center fs-base">
              <a class="btn btn-sm btn-alt-success" href="/client/pay-domain/' . $domain . '">
                <i class="fa fa-fw fa-cart-plus"></i>
              </a>
            </td>
          </tr>
          '; 
          
    } else if($status == 'false'){
        return '<tr>
            <td class="fs-base">
              <span class="badge rounded-pill bg-danger"> Đã được đăng ký</span>
            </td>
            
            <td class="text-center">
              <a class="fw-semibold">
                '.$domain.'
              </a>
            </td>
            
            <td class="text-center">
              <strong> '.format_cash($price).' <sup>đ</sup></strong>
            </td>
            
            <td class="text-center fs-base">
              <a class="btn btn-sm btn-alt-secondary">
                <i class="fa fa-fw fa-circle-xmark"></i>
              </a>
            </td>
          </tr>
          '; 
          
    } else if($status == 'null'){
    return '<tr>
            <td class="fs-base">
              <span class="badge rounded-pill bg-warning"> Không khả dụng </span>
            </td>
            
            <td class="text-center">
              <a class="fw-semibold">
                '.$domain.'
              </a>
            </td>
            
            <td class="text-center">
              <strong> '.format_cash($price).' <sup>đ</sup></strong>
            </td>
            
            <td class="text-center fs-base">
              <a class="btn btn-sm btn-alt-secondary">
                <i class="fa fa-window-close"></i>
              </a>
            </td>
          </tr>
        '; 
    }
}

$domain = isset($_POST['domain']) ? xss($_POST['domain']) : '';
if (!$domain) {
    echo '<tr><td colspan="4" class="text-center">Vui lòng nhập tên miền</td></tr>';
    exit;
}

$explode = explode(".", $domain);
$sub = $explode[0] ?? '';
$ext = $explode[1] ?? '';

if (!$sub) {
    echo '<tr><td colspan="4" class="text-center">Vui lòng nhập tên miền hợp lệ</td></tr>';
    exit;
}

if (!$ext) {
    $result = '';
    foreach ($TN->get_row("SELECT * FROM tbl_list_domain ORDER BY id DESC") as $row) {
        $fullDomain = $sub . '.' . $row['name'];
        $status = getDomainStatus($fullDomain);
        $result .= resultDomain($fullDomain, $row['price'], $status);
    }
    echo $result;
    exit;
}

$query = $TN->get_row("SELECT * FROM tbl_list_domain WHERE name = '$ext'");
if (!$query) {
    echo resultDomain($domain, 0, 'null');
    exit;
}

$mainStatus = getDomainStatus($domain);
echo resultDomain($domain, $query['price'], $mainStatus);

$otherExts = $TN->get_list("SELECT * FROM tbl_list_domain WHERE name != '$ext'");
foreach ($otherExts as $row) {
    $otherDomain = $sub . '.' . $row['name'];
    $status = getDomainStatus($otherDomain);
    echo resultDomain($otherDomain, $row['price'], $status);
}
?>
