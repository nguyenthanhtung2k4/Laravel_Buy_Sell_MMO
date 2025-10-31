<?php
// ============================================
// File: check.php  (Version ổn định, có rate-limit)
// ============================================

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';

// --------------------------------------------
// Đọc body JSON gốc
$raw = file_get_contents('php://input');
if (!$raw) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Empty body']);
    exit;
}

// Chuyển về JSON chuẩn hóa (canonical)
$canonical = canonical_json_from_string($raw);
if ($canonical === false) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Invalid JSON']);
    exit;
}

// Lấy header chữ ký
$signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
if (!$signature) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Missing signature']);
    exit;
}

// Giải mã payload
$payload = json_decode($raw, true);
if (!$payload) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Invalid JSON payload']);
    exit;
}

$token = $payload['token'] ?? '';
$device_id = $payload['device_id'] ?? '';
$event = $payload['event'] ?? '';
if (!$token || !$device_id || !$event) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Missing required fields']);
    exit;
}

// --------------------------------------------
// Tìm license + secret
$stmt = $pdo->prepare("SELECT l.id as license_id, l.revoked, l.expires_at, l.max_devices,
                              h.secret, h.target_url, h.is_active
                       FROM licenses l
                       JOIN hmacs h ON l.hmac_id = h.id
                       WHERE l.license_key = :token LIMIT 1");
$stmt->execute(['token'=>$token]);
$row = $stmt->fetch();

if (!$row) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'License not found']);
    exit;
}
if ($row['revoked']) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'License revoked']);
    exit;
}
if ($row['expires_at'] && strtotime($row['expires_at']) < time()) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'License expired']);
    exit;
}
if (!$row['is_active']) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'HMAC disabled']);
    exit;
}

// --------------------------------------------
// Kiểm tra chữ ký HMAC
$secret = $row['secret'];
$calc = hash_hmac('sha256', $canonical, $secret);
if (!hash_equals($calc, $signature)) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Invalid signature']);
    exit;
}

$license_id = $row['license_id'];
$max_devices = intval($row['max_devices']);
$target_url = $row['target_url'];

// --------------------------------------------
// HÀM TẠO LINK MỚI + RATE LIMIT CHUẨN
function create_one_time_link($pdo, $license_id, $device_id, $target_url)
{
    // Kiểm tra lần cuối tạo link
    $q = $pdo->prepare("SELECT created_at FROM redirect_links
                        WHERE license_id = :lid AND device_id = :did
                        ORDER BY id DESC LIMIT 1");
    $q->execute(['lid'=>$license_id, 'did'=>$device_id]);
    $last_time = $q->fetchColumn();

    if ($last_time) {
        $elapsed = time() - strtotime($last_time);
        if ($elapsed < 30) {
            http_response_code(429);
            echo json_encode([
                'status' => 'error',
                'message' => 'too_many_requests',
                'retry_after' => 30 - $elapsed
            ]);
            exit;
        }
    }

    // Tạo token và thời gian hết hạn
    $token_bytes = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', time() + 300); // 5 phút
    $now = date('Y-m-d H:i:s');

    // Lưu vào DB (đảm bảo có created_at)
    $insert = $pdo->prepare("INSERT INTO redirect_links
        (license_id, device_id, token, target_url, expires_at, created_at)
        VALUES (:lid, :did, :tok, :turl, :exp, :now)");
    $insert->execute([
        'lid' => $license_id,
        'did' => $device_id,
        'tok' => $token_bytes,
        'turl' => $target_url,
        'exp' => $expires_at,
        'now' => $now
    ]);

    // Tạo link redirect
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $redirect_url = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/redirect.php?token=' . $token_bytes;

    return $redirect_url;
}

// --------------------------------------------
// Xử lý event = register
if ($event === 'register') {
    // Kiểm tra thiết bị
    $st = $pdo->prepare("SELECT id FROM devices WHERE license_id=:lid AND device_id=:did LIMIT 1");
    $st->execute(['lid'=>$license_id, 'did'=>$device_id]);
    $exists = $st->fetch();

    // Đếm số thiết bị
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM devices WHERE license_id=:lid");
    $countStmt->execute(['lid'=>$license_id]);
    $device_count = intval($countStmt->fetchColumn());

    if (!$exists) {
        if ($device_count >= $max_devices) {
            echo json_encode(['status'=>'error','message'=>'Device limit reached']);
            exit;
        }
        $insertDev = $pdo->prepare("INSERT INTO devices (license_id, device_id, device_name, os_info, ip_addr, app_version, last_seen)
                                    VALUES (:lid, :did, :dname, :os, :ip, :ver, NOW())");
        $insertDev->execute([
            'lid'=>$license_id,
            'did'=>$device_id,
            'dname'=>$payload['device_name'] ?? null,
            'os'=>$payload['os_info'] ?? null,
            'ip'=>$_SERVER['REMOTE_ADDR'] ?? null,
            'ver'=>$payload['app_version'] ?? null
        ]);
    } else {
        $pdo->prepare("UPDATE devices SET last_seen=NOW() WHERE id=:id")->execute(['id'=>$exists['id']]);
    }

    // Luôn tạo redirect link (có rate limit)
    $redirect_url = create_one_time_link($pdo, $license_id, $device_id, $target_url);
    echo json_encode(['status'=>'ok','message'=>'registered','redirect_url'=>$redirect_url]);
    exit;
}

// --------------------------------------------
// Xử lý event = get_link
if ($event === 'get_link') {
    $st = $pdo->prepare("SELECT id FROM devices WHERE license_id=:lid AND device_id=:did LIMIT 1");
    $st->execute(['lid'=>$license_id,'did'=>$device_id]);
    $exists = $st->fetch();

    if (!$exists) {
        http_response_code(403);
        echo json_encode(['status'=>'error','message'=>'Device not registered']);
        exit;
    }

    $redirect_url = create_one_time_link($pdo, $license_id, $device_id, $target_url);
    echo json_encode(['status'=>'ok','message'=>'link_created','redirect_url'=>$redirect_url]);
    exit;
}

// --------------------------------------------
// Xử lý event = heartbeat (nếu muốn)
if ($event === 'heartbeat') {
    $pdo->prepare("UPDATE devices SET last_seen=NOW() WHERE license_id=:lid AND device_id=:did")
        ->execute(['lid'=>$license_id, 'did'=>$device_id]);
    echo json_encode(['status'=>'ok','message'=>'heartbeat']);
    exit;
}

// --------------------------------------------
echo json_encode(['status'=>'error','message'=>'Unknown event']);
exit;
