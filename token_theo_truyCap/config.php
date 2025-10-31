<?php
// File: config.php
// Thiết lập kết nối cơ sở dữ liệu
// LƯU Ý: Thay thế các thông tin dưới đây bằng thông tin MySQL của bạn

$host = 'localhost';
$db   = 'key_db'; // Thay bằng tên database của bạn
$user = 'root'; // Thay bằng user của bạn
$pass = 'tung2004'; // Thay bằng password của bạn
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Thiết lập biến môi trường
define('BASE_URL', 'http://localhost/nt/'); // Đổi thành URL gốc của dự án của bạn
define('DEVICE_LIMIT', 5); // Giới hạn thiết bị tối đa

/**
 * Hàm kiểm tra và chuyển hướng nếu chưa có kết nối CSDL (chỉ dùng cho giao diện quản trị)
 */
function check_db_connection() {
    global $pdo;
    if (!isset($pdo)) {
        echo "<div class='container mt-5'><div class='alert alert-danger' role='alert'>
              Lỗi kết nối CSDL! Vui lòng kiểm tra file config.php và đảm bảo database đã được tạo.
              </div></div>";
        exit;
    }
}

/**
 * Hàm tạo Navbar dùng chung (Đã gộp từ navbar.php)
 */
function generate_navbar() {
    return '
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Secure Access System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="management.php">Quản lý Chữ ký & Token</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="live_control.php">Kiểm soát Trực tiếp</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>';
}

/**
 * Hàm tạo UUID đơn giản
 */
function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
?>
