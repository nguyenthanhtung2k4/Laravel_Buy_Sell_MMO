<?php
// File: index.php
require 'config.php';
check_db_connection();

// Lấy dữ liệu thống kê từ CSDL
try {
    // Tổng số Chữ ký số
    $totalSignatures = $pdo->query("SELECT COUNT(*) FROM signatures")->fetchColumn();
    
    // Tổng số Token đang hoạt động
    $activeTokens = $pdo->query("SELECT COUNT(*) FROM tokens WHERE is_active = TRUE")->fetchColumn();
    
    // Tổng truy cập thành công gần đây (24h qua)
    $recentAccesses = $pdo->query("SELECT COUNT(*) FROM access_log WHERE status = 'SUCCESS' AND access_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn();
    
} catch (\PDOException $e) {
    $totalSignatures = 'Lỗi';
    $activeTokens = 'Lỗi';
    $recentAccesses = 'Lỗi';
    error_log("Database stats error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Kiểm soát Truy cập - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php echo generate_navbar(); ?>

    <div class="container mt-5">
        <h1 class="mb-4 text-primary">Bảng Điều Khiển Tổng Quan</h1>
        <p class="lead">Chào mừng đến với hệ thống quản lý token và chữ ký số bảo mật.</p>
        
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Tổng số Chữ ký số</h5>
                        <p class="card-text display-4" id="totalSignatures"><?php echo $totalSignatures; ?></p>
                        <a href="management.php#signatures" class="btn btn-primary">Quản lý Chữ ký</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">Tổng số Token đang hoạt động</h5>
                        <p class="card-text display-4" id="activeTokens"><?php echo $activeTokens; ?></p>
                        <a href="management.php#tokens" class="btn btn-success">Quản lý Token</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-info">
                    <div class="card-body">
                        <h5 class="card-title text-info">Truy cập thành công (24h)</h5>
                        <p class="card-text display-4" id="recentAccesses"><?php echo $recentAccesses; ?></p>
                        <a href="live_control.php" class="btn btn-info">Kiểm soát Trực tiếp</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 bg-white rounded shadow-sm">
            <h2 class="text-secondary">Thông tin Hệ thống & Thiết bị</h2>
            <p>Hệ thống đang hoạt động ổn định. Kiểm tra các trang quản lý để xem chi tiết.</p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Cấu hình API Endpoint:</strong> <code><?php echo BASE_URL; ?>api/api.php?action=access</code></li>
                <li class="list-group-item"><strong>Giới hạn thiết bị:</strong> <span class="badge bg-danger"><?php echo DEVICE_LIMIT; ?></span> thiết bị/Token</li>
                <li class="list-group-item"><strong>Môi trường:</strong> PHP <?php echo phpversion(); ?></li>
            </ul>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
