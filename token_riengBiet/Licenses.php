<?php
// file: licenses.php
require_once __DIR__ . '/db.php';

// ... (Giữ nguyên phần xác thực admin, hàm h(), write_file_log())
if (!isset($_SERVER['PHP_AUTH_USER']) || !($_SERVER['PHP_AUTH_USER'] === ADMIN_USER && $_SERVER['PHP_AUTH_PW'] === ADMIN_PASS)) {
    header('WWW-Authenticate: Basic realm="License Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

$pdo = get_pdo();

if (!function_exists('h')) {
    function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

$op = $_GET['op'] ?? '';
$id = (int)($_GET['id'] ?? 0);
$message = '';
$error = '';

// Lấy danh sách HMACs để sử dụng trong form (Select Box)
$hmacs_list = $pdo->query("SELECT id, name, is_active FROM hmacs ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);


// --- Xử lý POST (Thêm, Sửa, Xóa Token) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_op = $_POST['op'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    
    try {
        if ($post_op === 'add_license') {
            $key = trim($_POST['license_key'] ?? bin2hex(random_bytes(16)));
            $hmac_id = (int)($_POST['hmac_id'] ?? 0);
            $max_devices = (int)($_POST['max_devices'] ?? DEFAULT_MAX_DEVICES);
            $expires_at = empty($_POST['expires_at']) ? null : $_POST['expires_at'];
            $target_url = empty($_POST['target_url']) ? null : $_POST['target_url'];

            if (empty($key)) { throw new Exception("License Key không được để trống."); }
            if ($hmac_id <= 0) { throw new Exception("Vui lòng chọn HMAC Key."); }

            $stmt = $pdo->prepare("INSERT INTO licenses (license_key, hmac_id, max_devices, expires_at, target_url) VALUES (:k, :hid, :md, :exp, :url)");
            $stmt->execute([':k' => $key, ':hid' => $hmac_id, ':md' => $max_devices, ':exp' => $expires_at, ':url' => $target_url]);
            write_file_log('INFO', 'License added', ['key' => $key, 'hmac_id' => $hmac_id]);
            $message = "Token **{$key}** đã được tạo thành công.";
        }
        else if ($post_op === 'edit_license') {
            $hmac_id = (int)($_POST['hmac_id'] ?? 0);
            $max_devices = (int)($_POST['max_devices'] ?? DEFAULT_MAX_DEVICES);
            $expires_at = empty($_POST['expires_at']) ? null : $_POST['expires_at'];
            $target_url = empty($_POST['target_url']) ? null : $_POST['target_url'];
            
            if ($hmac_id <= 0) { throw new Exception("Vui lòng chọn HMAC Key."); }

            $stmt = $pdo->prepare("UPDATE licenses SET hmac_id = :hid, max_devices = :md, expires_at = :exp, target_url = :url WHERE id = :id");
            $stmt->execute([':hid' => $hmac_id, ':md' => $max_devices, ':exp' => $expires_at, ':url' => $target_url, ':id' => $id]);
            write_file_log('INFO', 'License updated', ['id' => $id, 'hmac_id' => $hmac_id]);
            $message = "Token ID **{$id}** đã được cập nhật thành công.";
            $op = ''; // Quay lại trang danh sách
        }
        else if ($post_op === 'delete_license') {
            // Lấy key để ghi log
            $key_stmt = $pdo->prepare("SELECT license_key FROM licenses WHERE id = :id");
            $key_stmt->execute([':id' => $id]);
            $key = $key_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM licenses WHERE id = :id");
            $stmt->execute([':id' => $id]);
            write_file_log('CRITICAL', 'License deleted', ['key' => $key, 'id' => $id]);
            $message = "Token **{$key}** và tất cả thiết bị liên quan đã được xóa.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// --- Lấy dữ liệu ---
$licenses = [];
if ($op === '') {
    // JOIN với bảng hmacs để hiển thị Tên HMAC và trạng thái
    $licenses = $pdo->query("
        SELECT 
            l.*, 
            COUNT(d.id) AS current_devices, 
            h.name AS hmac_name, 
            h.is_active AS hmac_active
        FROM licenses l
        JOIN hmacs h ON l.hmac_id = h.id
        LEFT JOIN devices d ON l.id = d.license_id
        GROUP BY l.id
        ORDER BY l.id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
}


$title = "Quản lý Tokens (Licenses)";

require_once 'header.php';
?>

<h2 class="text-2xl font-semibold text-gray-800 mb-6"><?= h($title) ?></h2>

<?php if (empty($hmacs_list)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">LỖI THIẾU HMAC!</strong>
        <span class="block sm:inline">Vui lòng <a href="hmacs.php?op=add" class="font-semibold underline">tạo một HMAC Key</a> trước khi tạo Token.</span>
    </div>
<?php endif; ?>

<?php if ($op === 'add' && !empty($hmacs_list)): 
    $new_key = bin2hex(random_bytes(16));
    $default_expires = (new DateTime('+1 year'))->format('Y-m-d H:i:s');
?>
    <div class="max-w-xl mx-auto p-6 bg-gray-50 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4">Tạo Token (License) Mới</h3>
        <form method="POST">
            <input type="hidden" name="op" value="add_license">
            
            <div class="mb-4">
                <label for="license_key" class="block text-sm font-medium text-gray-700">License Key</label>
                <div class="flex">
                    <input type="text" name="license_key" id="license_key" value="<?= h($new_key) ?>" class="mt-1 block w-full border border-gray-300 rounded-l-md shadow-sm p-2">
                    <button type="button" onclick="document.getElementById('license_key').value = '<?= h(bin2hex(random_bytes(16))) ?>'" class="mt-1 px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-r-md hover:bg-indigo-700 transition">Tạo ngẫu nhiên</button>
                </div>
            </div>

            <div class="mb-4">
                <label for="hmac_id" class="block text-sm font-medium text-gray-700">HMAC Key liên kết <span class="text-red-500">*</span></label>
                <select name="hmac_id" id="hmac_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="">-- Chọn HMAC Key --</option>
                    <?php foreach ($hmacs_list as $h): ?>
                        <option value="<?= $h['id'] ?>">
                            <?= h($h['name']) ?> (ID: <?= $h['id'] ?>) <?= $h['is_active'] ? '' : ' - [INACTIVE]' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-1 text-xs text-gray-500">Token này sẽ sử dụng HMAC Secret Key của mục đã chọn để xác thực.</p>
            </div>
            
            <div class="flex space-x-4 mb-4">
                <div class="w-1/2">
                    <label for="max_devices" class="block text-sm font-medium text-gray-700">Max Devices</label>
                    <input type="number" name="max_devices" id="max_devices" value="<?= DEFAULT_MAX_DEVICES ?>" min="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="w-1/2">
                    <label for="expires_at" class="block text-sm font-medium text-gray-700">Ngày Hết hạn (YYYY-MM-DD HH:MM:SS)</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" step="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <p class="mt-1 text-xs text-gray-500">Để trống nếu muốn vĩnh viễn.</p>
                </div>
            </div>

            <div class="mb-4">
                <label for="target_url" class="block text-sm font-medium text-gray-700">Target URL (Redirect)</label>
                <input type="url" name="target_url" id="target_url" placeholder="https://yourdomain.com/data" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                <p class="mt-1 text-xs text-gray-500">URL sẽ được redirect sau khi xác thực thành công.</p>
            </div>

            <div class="flex justify-end">
                <a href="licenses.php" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Hủy</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Tạo Token
                </button>
            </div>
        </form>
    </div>

<?php elseif ($op === 'edit' && $id > 0): 
    $l_stmt = $pdo->prepare("SELECT * FROM licenses WHERE id = :id");
    $l_stmt->execute([':id' => $id]);
    $l = $l_stmt->fetch(PDO::FETCH_ASSOC);
    if (!$l) {
        $error = "Token không tồn tại.";
        $op = ''; 
    } else {
?>
    <div class="max-w-xl mx-auto p-6 bg-gray-50 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4">Sửa Token (License)</h3>
        <p class="text-lg font-mono text-gray-700 mb-4">Key: **<?= h($l['license_key']) ?>**</p>
        <form method="POST">
            <input type="hidden" name="op" value="edit_license">
            <input type="hidden" name="id" value="<?= h($l['id']) ?>">
            
            <div class="mb-4">
                <label for="hmac_id" class="block text-sm font-medium text-gray-700">HMAC Key liên kết <span class="text-red-500">*</span></label>
                <select name="hmac_id" id="hmac_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="">-- Chọn HMAC Key --</option>
                    <?php foreach ($hmacs_list as $h): ?>
                        <option value="<?= $h['id'] ?>" <?= (int)$l['hmac_id'] === (int)$h['id'] ? 'selected' : '' ?>>
                            <?= h($h['name']) ?> (ID: <?= $h['id'] ?>) <?= $h['is_active'] ? '' : ' - [INACTIVE]' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex space-x-4 mb-4">
                <div class="w-1/2">
                    <label for="max_devices" class="block text-sm font-medium text-gray-700">Max Devices</label>
                    <input type="number" name="max_devices" id="max_devices" value="<?= h($l['max_devices']) ?>" min="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="w-1/2">
                    <label for="expires_at" class="block text-sm font-medium text-gray-700">Ngày Hết hạn (YYYY-MM-DD HH:MM:SS)</label>
                    <?php 
                        $exp_dt = $l['expires_at'] ? date('Y-m-d\TH:i:s', strtotime($l['expires_at'])) : '';
                    ?>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="<?= $exp_dt ?>" step="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <p class="mt-1 text-xs text-gray-500">Để trống nếu muốn vĩnh viễn.</p>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="target_url" class="block text-sm font-medium text-gray-700">Target URL (Redirect)</label>
                <input type="url" name="target_url" id="target_url" value="<?= h($l['target_url']) ?>" placeholder="https://yourdomain.com/data" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <div class="flex justify-between items-center">
                <a href="licenses.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Quay lại</a>
                <a href="admin.php?detail=<?= h($l['license_key']) ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-yellow-400 hover:bg-yellow-500">Quản lý Devices</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lưu Thay đổi
                </button>
            </div>
        </form>
    </div>
<?php } ?>

<?php else: ?>
    <div class="flex justify-between items-center mb-4">
        <p class="text-gray-600">Tổng số Tokens: **<?= count($licenses) ?>**</p>
        <a href="licenses.php?op=add" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            + Tạo Token Mới
        </a>
    </div>

    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Key</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HMAC Key</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devices (Max)</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hết hạn</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($licenses as $l): ?>
                    <?php 
                        $is_revoked = (bool)$l['revoked'];
                        $is_expired = $l['expires_at'] && strtotime($l['expires_at']) < time();
                        $hmac_inactive = $l['hmac_active'] != 1;
                        
                        $status = 'Active';
                        $class = 'bg-green-100 text-green-800';

                        if ($is_revoked) { $status = 'Revoked'; $class = 'bg-red-100 text-red-800 font-bold'; }
                        else if ($hmac_inactive) { $status = 'HMAC Inactive'; $class = 'bg-yellow-100 text-yellow-800 font-bold'; }
                        else if ($is_expired) { $status = 'Expired'; $class = 'bg-yellow-100 text-yellow-800'; }
                        else if ((int)$l['current_devices'] >= (int)$l['max_devices']) { $status = 'Max Devices'; $class = 'bg-orange-100 text-orange-800'; }

                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 text-sm font-mono text-gray-900"><?= h($l['license_key']) ?></td>
                        <td class="py-2 px-4 text-sm font-medium text-indigo-600">
                            <a href="hmacs.php?op=edit&id=<?= h($l['hmac_id']) ?>" class="hover:underline" title="ID: <?= h($l['hmac_id']) ?>">
                                <?= h($l['hmac_name'] ?? 'N/A') ?>
                            </a>
                        </td>
                        <td class="py-2 px-4 text-sm text-gray-700"><?= h($l['current_devices']) ?> / <?= h($l['max_devices']) ?></td>
                        <td class="py-2 px-4 text-sm text-gray-500">
                            <?= $l['expires_at'] ? date('Y-m-d', strtotime($l['expires_at'])) : 'Vĩnh viễn' ?>
                        </td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 rounded-full text-xs <?= $class ?>"><?= $status ?></span>
                        </td>
                        <td class="py-2 px-4">
                            <a href="licenses.php?op=edit&id=<?= h($l['id']) ?>" class="text-blue-600 hover:text-blue-800 mr-3">Sửa</a>
                            <a href="admin.php?detail=<?= h($l['license_key']) ?>" class="text-indigo-600 hover:text-indigo-800 mr-3">Devices</a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận xóa Token <?= h($l['license_key']) ?> và TẤT CẢ thiết bị liên quan?')">
                                <input type="hidden" name="op" value="delete_license">
                                <input type="hidden" name="id" value="<?= h($l['id']) ?>">
                                <button class="text-red-600 hover:text-red-800" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>