<?php
// file: index.php
require_once __DIR__ . '/db.php';

// Yêu cầu đăng nhập cơ bản cho admin (Nếu chưa có trong db.php)
if (!isset($_SERVER['PHP_AUTH_USER']) || !($_SERVER['PHP_AUTH_USER'] === ADMIN_USER && $_SERVER['PHP_AUTH_PW'] === ADMIN_PASS)) {
    header('WWW-Authenticate: Basic realm="License Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

// Bổ sung hàm h() nếu chưa có trong db.php
if (!function_exists('h')) {
    function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

$title = "Tổng quan"; // Định nghĩa $title trước khi gọi header.php

// Threshold cho cảnh báo (ví dụ: 80% giới hạn thiết bị)
define('DEVICE_LIMIT_WARNING_THRESHOLD', 0.80); 

// gather stats
try {
    $pdo = get_pdo();

    // 1. Thống kê Licenses/Devices
    $r = $pdo->query("SELECT COUNT(*) AS cnt FROM licenses");
    $totalLicenses = (int)$r->fetchColumn();
    
    $r = $pdo->query("SELECT COUNT(*) AS cnt FROM licenses WHERE revoked = 0");
    $activeLicenses = (int)$r->fetchColumn();

    $r = $pdo->query("SELECT COUNT(*) AS cnt FROM devices");
    $totalDevices = (int)$r->fetchColumn();
    
    // Tổng số Devices bị xóa trong 24h gần nhất
    $one_day_ago = (new DateTime('-1 day', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
    $r = $pdo->prepare("SELECT COUNT(*) FROM access_logs WHERE level = 'WARN' AND message LIKE 'Device deleted by Admin' AND created_at >= :cutoff");
    $r->execute([':cutoff' => $one_day_ago]);
    $deletedDevices24h = (int)$r->fetchColumn();

    // Thống kê Devices quá hạn (last_seen)
    $inactive_days = defined('INACTIVE_DAYS') ? INACTIVE_DAYS : 30;
    $cutoff_dt = new DateTime("-$inactive_days days", new DateTimeZone('UTC'));
    $cutoff = $cutoff_dt->format('Y-m-d H:i:s');
    
    $r = $pdo->prepare("SELECT COUNT(*) AS cnt FROM devices WHERE last_seen < :cutoff");
    $r->execute([':cutoff' => $cutoff]);
    $inactiveDevices = (int)$r->fetchColumn();

    // 2. Thống kê HMACs
    $r = $pdo->query("SELECT COUNT(*) AS cnt FROM hmacs");
    $totalHMACs = (int)$r->fetchColumn();
    
    $r = $pdo->query("SELECT COUNT(*) AS cnt FROM hmacs WHERE is_active = 0");
    $inactiveHMACs = (int)$r->fetchColumn();
    
    // 3. Cảnh báo Tokens sắp đạt giới hạn (80%)
    $warningTokens = $pdo->query("
        SELECT 
            l.license_key, 
            l.max_devices, 
            COUNT(d.id) AS current_devices
        FROM licenses l
        LEFT JOIN devices d ON l.id = d.license_id
        WHERE l.revoked = 0 
        GROUP BY l.id
        HAVING current_devices >= l.max_devices * " . DEVICE_LIMIT_WARNING_THRESHOLD . " AND current_devices < l.max_devices
        ORDER BY (current_devices / l.max_devices) DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 4. Recent Devices
    $recentDevices = $pdo->query("
        SELECT 
            d.device_id, 
            d.device_name, 
            d.os_info, 
            d.registered_at, 
            l.license_key, 
            l.id AS license_id 
        FROM devices d
        JOIN licenses l ON d.license_id = l.id
        ORDER BY d.registered_at DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);


} catch (Exception $e) {
    // Nếu có lỗi DB (ví dụ: bảng chưa được tạo)
    $error = "Lỗi cơ sở dữ liệu: " . h($e->getMessage()) . ". Vui lòng kiểm tra lại file `db.sql` đã được import chưa.";
}

require_once 'header.php';
?>

<h2 class="text-2xl font-semibold text-gray-800 mb-6">Thống kê Tổng quan</h2>

<?php if (isset($error) && !empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">LỖI!</strong>
        <span class="block sm:inline"> <?= $error ?></span>
    </div>
<?php endif; ?>

<?php if (isset($inactiveHMACs) && $inactiveHMACs > 0): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">CẢNH BÁO LỚN - HMAC!</strong>
        <span class="block sm:inline">Có **<?= $inactiveHMACs ?>** HMAC Key đang bị **VÔ HIỆU HÓA**. Tất cả Tokens sử dụng HMAC đó sẽ không thể xác thực. Vui lòng kiểm tra trang <a href="hmacs.php" class="font-semibold underline">Quản lý HMACs</a>.</span>
    </div>
<?php endif; ?>

<?php if (!empty($warningTokens)): ?>
    <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">CẢNH BÁO - GIỚI HẠN THIẾT BỊ!</strong>
        <span class="block sm:inline">
            Có **<?= count($warningTokens) ?>** Tokens đang **sắp đạt giới hạn thiết bị** (>= <?= DEVICE_LIMIT_WARNING_THRESHOLD * 100 ?>% Max Devices). 
            Vui lòng xem chi tiết bên dưới:
            <ul class="list-disc list-inside mt-2">
                <?php foreach ($warningTokens as $wt): ?>
                    <li>
                        <a href="admin.php?detail=<?= h($wt['license_key']) ?>" class="font-semibold underline hover:text-orange-900">
                            <?= h($wt['license_key']) ?>
                        </a>: <?= $wt['current_devices'] ?> / <?= $wt['max_devices'] ?> Devices.
                    </li>
                <?php endforeach; ?>
            </ul>
        </span>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="card bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
        <p class="text-sm font-medium text-gray-500">Tổng Tokens</p>
        <p class="text-3xl font-bold text-gray-900"><?= $totalLicenses ?? 0 ?></p>
        <p class="text-xs text-gray-500"><?= $activeLicenses ?? 0 ?> đang Active</p>
    </div>
    
    <div class="card bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
        <p class="text-sm font-medium text-gray-500">Tổng Thiết bị</p>
        <p class="text-3xl font-bold text-gray-900"><?= $totalDevices ?? 0 ?></p>
        <p class="text-xs text-gray-500"><?= $activeDevices ?? 0 ?> Active (7 ngày)</p>
    </div>
    
    <div class="card bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500">
        <p class="text-sm font-medium text-gray-500">Tổng HMAC Keys</p>
        <p class="text-3xl font-bold text-gray-900"><?= $totalHMACs ?? 0 ?></p>
        <p class="text-xs text-gray-500"><?= $inactiveHMACs ?? 0 ?> đang Inactive</p>
    </div>
    
    <div class="card bg-white p-6 rounded-lg shadow-lg border-l-4 border-yellow-500">
        <p class="text-sm font-medium text-gray-500">Devices Inactive</p>
        <p class="text-3xl font-bold text-gray-900"><?= $inactiveDevices ?? 0 ?></p>
        <p class="text-xs text-gray-500">Sẽ bị xóa sau <?= $inactive_days ?> ngày</p>
    </div>
    
</div>

<h3 class="text-xl font-bold text-gray-800 mb-4">10 Thiết bị đăng ký gần nhất</h3>
<div class="overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID (Rút gọn)</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên/OS</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token</th>
                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đăng ký (UTC)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php $i=0; foreach ($recentDevices as $d): $i++; ?>
                <tr class="<?= $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-gray-100">
                    <td class="py-3 px-4 text-sm font-mono text-gray-900 truncate" title="<?= h($d['device_id']) ?>"><?= h(substr($d['device_id'], 0, 8)) ?>...</td>
                    <td class="py-3 px-4 text-sm text-gray-600"><?= h($d['device_name'] ?: 'N/A') ?> (<?= h($d['os_info'] ?: 'N/A') ?>)</td>
                    <td class="py-3 px-4 text-sm font-medium text-blue-600">
                        <a href="admin.php?detail=<?= h($d['license_key']) ?>" class="hover:underline"><?= h($d['license_key']) ?></a>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-500"><?= date('Y-m-d H:i:s', strtotime($d['registered_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>