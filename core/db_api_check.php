<?php 
// 1. Load DB (config, Class DB, get_pdo())
require_once __DIR__ .'/DB.php'; 
// require_once __DIR__ .'/is_user.php'; 

// 3. Thực thi kiểm tra
// CheckLogin() ;
// CheckAdmin();
// db.php
// PDO connection reused across server files

$db_host = DB_HOST;
$db_name = DB_NAME;
$db_user = DB_USER;
$db_pass = DB_PASS;
$db_charset = 'utf8mb4';

$dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'DB connection failed']);
    exit;
}
