<?php
// file: admin.php (Device Management & License Detail)
require_once __DIR__ . '/db.php';

$pdo = get_pdo();
$title = "Quản lý Chi tiết & Thiết bị";
$message = '';
$error = '';
$k = $_GET['detail'] ?? null; // License key

// ... (Giữ nguyên phần xử lý POST: revoke, activate, delete_device)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = $_POST['op'] ?? '';
    $k = $_POST['token'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    $device_id_val = $_POST['device_id_val'] ?? '';

    if ($op === 'revoke') {
        $stmt = $pdo->prepare("UPDATE licenses SET revoked = 1 WHERE license_key = :k");
        $stmt->execute([':k'=>$k]);
        write_file_log('CRITICAL', 'License revoked by Admin', ['key'=>$k]);
        $message = "Đã thu hồi (Revoke) Token $k thành công!";
    }
    else if ($op === 'activate') {
        $stmt = $pdo->prepare("UPDATE licenses SET revoked = 0 WHERE license_key = :k");
        $stmt->execute([':k'=>$k]);
        write_file_log('INFO', 'License activated by Admin', ['key'=>$k]);
        $message = "Đã kích hoạt lại (Activate) Token $k thành công!";
    }
    else if ($op === 'delete_device') {
        $device_id = (int)($_POST['device_id'] ?? 0);
        $device_name = $_POST['device_name'] ?? 'N/A';
        $k_for_log = $_POST['license_key'] ?? 'N/A';

        $stmt = $pdo->prepare("DELETE FROM devices WHERE id = :id");
        $stmt->execute([':id'=>$device_id]);
        write_file_log('WARN', 'Device deleted by Admin', ['license_key'=>$k_for_log, 'device_id'=>$device_id_val, 'device_name'=>$device_name]);
        $message = "Đã xóa thiết bị **$device_name** (ID: $device_id_val) thành công!";
    }
    // Chuyển hướng để tránh lỗi submit form lại và cập nhật query string
    header("Location: admin.php?detail=" . urlencode($k) . "&m=" . urlencode($message));
    exit;
}

// Lấy message từ query string nếu có
if (isset($_GET['m'])) {
    $message = $_GET['m'];
}

require_once 'header.php';

// Hiển thị chi tiết License
$license_details = null;
$devices = [];

if ($k) {
    // TÌM LICENSE VÀ JOIN VỚI HMACs
    $l_stmt = $pdo->prepare("
        SELECT 
            l.*, 
            h.name AS hmac_name, 
            h.hmac_secret,
            h.is_active AS hmac_active,
            h.description AS hmac_desc
        FROM licenses l
        JOIN hmacs h ON l.hmac_id = h.id
        WHERE l.license_key = :k 
        LIMIT 1
    ");
    $l_stmt->execute([':k'=>$k]);
    $license_details = $l_stmt->fetch(PDO::FETCH_ASSOC);

    if ($license_details) {
        $devices = $pdo->prepare("SELECT * FROM devices WHERE license_id = :lid ORDER BY last_seen DESC");
        $devices->execute([':lid' => $license_details['id']]);
        $devices = $devices->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!$k || !$license_details): ?>
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Lưu ý!</strong>
        <span class="block sm:inline">Vui lòng cung cấp một License Key hợp lệ để xem chi tiết.</span>
    </div>
<?php else: 
    $l = $license_details;
    $is_revoked = (bool)$l['revoked'];
    $is_expired = $l['expires_at'] && strtotime($l['expires_at']) < time();
    $hmac_inactive = $l['hmac_active'] != 1;
?>

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Chi tiết Token: **<?= h($l['license_key']) ?>**</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-indigo-600">Thông tin chung</h3>
            <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <dt class="font-medium text-gray-500">Trạng thái:</dt>
                <dd class="font-semibold">
                    <?php if ($is_revoked): ?>
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">REVOKED</span>
                    <?php elseif ($hmac_inactive): ?>
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">HMAC INACTIVE</span>
                    <?php elseif ($is_expired): ?>
                        <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">EXPIRED</span>
                    <?php else: ?>
                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">ACTIVE</span>
                    <?php endif; ?>
                </dd>
                
                <dt class="font-medium text-gray-500">ID:</dt>
                <dd class="text-gray-900"><?= h($l['id']) ?></dd>
                
                <dt class="font-medium text-gray-500">Max Devices:</dt>
                <dd class="text-gray-900"><?= count($devices) ?> / <?= h($l['max_devices']) ?></dd>

                <dt class="font-medium text-gray-500">Ngày Tạo:</dt>
                <dd class="text-gray-900"><?= date('Y-m-d H:i:s', strtotime($l['created_at'])) ?></dd>

                <dt class="font-medium text-gray-500">Ngày Hết hạn:</dt>
                <dd class="text-gray-900"><?= $l['expires_at'] ? date('Y-m-d H:i:s', strtotime($l['expires_at'])) : 'Vĩnh viễn' ?></dd>
                
                <dt class="font-medium text-gray-500">Target URL:</dt>
                <dd class="text-blue-600 truncate"><a href="<?= h($l['target_url']) ?>" target="_blank" title="<?= h($l['target_url']) ?>"><?= h($l['target_url']) ?></a></dd>
            </dl>
            
            <div class="mt-4 pt-4 border-t">
                 <a href="licenses.php?op=edit&id=<?= h($l['id']) ?>" class="text-blue-600 hover:text-blue-800 mr-3 text-sm">Sửa thông tin Token</a>
                <?php if ($is_revoked): ?>
                    <form method="post" style="display:inline" onsubmit="return confirm('Kích hoạt lại license này?')">
                        <input type="hidden" name="op" value="activate">
                        <input type="hidden" name="token" value="<?=h($l['license_key'])?>">
                        <button class="text-green-600 hover:underline text-sm">Activate</button>
                    </form>
                <?php else: ?>
                    <form method="post" style="display:inline" onsubmit="return confirm('Thu hồi license này?')">
                        <input type="hidden" name="op" value="revoke">
                        <input type="hidden" name="token" value="<?=h($l['license_key'])?>">
                        <button class="text-red-600 hover:underline text-sm">Revoke</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-purple-600">Thông tin HMAC Key</h3>
            <dl class="grid grid-cols-1 gap-y-2 text-sm">
                <dt class="font-medium text-gray-500">Tên HMAC / ID:</dt>
                <dd class="text-gray-900 font-semibold">
                    <a href="hmacs.php?op=edit&id=<?= h($l['hmac_id']) ?>" class="text-purple-600 hover:underline">
                        <?= h($l['hmac_name'] ?? 'N/A') ?> (ID: <?= h($l['hmac_id']) ?>)
                    </a>
                </dd>

                <dt class="font-medium text-gray-500">HMAC Secret:</dt>
                <dd class="text-yellow-700 font-mono text-xs break-all cursor-pointer" onclick="this.select(); document.execCommand('copy'); alert('Đã sao chép HMAC Secret!')">
                    <?= h($l['hmac_secret'] ?? '[KEY REDACTED]') ?>
                </dd>
                
                <dt class="font-medium text-gray-500 mt-2">Trạng thái HMAC:</dt>
                <dd class="font-semibold">
                    <?php if ($l['hmac_active']): ?>
                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Hoạt động</span>
                    <?php else: ?>
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">VÔ HIỆU HÓA</span>
                        <p class="text-xs text-red-500 mt-1">Token này không thể xác thực!</p>
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
    </div>


    <h3 class="text-xl font-bold mb-4 border-b pb-2 text-gray-800">Danh sách Thiết bị (<?= count($devices) ?>)</h3>
    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device ID (Rút gọn)</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Thiết bị/OS</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP/App Version</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lần cuối (UTC)</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($devices)): ?>
                    <tr class="bg-white">
                        <td colspan="5" class="py-4 px-4 text-sm text-center text-gray-500">Chưa có thiết bị nào đăng ký với Token này.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($devices as $d): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-4 text-sm font-mono text-gray-900" title="<?= h($d['device_id']) ?>">
                            <?= h(substr($d['device_id'], 0, 8)) ?>... (DB ID: <?= $d['id'] ?>)
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-700">
                            <?= h($d['device_name'] ?: 'N/A') ?> / <?= h($d['os_info'] ?: 'N/A') ?>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-500">
                            <?= h($d['ip_addr'] ?: 'N/A') ?> (App: <?= h($d['app_version'] ?: 'N/A') ?>)
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-500">
                            <?= date('Y-m-d H:i:s', strtotime($d['last_seen'])) ?>
                        </td>
                        <td class="py-4 px-4 text-sm font-medium">
                            <form method="POST" style="display:inline" onsubmit="return confirm('Xác nhận xóa thiết bị <?= h($d['device_name']) ?> (<?= h(substr($d['device_id'], 0, 8)) ?>...)?')">
                                <input type="hidden" name="op" value="delete_device">
                                <input type="hidden" name="device_id" value="<?= h($d['id']) ?>">
                                <input type="hidden" name="device_id_val" value="<?= h(substr($d['device_id'], 0, 8)) ?>">
                                <input type="hidden" name="device_name" value="<?= h($d['device_name'] ?: 'N/A') ?>">
                                <input type="hidden" name="token" value="<?= h($l['license_key']) ?>">
                                <button class="text-red-600 hover:text-red-800" type="submit">Xóa Device</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>