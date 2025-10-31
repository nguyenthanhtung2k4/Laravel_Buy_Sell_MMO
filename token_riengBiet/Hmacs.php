<?php
// file: hmacs.php - Quản lý HMAC Keys
require_once __DIR__ . '/db.php';

$pdo = get_pdo();

if (!function_exists('h')) {
    function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

$op = $_GET['op'] ?? '';
$id = (int)($_GET['id'] ?? 0);
$message = '';
$error = '';

// Hàm tạo HMAC Secret Key ngẫu nhiên 64 ký tự hex
function generate_hmac_secret(int $length = 32): string {
    return bin2hex(random_bytes($length));
}

// --- Xử lý POST (Thêm, Sửa, Xóa, Kích hoạt/Vô hiệu hóa) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_op = $_POST['op'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    
    try {
        if ($post_op === 'add_hmac') {
            $name = trim($_POST['name'] ?? '');
            $secret = trim($_POST['hmac_secret'] ?? generate_hmac_secret());
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) { throw new Exception("Tên HMAC không được để trống."); }
            if (empty($secret)) { throw new Exception("HMAC Secret không được để trống."); }

            $stmt = $pdo->prepare("INSERT INTO hmacs (name, hmac_secret, description) VALUES (:name, :secret, :desc)");
            $stmt->execute([':name' => $name, ':secret' => $secret, ':desc' => $description]);
            write_file_log('INFO', 'HMAC added', ['name' => $name]);
            $message = "HMAC Key **{$name}** đã được tạo thành công.";
        }
        else if ($post_op === 'edit_hmac') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $is_active = (int)($_POST['is_active'] ?? 0);

            if (empty($name)) { throw new Exception("Tên HMAC không được để trống."); }
            
            $stmt = $pdo->prepare("UPDATE hmacs SET name = :name, description = :desc, is_active = :active WHERE id = :id");
            $stmt->execute([':name' => $name, ':desc' => $description, ':active' => $is_active, ':id' => $id]);
            write_file_log('INFO', 'HMAC updated', ['id' => $id, 'name' => $name]);
            $message = "HMAC Key **{$name}** đã được cập nhật thành công.";
            $op = ''; // Quay lại trang danh sách
        }
        else if ($post_op === 'toggle_active') {
            $current_active = (int)($_POST['current_active'] ?? 0);
            $new_active = $current_active === 1 ? 0 : 1;
            $action = $new_active === 1 ? 'kích hoạt' : 'vô hiệu hóa';

            $stmt = $pdo->prepare("UPDATE hmacs SET is_active = :active WHERE id = :id");
            $stmt->execute([':active' => $new_active, ':id' => $id]);
            
            $hmac_name_stmt = $pdo->prepare("SELECT name FROM hmacs WHERE id = :id");
            $hmac_name_stmt->execute([':id' => $id]);
            $hmac_name = $hmac_name_stmt->fetchColumn() ?? "ID:$id";
            
            write_file_log('CRITICAL', "HMAC {$action}", ['id' => $id, 'name' => $hmac_name, 'new_status' => $new_active]);
            $message = "Đã **{$action}** HMAC Key **{$hmac_name}** thành công. Tất cả Tokens sử dụng HMAC này sẽ bị ảnh hưởng.";
        }
        else if ($post_op === 'delete_hmac') {
             // Kiểm tra nếu có license nào đang sử dụng HMAC này
            $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM licenses WHERE hmac_id = :id");
            $count_stmt->execute([':id' => $id]);
            if ((int)$count_stmt->fetchColumn() > 0) {
                throw new Exception("Không thể xóa HMAC này. Vẫn còn Tokens đang sử dụng nó. Vui lòng gán Tokens sang HMAC khác trước.");
            }
            
            $stmt = $pdo->prepare("DELETE FROM hmacs WHERE id = :id");
            $stmt->execute([':id' => $id]);
            write_file_log('CRITICAL', 'HMAC deleted', ['id' => $id]);
            $message = "HMAC Key ID **{$id}** đã được xóa thành công.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// --- Lấy dữ liệu ---
$hmacs = [];
$totalTokens = [];

// Lấy tổng số Tokens/Licenses đang sử dụng mỗi HMAC
$token_count_stmt = $pdo->query("
    SELECT hmac_id, COUNT(*) as token_count 
    FROM licenses 
    GROUP BY hmac_id
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Lấy danh sách HMACs
$hmacs = $pdo->query("
    SELECT id, name, hmac_secret, is_active, description, created_at, updated_at
    FROM hmacs 
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$title = "Quản lý HMAC Keys";

// Chèn header
require_once 'header.php';
?>

<h2 class="text-2xl font-semibold text-gray-800 mb-6"><?= h($title) ?></h2>

<?php if ($op === 'add'): 
    $new_secret = generate_hmac_secret();
?>
    <div class="max-w-xl mx-auto p-6 bg-gray-50 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4">Tạo HMAC Key Mới</h3>
        <form method="POST">
            <input type="hidden" name="op" value="add_hmac">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Tên HMAC <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <div class="mb-4">
                <label for="hmac_secret" class="block text-sm font-medium text-gray-700">HMAC Secret Key (Có thể dùng lại)</label>
                <div class="flex">
                    <input type="text" name="hmac_secret" id="hmac_secret" value="<?= h($new_secret) ?>" readonly class="mt-1 block w-full bg-yellow-50 font-mono text-sm border border-gray-300 rounded-l-md shadow-sm p-2">
                    <button type="button" onclick="document.getElementById('hmac_secret').value = '<?= h(generate_hmac_secret()) ?>'" class="mt-1 px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-r-md hover:bg-indigo-700 transition">Tạo mới</button>
                </div>
                <p class="mt-1 text-xs text-gray-500">Đây là khóa bí mật để Client Tool ký request. Khóa này có thể được tái sử dụng cho nhiều Tokens.</p>
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
            </div>

            <div class="flex justify-end">
                <a href="hmacs.php" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Hủy</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Tạo HMAC Key
                </button>
            </div>
        </form>
    </div>

<?php elseif ($op === 'edit' && $id > 0): 
    $edit_hmac = $pdo->prepare("SELECT id, name, hmac_secret, is_active, description, created_at, updated_at FROM hmacs WHERE id = :id");
    $edit_hmac->execute([':id' => $id]);
    $h = $edit_hmac->fetch(PDO::FETCH_ASSOC);
    if (!$h) {
        $error = "HMAC Key không tồn tại.";
        $op = ''; 
        require_once 'footer.php';
        exit;
    }
?>
    <div class="max-w-xl mx-auto p-6 bg-gray-50 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4">Sửa HMAC Key: <?= h($h['name']) ?> (ID: <?= $h['id'] ?>)</h3>
        <form method="POST">
            <input type="hidden" name="op" value="edit_hmac">
            <input type="hidden" name="id" value="<?= h($h['id']) ?>">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Tên HMAC <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="<?= h($h['name']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">HMAC Secret Key</label>
                <div class="relative mt-1">
                    <input type="text" id="hmac_secret_display" value="<?= h($h['hmac_secret']) ?>" readonly class="block w-full bg-yellow-50 font-mono text-sm border border-gray-300 rounded-md shadow-sm p-2 cursor-pointer" onclick="this.select(); document.execCommand('copy'); alert('Đã sao chép HMAC Secret!');">
                </div>
                <p class="mt-1 text-xs text-gray-500">Click để sao chép. Không thể thay đổi khóa này sau khi tạo.</p>
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"><?= h($h['description']) ?></textarea>
            </div>
            
            <div class="mb-6">
                <label for="is_active" class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" <?= $h['is_active'] ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <span class="ml-2 text-sm font-medium text-gray-700">HMAC đang hoạt động (Tất cả Tokens sử dụng nó sẽ hoạt động)</span>
                </label>
                <?php if (!$h['is_active']): ?>
                    <p class="mt-1 text-sm text-red-600 font-semibold">CẢNH BÁO: HMAC này đang **VÔ HIỆU HÓA**. Tất cả <?= $token_count_stmt[$h['id']] ?? 0 ?> Tokens liên quan sẽ không thể xác thực.</p>
                <?php endif; ?>
            </div>

            <div class="flex justify-between items-center">
                <a href="hmacs.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Quay lại</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lưu Thay đổi
                </button>
            </div>
        </form>
    </div>


<?php else: ?>
    <div class="flex justify-between items-center mb-4">
        <p class="text-gray-600">Tổng số HMAC Keys: **<?= count($hmacs) ?>**</p>
        <a href="hmacs.php?op=add" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            + Tạo HMAC Mới
        </a>
    </div>

    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên HMAC / ID</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HMAC Secret (Rút gọn)</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tokens liên kết</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($hmacs as $h): 
                    $is_active = (bool)$h['is_active'];
                    $token_count = $token_count_stmt[$h['id']] ?? 0;
                ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-4 text-sm font-medium text-gray-900">
                            <?= h($h['name']) ?> <span class="text-xs text-gray-500">(ID: <?= $h['id'] ?>)</span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-700 font-mono">
                            <?= h(substr($h['hmac_secret'], 0, 8)) ?>...
                        </td>
                        <td class="py-4 px-4 text-sm">
                            <?php if ($is_active): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                <span title="<?= h($h['description']) ?>" class="ml-1 text-red-500 cursor-pointer">⚠️</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-500 font-semibold">
                             <?= $token_count ?> Tokens
                        </td>
                        <td class="py-4 px-4 text-sm font-medium">
                            <a href="hmacs.php?op=edit&id=<?= h($h['id']) ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                            
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn <?= $is_active ? 'VÔ HIỆU HÓA' : 'KÍCH HOẠT' ?> HMAC Key <?= h($h['name']) ?>? Điều này sẽ ảnh hưởng đến <?= $token_count ?> Tokens liên quan!')">
                                <input type="hidden" name="op" value="toggle_active">
                                <input type="hidden" name="id" value="<?= h($h['id']) ?>">
                                <input type="hidden" name="current_active" value="<?= $h['is_active'] ?>">
                                <button type="submit" class="<?= $is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' ?> mr-3">
                                    <?= $is_active ? 'Vô hiệu hóa' : 'Kích hoạt' ?>
                                </button>
                            </form>
                            
                            <form method="POST" style="display:inline;" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc muốn XÓA HMAC Key <?= h($h['name']) ?> (ID: <?= $h['id'] ?>)? Nếu còn Tokens liên quan (<?= $token_count ?>), thao tác sẽ bị từ chối.')">
                                <input type="hidden" name="op" value="delete_hmac">
                                <input type="hidden" name="id" value="<?= h($h['id']) ?>">
                                <button type="submit" class="text-red-400 hover:text-red-600 ml-1">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>

<?php require_once 'footer.php'; ?>