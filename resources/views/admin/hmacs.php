<?php
// hmacs.php - chỉ dùng admin, minimal
require_once(__DIR__.'/../../../core/auth_token.php'); // Giữ nguyên, giả định $pdo đã được khởi tạo trước đó

header('Content-Type: application/json; charset=utf-8');

$op = $_GET['op'] ?? 'list';
if ($op == 'list') {
    $st = $pdo->query("SELECT id, name, secret, target_url, is_active, created_at FROM hmacs ORDER BY id DESC");
    $all = $st->fetchAll();
    echo json_encode(['status'=>'ok','data'=>$all]);
    exit;
}
if ($op == 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data['name'] || !$data['secret'] || !$data['target_url']) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Missing fields']);
        exit;
    }
    $ins = $pdo->prepare("INSERT INTO hmacs (name, secret, target_url, is_active, description) VALUES (:n,:s,:t,:a,:d)");
    $ins->execute([
        'n'=>$data['name'],
        's'=>$data['secret'],
        't'=>$data['target_url'],
        'a'=>isset($data['is_active'])?intval($data['is_active']):1,
        'd'=>$data['description'] ?? null
    ]);
    echo json_encode(['status'=>'ok','id'=>$pdo->lastInsertId()]);
    exit;
}
echo json_encode(['status'=>'error','message'=>'Unknown op']);
