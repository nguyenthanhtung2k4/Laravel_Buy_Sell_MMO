<?php
// file: hmacs.php (Tên file giả định)
if (!defined('IN_SITE')) {
      die('The Request Not Found');
}
$title = 'Quản lý HMAC Keys | ' . $TN->site('title');
$body = [
      'title' => 'Quản lý HMAC Keys'
];
$body['header'] = '
';
$body['footer'] = '
';

require_once(__DIR__ . '/key/logs.php');

