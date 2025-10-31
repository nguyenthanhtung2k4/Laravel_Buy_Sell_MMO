<?php
// file: check.php
require_once __DIR__ . '/db.php';

$raw = file_get_contents('php://input');
if (!$raw) { write_file_log('ERROR','Empty body in request'); json_response(['status'=>'error','message'=>'Empty body'], 400); }

$payload = json_decode($raw, true);
if (!is_array($payload)) { write_file_log('ERROR','Invalid JSON in request'); json_response(['status'=>'error','message'=>'Invalid JSON'], 400); }

$token = $payload['token'] ?? null;
$device_id = $payload['device_id'] ?? null;
$event = $payload['event'] ?? null;
$app_version = $payload['app_version'] ?? 'unknown';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
$now = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
$signature = $_SERVER['HTTP_X_SIGNATURE'] ?? null; // Lấy chữ ký từ header

if (!$token || !$device_id || !$event || !$signature) { 
    write_file_log('ERROR','Missing fields or signature', ['token'=>$token,'device_id'=>$device_id,'event'=>$event,'sig_present'=>!is_null($signature)]); 
    json_response(['status'=>'error','message'=>'Missing token/device_id/event/signature'], 400); 
}

// Hàm xác thực chữ ký HMAC-SHA256
function verify_signature(string $raw_body, string $secret, string $signature): bool {
    $expected_sig = hash_hmac('sha256', $raw_body, $secret);
    // Sử dụng hash_equals để chống tấn công timing
    return hash_equals($expected_sig, $signature);
}


try {
    $pdo = get_pdo();

    // 1. Tìm license VÀ HMAC SECRET của nó (JOIN từ licenses sang hmacs)
    $stmt = $pdo->prepare("
        SELECT 
            l.id, 
            l.max_devices, 
            l.revoked, 
            l.expires_at, 
            l.target_url, 
            h.hmac_secret,
            h.is_active AS hmac_active
        FROM licenses l
        JOIN hmacs h ON l.hmac_id = h.id
        WHERE l.license_key = :k 
        LIMIT 1
    ");
    $stmt->execute([':k'=>$token]);
    $license = $stmt->fetch();

    if (!$license) { 
        write_file_log('WARN','Invalid token attempted', ['token'=>$token]); 
        json_response(['status'=>'error','message'=>'Invalid license key'], 403); 
    }

    // *NEW* Kiểm tra trạng thái HMAC
    if ($license['hmac_active'] != 1) {
        write_file_log('CRITICAL','HMAC is inactive', ['token'=>$token]);
        json_response(['status'=>'error','message'=>'HMAC Key is inactive. Please contact support.'], 403);
    }

    // 2. Xác thực chữ ký bằng HMAC SECRET TỪ DB
    $license_secret = $license['hmac_secret'];
    if (!verify_signature($raw, $license_secret, $signature)) {
        write_file_log('WARN','Invalid signature', ['license'=>$token,'device'=>$device_id,'sig_provided'=>$signature]); 
        json_response(['status'=>'error','message'=>'Invalid signature'], 403); 
    }

    // 3. Kiểm tra Revoked và Hết hạn (logic giữ nguyên)
    if ($license['revoked']) { 
        write_file_log('WARN','Revoked license attempted', ['token'=>$token]); 
        json_response(['status'=>'error','message'=>'License key has been revoked'], 403); 
    }
    
    // Check expiry
    if ($license['expires_at'] && strtotime($license['expires_at']) < time()) {
        write_file_log('WARN','Expired license attempted', ['token'=>$token,'expires_at'=>$license['expires_at']]); 
        json_response(['status'=>'error','message'=>'License key has expired'], 403); 
    }
    
    $license_id = $license['id'];
    $max_devices = (int)$license['max_devices'];
    $target_url = $license['target_url'];
    $expires_at = $license['expires_at'];

    // 4. Tìm kiếm thiết bị hiện tại
    $stmt = $pdo->prepare("SELECT id, device_name, os_info, ip_addr, app_version FROM devices WHERE license_id = :lid AND device_id = :did LIMIT 1");
    $stmt->execute([':lid'=>$license_id, ':did'=>$device_id]);
    $device = $stmt->fetch();

    if ($event === 'register') {
        // ... (Logic REGISTER giữ nguyên)
        if (!$device) {
            // Đếm số thiết bị hiện tại
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM devices WHERE license_id = :lid");
            $stmt->execute([':lid'=>$license_id]);
            $device_count = (int)$stmt->fetchColumn();

            if ($device_count >= $max_devices) {
                write_file_log('WARN','Max devices reached', ['license'=>$token,'device_id'=>$device_id,'count'=>$device_count,'max'=>$max_devices]); 
                json_response(['status'=>'error','message'=>'Maximum number of devices reached'], 403); 
            }
            
            // Lấy thông tin thiết bị từ payload (tùy chọn)
            $device_name = $payload['device_name'] ?? 'Unknown Device';
            $os_info = $payload['os_info'] ?? 'N/A';
            
            // Thêm thiết bị mới
            $stmt = $pdo->prepare("INSERT INTO devices (license_id, device_id, device_name, os_info, ip_addr, app_version, last_seen) VALUES (:lid, :did, :dname, :os, :ip, :appv, :now)");
            $stmt->execute([':lid'=>$license_id, ':did'=>$device_id, ':dname'=>$device_name, ':os'=>$os_info, ':ip'=>$ip, ':appv'=>$app_version, ':now'=>$now]);
            $device_id_db = (int)$pdo->lastInsertId();
            
            // Tạo link
            $redirect_link = create_redirect_link($pdo, $license_id, $device_id_db, $target_url, $expires_at);

            write_file_log('INFO','Device registered', ['license'=>$token,'device'=>$device_id,'ip'=>$ip]); 
            json_response(['status'=>'ok','link'=>$redirect_link, 'message'=>'Device registered successfully, link provided']); 
        } else {
            // Thiết bị đã đăng ký, chỉ cần cập nhật last_seen
            $device_id_db = (int)$device['id'];
            $stmt = $pdo->prepare("UPDATE devices SET last_seen = :now, ip_addr = :ip, app_version = :appv WHERE id = :id");
            $stmt->execute([':now'=>$now, ':ip'=>$ip, ':appv'=>$app_version, ':id'=>$device_id_db]);

            // Tạo link
            $redirect_link = create_redirect_link($pdo, $license_id, $device_id_db, $target_url, $expires_at);

            write_file_log('INFO','Device heartbeat', ['license'=>$token,'device'=>$device_id,'ip'=>$ip]); 
            json_response(['status'=>'ok','link'=>$redirect_link, 'message'=>'Heartbeat received, link provided']); 
        }
    } 
    
    // Logic PING và DEREGISTER giữ nguyên
    else if ($event === 'ping') {
        if ($device) {
            // Cập nhật last_seen
            $device_id_db = (int)$device['id'];
            $stmt = $pdo->prepare("UPDATE devices SET last_seen = :now, ip_addr = :ip, app_version = :appv WHERE id = :id");
            $stmt->execute([':now'=>$now, ':ip'=>$ip, ':appv'=>$app_version, ':id'=>$device_id_db]);

            write_file_log('INFO','Device heartbeat', ['license'=>$token,'device'=>$device_id,'ip'=>$ip]); 
            json_response(['status'=>'ok','message'=>'Heartbeat received']); 
        } else {
            write_file_log('WARN','Unregistered device ping', ['license'=>$token,'device'=>$device_id]);
            json_response(['status'=>'error','message'=>'Device not registered. Please run REGISTER first.'], 404);
        }
    }
    
    if ($event === 'deregister') {
        $d = $pdo->prepare("DELETE FROM devices WHERE license_id = :lid AND device_id = :did");
        $d->execute([':lid'=>$license_id, ':did'=>$device_id]);
        write_file_log('INFO','Device deregistered', ['license'=>$token,'device'=>$device_id]);
        json_response(['status'=>'ok','message'=>'Deregistered (if existed)']);
    }

} catch (Exception $e) {
    write_file_log('FATAL','API exception', ['message'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()]);
    json_response(['status'=>'error','message'=>'Internal server error'], 500);
}

// Hàm tạo redirect link (giữ nguyên)
function create_redirect_link(PDO $pdo, int $license_id, int $device_id_db, string $target_url, ?string $expires_at): string {
    $token = bin2hex(random_bytes(16)); // Tạo token ngẫu nhiên 32 ký tự
    $exp = $expires_at ?: (new DateTime('+1 year', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO redirect_links (token, license_id, device_id, target_url, expires_at) VALUES (:t, :lid, :did, :url, :exp)");
    $stmt->execute([':t'=>$token, ':lid'=>$license_id, ':did'=>$device_id_db, ':url'=>$target_url, ':exp'=>$exp]);

    return BASE_URL . "/licenses.php?t=" . $token; // Giả định BASE_URL
}

function json_response(array $data, int $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    // Bỏ qua JSON_PRETTY_PRINT để tiết kiệm bandwidth
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    exit;
}