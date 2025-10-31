<?php
// file: db.php
declare(strict_types=1);
date_default_timezone_set('UTC');

// ---------- CONFIG - chỉnh theo môi trường ----------
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'license_db');
define('DB_USER', 'root');     // <-- đổi
define('DB_PASS', 'tung2004');     // <-- đổi

define('BASE_URL', 'http://localhost/ADMINKEY/'); // <-- đổi (không có slash cuối)
define('ADMIN_USER', 'admin');           // <-- đổi
define('ADMIN_PASS', 'admin123');  // <-- đổi

define('INACTIVE_DAYS', 30);             // thiết bị không active trong X ngày sẽ bị xóa
define('DEFAULT_MAX_DEVICES', 5);

// Logging files & options
define('LOG_DIR', __DIR__ . '/logs');
define('LOG_FILE', LOG_DIR . '/payload_access.log');
if (!is_dir(LOG_DIR)) @mkdir(LOG_DIR, 0755, true);

// redirect behavior
define('ONE_TIME_LINK', true); // true = link chỉ dùng 1 lần

// ---------------------------------------------------
function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function json_response($data, $status=200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// HÀM HELPER: Dùng để thoát ký tự HTML (Nếu hàm này đã được định nghĩa ở nơi khác, sẽ không định nghĩa lại)
if (!function_exists('h')) {
    function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

// --- HÀM MỚI: Lấy IP Address an toàn ---
function get_ip(): string {
    // Kiểm tra REMOTE_ADDR trước
    return $_SERVER['REMOTE_ADDR'] ?? 'CLI';
}

// --- HÀM CẢI TIẾN: Ghi log vào File VÀ Database ---
// Thêm hàm h() để sử dụng nhất quán
if (!function_exists('h')) {
    function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

// Hàm ghi log kép: ra file và vào database
function write_file_log(string $level, string $message, array $meta = []) {
    // 1. Ghi ra file log (để debug nhanh)
    $log_line = sprintf(
        "[%s] [%s] [%s] %s %s\n",
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'] ?? 'CLI', // IP
        strtoupper($level), // LEVEL
        $message, // MESSAGE
        json_encode($meta, JSON_UNESCAPED_UNICODE) // META
    );
    // Ghi file log không gây lỗi nếu đường dẫn sai (dựa trên LOG_FILE đã định nghĩa)
    @file_put_contents(LOG_FILE, $log_line, FILE_APPEND);

    // 2. Ghi vào database (để logs.php hiển thị)
    try {
        $pdo = get_pdo();
        $stmt = $pdo->prepare("INSERT INTO access_logs (level, message, meta, ip) VALUES (:lvl, :msg, :meta, :ip)");
        
        $meta_json = json_encode($meta, JSON_UNESCAPED_UNICODE);
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        
        $stmt->execute([
            ':lvl' => strtoupper($level),
            ':msg' => $message,
            ':meta' => $meta_json,
            ':ip' => $ip,
        ]);
    } catch (Throwable $e) {
        // Nếu lỗi database khi ghi log, chỉ ghi lỗi ra file và không crash hệ thống
        $error_line = sprintf("[FATAL] Database Logging Failed: %s\n", $e->getMessage());
        @file_put_contents(LOG_FILE, $error_line, FILE_APPEND);
    }
}