<?php
// File: live_control.php
require 'config.php';
check_db_connection();

// Lấy nhật ký truy cập
$log_query = "SELECT * FROM access_log ORDER BY access_time DESC LIMIT 50";
$logs = $pdo->query($log_query)->fetchAll();

// Lấy số liệu thống kê nhanh
$stats = $pdo->query("
    SELECT 
        SUM(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) as total_success,
        SUM(CASE WHEN status LIKE 'FAILED%' THEN 1 ELSE 0 END) as total_failed
    FROM access_log
")->fetch();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm soát Trực tiếp & Nhật ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="5"> <!-- Tự động làm mới trang sau mỗi 5 giây -->
</head>
<body class="bg-light">
    <?php echo generate_navbar(); ?>
    
    <div class="container mt-5">
        <h1 class="mb-4 text-info">Kiểm soát Trực tiếp & Nhật ký Truy cập</h1>
        <p class="lead">Trang này tự động làm mới (mỗi 5s) để hiển thị các yêu cầu truy cập mới nhất.</p>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="alert alert-success">
                    <strong>Tổng truy cập Thành công:</strong> <?php echo $stats['total_success'] ?? 0; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <strong>Tổng truy cập Thất bại:</strong> <?php echo $stats['total_failed'] ?? 0; ?>
                </div>
            </div>
        </div>

        <!-- Bảng Nhật ký Truy cập -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle bg-white shadow-sm">
                <thead class="table-primary">
                    <tr>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Token</th>
                        <th>IP & Thiết bị</th>
                        <th>Đường link Dữ liệu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): 
                        $badge_class = 'bg-secondary';
                        if ($log['status'] === 'SUCCESS') {
                            $badge_class = 'bg-success';
                        } elseif (strpos($log['status'], 'FAILED') !== false) {
                            $badge_class = 'bg-danger';
                        }
                    ?>
                        <tr>
                            <td><?php echo date('H:i:s d/m', strtotime($log['access_time'])); ?></td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($log['status']); ?></span></td>
                            <td><code class="text-break"><?php echo htmlspecialchars($log['token_value']); ?></code></td>
                            <td><?php echo htmlspecialchars($log['ip_address']); ?> / <?php echo htmlspecialchars($log['user_identifier']); ?></td>
                            <td>
                                <?php if ($log['data_link']): ?>
                                    <span class="text-success">Link Dữ liệu (Đã dùng)</span>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="text-muted text-center mt-3">Hiển thị 50 log gần nhất. Trang tự động làm mới.</p>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
