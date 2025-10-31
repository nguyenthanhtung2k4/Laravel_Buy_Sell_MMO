<?php
// Bật Output Buffering ngay từ đầu để tránh lỗi headers đã được gửi
ob_start();

if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

// Hàm chuyển hướng đã được sửa để hoạt động cùng với session thông báo
// function redirect_self()
// {
//     // Cần phải xóa Output Buffer trước khi chuyển hướng
//     ob_end_clean();
//     header("Location: admin/Key");
//     exit;
// }


function redirect_self()
{
    if (ob_get_level()) ob_end_clean();
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}


// Kiểm tra và hiển thị thông báo từ session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$alert = '';
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']); // Xóa thông báo sau khi đã lấy ra
}

$title = 'Quản Lý Thành Viên | ' . $TN->site('title');
$body = [
    'title' => 'Danh sách Tool'
];
$body['header'] = '
';
$body['footer'] = '
';
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
// require_once(__DIR__ . '/../../../core/is_user.php');
// CheckLogin();
// CheckAdmin();
require_once(__DIR__.'/../../../core/auth_token.php'); // Giữ nguyên, giả định $pdo đã được khởi tạo trước đó

// --- TẠO HMAC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create_hmac') {
    try {
        $stmt = $pdo->prepare("INSERT INTO hmacs (name, secret, target_url, description, is_active)
                               VALUES (:n,:s,:t,:d,:a)");
        $stmt->execute([
            'n' => $_POST['name'],
            's' => $_POST['secret'],
            't' => $_POST['target_url'],
            'd' => $_POST['description'] ?? null,
            'a' => isset($_POST['is_active']) ? 1 : 0
        ]);
        $_SESSION['alert'] = '<div class="alert alert-success">✅ Thêm HMAC mới thành công!</div>';
    } catch (PDOException $e) {
        // Xử lý lỗi trùng lặp (ví dụ: nếu name là UNIQUE)
        $_SESSION['alert'] = '<div class="alert alert-danger">❌ Lỗi: Không thể thêm HMAC. Có thể tên đã tồn tại. (' . $e->getMessage() . ')</div>';
    }
    redirect_self();
}

// --- CẬP NHẬT HMAC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_hmac') {
    // ... Giữ nguyên logic cập nhật HMAC ...
    try {
        $stmt = $pdo->prepare("UPDATE hmacs SET name=:n, secret=:s, target_url=:t, description=:d, is_active=:a WHERE id=:id");
        $stmt->execute([
            'n' => $_POST['name'],
            's' => $_POST['secret'],
            't' => $_POST['target_url'],
            'd' => $_POST['description'],
            'a' => isset($_POST['is_active']) ? 1 : 0,
            'id' => $_POST['id']
        ]);
        $_SESSION['alert'] = '<div class="alert alert-success">✅ Cập nhật HMAC thành công!</div>';
    } catch (PDOException $e) {
        $_SESSION['alert'] = '<div class="alert alert-danger">❌ Lỗi: Cập nhật HMAC thất bại. (' . $e->getMessage() . ')</div>';
    }
    redirect_self();
}

// --- XÓA HMAC ---
if (isset($_GET['delete_hmac'])) {
    // ... Giữ nguyên logic xóa HMAC ...
    try {
        $id = intval($_GET['delete_hmac']);
        $pdo->prepare("DELETE FROM hmacs WHERE id=?")->execute([$id]);
        $_SESSION['alert'] = '<div class="alert alert-warning">🗑️ Đã xóa HMAC thành công.</div>';
    } catch (PDOException $e) {
        $_SESSION['alert'] = '<div class="alert alert-danger">❌ Lỗi: Xóa HMAC thất bại. (' . $e->getMessage() . ')</div>';
    }
    redirect_self();
}

// --- TẠO LICENSE (Đã fix lỗi kiểm tra trùng lặp và thông báo) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create_license') {
    $license_key = $_POST['license_key'];

    // 1. Kiểm tra License Key đã tồn tại chưa
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM licenses WHERE license_key = ?");
    $check_stmt->execute([$license_key]);
    if ($check_stmt->fetchColumn() > 0) {
        // Thông báo lỗi nếu key đã tồn tại
        $_SESSION['alert'] = '<div class="alert alert-danger">❌ Lỗi: License Key **`' . htmlspecialchars($license_key) . '`** đã tồn tại. Vui lòng nhập key khác.</div>';
    } else {
        // 2. Thêm mới nếu chưa tồn tại
        try {
            $stmt = $pdo->prepare("INSERT INTO licenses (license_key, hmac_id, max_devices, expires_at)
                               VALUES (:k,:h,:m,:e)");
            $stmt->execute([
                'k' => $license_key,
                'h' => $_POST['hmac_id'],
                'm' => $_POST['max_devices'],
                'e' => $_POST['expires_at'] ?: null
            ]);
            $_SESSION['alert'] = '<div class="alert alert-success">✅ Thêm License Key **`' . htmlspecialchars($license_key) . '`** thành công!</div>';
        } catch (PDOException $e) {
            $_SESSION['alert'] = '<div class="alert alert-danger">❌ Lỗi SQL khi thêm License. (' . $e->getMessage() . ')</div>';
        }
    }
    redirect_self();
}

// --- CẬP NHẬT LICENSE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_license') {
    // ... Giữ nguyên logic cập nhật License ...
    try {
        $stmt = $pdo->prepare("UPDATE licenses SET license_key=:k, hmac_id=:h, max_devices=:m, expires_at=:e, revoked=:r WHERE id=:id");
        $stmt->execute([
            'k' => $_POST['license_key'],
            'h' => $_POST['hmac_id'],
            'm' => $_POST['max_devices'],
            'e' => $_POST['expires_at'] ?: null,
            'r' => isset($_POST['revoked']) ? 1 : 0,
            'id' => $_POST['id']
        ]);
        $_SESSION['alert'] = '<div class="alert alert-success">✅ Cập nhật License #`' . $_POST['id'] . '` thành công!</div>';
    } catch (PDOException $e) {
        $_SESSION['alert'] = '<div class="alert alert-danger">❌ Lỗi: Cập nhật License thất bại. (' . $e->getMessage() . ')</div>';
    }
    redirect_self();
}

// --- XÓA LICENSE ---
if (isset($_GET['delete_license'])) {
    // ... Giữ nguyên logic xóa License ...
    try {
        $id = intval($_GET['delete_license']);
        $pdo->prepare("DELETE FROM licenses WHERE id=?")->execute([$id]);
        $_SESSION['alert'] = '<div class="alert alert-warning">🗑️ Đã xóa License thành công.</div>';
    } catch (PDOException $e) {
        $_SESSION['alert'] = '<div class="alert alert-danger">❌ Lỗi: Xóa License thất bại. (' . $e->getMessage() . ')</div>';
    }
    redirect_self();
}

// --------------------------------------------
// LOAD DATA - Giữ nguyên
$hmacs = $pdo->query("SELECT * FROM hmacs ORDER BY id DESC")->fetchAll();
$licenses = $pdo->query("
    SELECT l.*, h.name AS hmac_name, 
           (SELECT COUNT(*) FROM devices d WHERE d.license_id = l.id) AS device_count
    FROM licenses l 
    LEFT JOIN hmacs h ON l.hmac_id = h.id 
    ORDER BY l.id DESC
")->fetchAll();

$devices = $pdo->query("SELECT d.*, l.license_key FROM devices d LEFT JOIN licenses l ON d.license_id=l.id ORDER BY d.id DESC LIMIT 50")->fetchAll();
$links = $pdo->query("SELECT rl.*, l.license_key FROM redirect_links rl LEFT JOIN licenses l ON rl.license_id=l.id ORDER BY rl.id DESC LIMIT 50")->fetchAll();

?>
    <style>
        .body {
            color: black;
            background-color: #f8f9fa;
        }

        .card {
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }

        .table td,
        .table th {
            vertical-align: middle !important;
        }

        .btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
        }
    </style>



<div id="body" class="body main-content app-content">
    <div class="container-fluid">
        <?php echo $alert; ?> 
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>🔐 Quản lý HMAC</span>
                <button class="btn btn-light btn-sm" data-bs-toggle="collapse" data-bs-target="#createHmac">+
                    Thêm mới</button>
            </div>
            <div class="collapse" id="createHmac">
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="action" value="create_hmac">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tên HMAC</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Secret</label>
                                <input type="text" name="secret" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target URL</label>
                                <input type="url" name="target_url" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <input type="text" name="description" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label><input type="checkbox" name="is_active" checked> Kích hoạt
                                    ngay</label>
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button class="btn btn-success">Lưu HMAC</button>
                        </div>
                    </form>
                </div>
            </div>
<div class="card-body">
  <table class="table table-bordered table-striped table-sm align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Secret</th>
        <th>Target</th>
        <th>Trạng thái</th>
        <th class="text-end">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($hmacs as $h): ?>
      <tr>
        <td><?= $h['id'] ?></td>
        <td><?= htmlspecialchars($h['name']) ?></td>
        <td>
          <code title="<?= htmlspecialchars($h['secret']) ?>">
            <?= htmlspecialchars(substr($h['secret'], 0, 16)) ?>...
          </code>
        </td>
        <td><small><?= htmlspecialchars($h['target_url']) ?></small></td>
        <td>
          <?= $h['is_active'] ? '<span class="badge bg-success">Bật</span>' : '<span class="badge bg-secondary">Tắt</span>' ?>
        </td>
        <td class="text-end">
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
            data-bs-target="#editHmac<?= $h['id'] ?>">✏️ Sửa</button>
          <a href="?delete_hmac=<?= $h['id'] ?>" class="btn btn-danger btn-sm"
            onclick="return confirm('Bạn chắc chắn muốn xóa HMAC này?')">🗑️ Xóa</a>
        </td>
      </tr>

      <div class="modal fade" id="editHmac<?= $h['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <form method="post">
              <input type="hidden" name="action" value="update_hmac">
              <input type="hidden" name="id" value="<?= $h['id'] ?>">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Sửa HMAC #<?= $h['id'] ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Tên HMAC</label>
                  <input name="name" value="<?= htmlspecialchars($h['name']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Secret</label>
                  <div class="input-group">
                    <input name="secret" value="<?= htmlspecialchars($h['secret']) ?>" class="form-control" required>
                    <button class="btn btn-outline-secondary" type="button"
                      onclick="this.previousElementSibling.value = randomHex(64)">🔑 Tạo ngẫu nhiên</button>
                  </div>
                  <small class="text-muted">Secret sẽ được dùng để mã hóa HMAC. 64 ký tự hex = 256-bit</small>
                </div>
                <div class="mb-3">
                  <label class="form-label">Target URL</label>
                  <input name="target_url" value="<?= htmlspecialchars($h['target_url']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Mô tả</label>
                  <input name="description" value="<?= htmlspecialchars($h['description']) ?>" class="form-control">
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_active" <?= $h['is_active'] ? 'checked' : '' ?>>
                  <label class="form-check-label">Kích hoạt</label>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary">💾 Lưu</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span>🪪 Quản lý License</span>
                <button class="btn btn-light btn-sm" data-bs-toggle="collapse" data-bs-target="#createLicense">+
                    Thêm mới</button>
            </div>
            <div class="collapse" id="createLicense">
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="action" value="create_license">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">License Key</label>
                                <input type="text" name="license_key" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">HMAC</label>
                                <select name="hmac_id" class="form-select" required>
                                    <?php foreach ($hmacs as $h): ?>
                                            <option value="<?= $h['id'] ?>">
                                                <?= htmlspecialchars($h['name']) ?></option>
                                    <?php endforeach; ?>
                                   </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Max Devices</label>
                                <input type="number" name="max_devices" class="form-control" value="5"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ngày hết hạn</label>
                                <input type="date" name="expires_at" class="form-control">
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button class="btn btn-primary">Lưu License</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-striped table-sm table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Key</th>
                            <th>HMAC</th>
                            <th>Thiết bị</th>
                            <th>Hết hạn</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($licenses as $l): ?>
                            <tr>
                                <td><?= $l['id'] ?></td>
                                <td><code><?= htmlspecialchars($l['license_key']) ?></code></td>
                                <td><?= htmlspecialchars($l['hmac_name'] ?? '-') ?></td>
                                <td>
                                    <span
                                        class="fw-bold text-primary"><?= $l['device_count'] ?>/<?= $l['max_devices'] ?></span>
                                    <button class="btn btn-outline-info btn-sm ms-2" data-bs-toggle="modal"
                                        data-bs-target="#devicesLic<?= $l['id'] ?>">📱</button>
                                </td>
                                <td><?= $l['expires_at'] ?: '-' ?></td>
                                <td><?= $l['revoked'] ? '<span class="badge bg-danger">Revoked</span>' : '<span class="badge bg-success">Active</span>' ?>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editLic<?= $l['id'] ?>">Sửa</button>
                                    <a href="?delete_license=<?= $l['id'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Xóa license này?')">Xóa</a>
                                </td>
                            </tr>

                            <?php
                            $devicesLic = $pdo->prepare("SELECT * FROM devices WHERE license_id = ? ORDER BY last_seen DESC");
                            $devicesLic->execute([$l['id']]);
                            $devRows = $devicesLic->fetchAll();
                            ?>
                            <div class="modal fade" id="editLic<?= $l['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form method="post">
                                            <input type="hidden" name="action" value="update_license">
                                            <input type="hidden" name="id" value="<?= $l['id'] ?>">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">Sửa License #<?= $l['id'] ?></h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">License Key</label>
                                                        <input name="license_key" value="<?= htmlspecialchars($l['license_key']) ?>" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">HMAC</label>
                                                        <select name="hmac_id" class="form-select" required>
                                                            <?php foreach ($hmacs as $h): ?>
                                                                <option value="<?= $h['id'] ?>" <?= $l['hmac_id'] == $h['id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($h['name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Max Devices</label>
                                                        <input type="number" name="max_devices" value="<?= $l['max_devices'] ?>" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Ngày hết hạn</label>
                                                        <input type="date" name="expires_at" value="<?= $l['expires_at'] ? date('Y-m-d', strtotime($l['expires_at'])) : '' ?>" class="form-control">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="revoked" <?= $l['revoked'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label">Thu hồi (Revoked)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary">💾 Lưu</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="devicesLic<?= $l['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title">Thiết bị của License
                                                                    #<?= $l['id'] ?>
                                                                    (<?= $l['device_count'] ?>/<?= $l['max_devices'] ?>)
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                            <table class="table table-striped table-sm m-0">
                                                                    <thead class="table-light">
                                                                            <tr>
                                                                                    <th>ID</th>
                                                                                    <th>Tên thiết bị</th>
                                                                                    <th>OS</th>
                                                                                    <th>IP</th>
                                                                                    <th>App</th>
                                                                                    <th>Last seen</th>
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                            <?php if ($devRows):
                                                                                    foreach ($devRows as $d): ?>
                                                                                            <tr>
                                                                                                    <td><?= $d['id'] ?></td>
                                                                                                    <td><?= htmlspecialchars($d['device_name']) ?>
                                                                                                    </td>
                                                                                                    <td><?= htmlspecialchars($d['os_info']) ?>
                                                                                                    </td>
                                                                                                    <td><?= htmlspecialchars($d['ip_addr']) ?>
                                                                                                    </td>
                                                                                                    <td><?= htmlspecialchars($d['app_version']) ?>
                                                                                                    </td>
                                                                                                    <td><?= $d['last_seen'] ?></td>
                                                                                            </tr>
                                                                                    <?php endforeach; else: ?>
                                                                                            <tr>
                                                                                                    <td colspan="6"
                                                                                                            class="text-center text-muted">Chưa
                                                                                                            có thiết bị nào</td>
                                                                                            </tr>
                                                                                    <?php endif; ?>
                                                                    </tbody>
                                                            </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                            </div>
                                    </div>
                            </div>

                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">💻 Thiết bị gần nhất (50)</div>
            <div class="card-body">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>License</th>
                            <th>Device ID</th>
                            <th>OS</th>
                            <th>IP</th>
                            <th>Last Seen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($devices as $d): ?>
                            <tr>
                                <td><?= $d['id'] ?></td>
                                <td><?= htmlspecialchars($d['license_key']) ?></td>
                                <td><small><?= substr($d['device_id'], 0, 12) ?>...</small></td>
                                <td><?= htmlspecialchars($d['os_info']) ?></td>
                                <td><?= htmlspecialchars($d['ip_addr']) ?></td>
                                <td><?= $d['last_seen'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header bg-secondary text-white">🔗 Link Redirect gần nhất (50)</div>
            <div class="card-body">
                <table class="table table-striped table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>License</th>
                            <th>Token</th>
                            <th>Hết hạn</th>
                            <th>Used</th>
                            <th>Tạo lúc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($links as $r): ?>
                            <tr>
                                <td><?= $r['id'] ?></td>
                                <td><?= htmlspecialchars($r['license_key']) ?></td>
                                <td><code><?= substr($r['token'], 0, 10) ?>...</code></td>
                                <td><?= $r['expires_at'] ?></td>
                                <td><?= $r['used'] ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-success">No</span>' ?>
                                </td>
                                <td><?= $r['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function randomHex(len = 64) {
            const chars = 'abcdef0123456789';
            let res = '';
            for (let i = 0; i < len; i++) res += chars[Math.floor(Math.random() * chars.length)];
            return res;
        }
        function randomKey(len = 25) {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let res = '';
            for (let i = 0; i < len; i++) {
                res += chars[Math.floor(Math.random() * chars.length)];
                if ((i + 1) % 5 === 0 && i < len - 1) res += '-';
            }
            return res;
        }

        // Gợi ý secret trong HMAC
        document.addEventListener('DOMContentLoaded', () => {
            // ... Logic gợi ý secret và license key giữ nguyên ...
            // Gợi ý secret trong HMAC
            const secretInput = document.querySelector('input[name="secret"]');
            if (secretInput) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-secondary btn-sm mt-2';
                btn.textContent = '🔑 Tạo ngẫu nhiên secret';
                btn.onclick = () => {
                    secretInput.value = randomHex(64);
                };
                // Kiểm tra xem input có nằm trong input-group hay không (modal sửa)
                const inputGroup = secretInput.closest('.input-group');
                if (!inputGroup) {
                    // Chỉ thêm nút nếu nó không nằm trong input-group (form thêm mới)
                    secretInput.parentElement.appendChild(btn);
                }
            }

            // Gợi ý license key trong form thêm License
            const licenseInput = document.querySelector('form[action="create_license"] input[name="license_key"]');
            if (licenseInput) {
                const btn2 = document.createElement('button');
                btn2.type = 'button';
                btn2.className = 'btn btn-outline-secondary btn-sm mt-2';
                btn2.textContent = '🪪 Gợi ý License Key';
                btn2.onclick = () => {
                    licenseInput.value = randomKey(25);
                };
                licenseInput.parentElement.appendChild(btn2);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
// Đảm bảo output buffer được flush và đóng sau cùng
ob_end_flush();
?>