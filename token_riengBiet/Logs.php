<?php
// file: logs.php - Dedicated Log Viewer
require_once __DIR__ . '/db.php';

// Cần đảm bảo hàm h() và write_file_log() đã được định nghĩa trong db.php

$pdo = get_pdo();
// Yêu cầu đăng nhập cơ bản cho admin (Nếu chưa có ở header.php)
if (!isset($_SERVER['PHP_AUTH_USER']) || !($_SERVER['PHP_AUTH_USER'] === ADMIN_USER && $_SERVER['PHP_AUTH_PW'] === ADMIN_PASS)) {
    header('WWW-Authenticate: Basic realm="License Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

$title = "Kiểm soát & Logs";

$level_filter = $_GET['level'] ?? 'ALL';
$search_ip = $_GET['ip'] ?? '';
$search_message = $_GET['search'] ?? '';
$limit = 100; // Giới hạn số lượng log hiển thị

$where_clauses = [];
$params = [];

// Lọc theo Level
if ($level_filter !== 'ALL') {
    $where_clauses[] = "level = :level";
    $params[':level'] = $level_filter;
}

// Lọc theo IP
if (!empty($search_ip)) {
    $where_clauses[] = "ip = :ip";
    $params[':ip'] = $search_ip;
}

// Lọc theo Message
if (!empty($search_message)) {
    // Sửa dụng LIKE để tìm kiếm
    $where_clauses[] = "message LIKE :search_msg";
    $params[':search_msg'] = '%' . $search_message . '%';
}

$where_sql = count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

try {
    // Đảm bảo get_pdo() trả về một đối tượng PDO hợp lệ
    $stmt = $pdo->prepare("SELECT id, level, message, meta, ip, created_at FROM access_logs {$where_sql} ORDER BY created_at DESC LIMIT {$limit}");
    $stmt->execute($params);
    $logs = $stmt->fetchAll();

} catch (Exception $e) {
    $error = "Database Error: " . h($e->getMessage());
    $logs = [];
    write_file_log('FATAL', 'Database query failed in logs.php', ['error'=>$e->getMessage()]);
}

// Giả định header.php chứa các tag HTML mở, CSS Tailwind, và hàm h()
require_once 'header.php'; 

?>

<h2 class="text-2xl font-semibold text-gray-800 mb-6">Logs Truy cập Hệ thống (<?= count($logs) ?> logs)</h2>

<form method="GET" class="bg-gray-100 p-4 rounded-lg shadow-inner mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label for="level" class="block text-sm font-medium text-gray-700">Lọc theo Level:</label>
        <select name="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
            <option value="ALL" <?= $level_filter === 'ALL' ? 'selected' : '' ?>>Tất cả Levels</option>
            <option value="INFO" <?= $level_filter === 'INFO' ? 'selected' : '' ?>>INFO</option>
            <option value="WARN" <?= $level_filter === 'WARN' ? 'selected' : '' ?>>WARN</option>
            <option value="ERROR" <?= $level_filter === 'ERROR' ? 'selected' : '' ?>>ERROR</option>
            <option value="CRITICAL" <?= $level_filter === 'CRITICAL' ? 'selected' : '' ?>>CRITICAL</option>
            <option value="FATAL" <?= $level_filter === 'FATAL' ? 'selected' : '' ?>>FATAL</option>
        </select>
    </div>
    <div>
        <label for="ip" class="block text-sm font-medium text-gray-700">Lọc theo IP:</label>
        <input type="text" name="ip" id="ip" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border w-40" value="<?= h($search_ip) ?>" placeholder="e.g. 192.168.1.1">
    </div>
    <div>
        <label for="search" class="block text-sm font-medium text-gray-700">Tìm kiếm Message:</label>
        <input type="text" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border w-64" value="<?= h($search_message) ?>" placeholder="e.g. Invalid token">
    </div>
    <button type="submit" class="self-end bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 h-[42px]">Lọc Logs</button>
    <a href="logs.php" class="self-end bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded hover:bg-gray-400 h-[42px] leading-[22px]">Reset</a>
</form>

<?php if (isset($error)): // Hiển thị lỗi Database ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Lỗi Database!</strong>
        <span class="block sm:inline"> <?= $error ?></span>
    </div>
<?php endif; ?>

<?php if (empty($logs)): ?>
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Thông báo!</strong>
        <span class="block sm:inline"> Không tìm thấy Log nào phù hợp với điều kiện lọc.</span>
    </div>
<?php else: ?>
    <div class="overflow-x-auto bg-gray-900 text-white rounded-lg shadow max-h-[70vh]">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-800 sticky top-0">
                <tr>
                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-32">Thời gian (UTC)</th>
                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-20">Level</th>
                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-28">IP</th>
                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Message</th>
                    <th class="py-2 px-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-64">Metadata (JSON)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach ($logs as $log): 
                    $level = strtoupper($log['level']);
                    $class = 'text-gray-200'; // Default
                    
                    if ($level === 'INFO') $class = 'text-sky-400';
                    else if ($level === 'WARN') $class = 'text-yellow-400';
                    else if ($level === 'ERROR') $class = 'text-orange-400';
                    else if ($level === 'CRITICAL') $class = 'text-red-500 font-bold';
                    // FATAL hoặc ALERT nổi bật hơn
                    else if ($level === 'FATAL' || $level === 'ALERT') $class = 'text-red-400 font-extrabold bg-gray-700/50';
                ?>
                    <tr class="hover:bg-gray-800">
                        <td class="py-1 px-4 text-xs font-mono text-gray-400"><?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?></td>
                        <td class="py-1 px-4 text-xs font-semibold <?= $class ?>"><?= $level ?></td>
                        <td class="py-1 px-4 text-xs text-gray-500 font-mono"><?= h($log['ip'] ?? 'N/A') ?></td>
                        <td class="py-1 px-4 text-xs text-white"><?= h($log['message']) ?></td>
                        <td class="py-1 px-4 text-xs text-green-300 font-mono max-w-xs overflow-hidden truncate" title="<?= h($log['meta'] ?? '[]') ?>"><?= h($log['meta'] ?? '[]') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>