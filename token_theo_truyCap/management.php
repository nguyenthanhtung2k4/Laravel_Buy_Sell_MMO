<?php
// File: management.php
require 'config.php';
check_db_connection();

$message = '';
$message_type = '';
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'signatures'; // Default tab

// --- LOGIC XỬ LÝ CHỮ KÝ SỐ (SIGNATURES) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'manage_signature') {
    $current_tab = 'signatures';
    $api_key = filter_input(INPUT_POST, 'api_key', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $payload_json = filter_input(INPUT_POST, 'payload_json');
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $signature_id = filter_input(INPUT_POST, 'signature_id', FILTER_VALIDATE_INT);

    json_decode($payload_json);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $message = "Lỗi: Payload JSON không hợp lệ.";
        $message_type = 'danger';
    } elseif (!empty($api_key) && !empty($payload_json)) {
        try {
            if ($signature_id) {
                $stmt = $pdo->prepare("UPDATE signatures SET api_key = ?, payload_json = ?, description = ? WHERE signature_id = ?");
                $stmt->execute([$api_key, $payload_json, $description, $signature_id]);
                $message = "Cập nhật Chữ ký số thành công!";
                $message_type = 'success';
            } else {
                $stmt = $pdo->prepare("INSERT INTO signatures (api_key, payload_json, description) VALUES (?, ?, ?)");
                $stmt->execute([$api_key, $payload_json, $description]);
                $message = "Tạo Chữ ký số mới thành công!";
                $message_type = 'success';
            }
        } catch (\PDOException $e) {
            $message = "Lỗi CSDL: Key đã tồn tại hoặc lỗi khác.";
            $message_type = 'danger';
        }
    } else {
        $message = "Vui lòng điền đầy đủ Chữ ký số và Payload.";
        $message_type = 'warning';
    }
}

// Xử lý xóa Chữ ký số
$delete_sig_id = filter_input(INPUT_GET, 'delete_sig_id', FILTER_VALIDATE_INT);
if ($delete_sig_id) {
    $current_tab = 'signatures';
    try {
        $stmt = $pdo->prepare("DELETE FROM signatures WHERE signature_id = ?");
        $stmt->execute([$delete_sig_id]);
        $message = "Đã xóa Chữ ký số thành công!";
        $message_type = 'success';
    } catch (\PDOException $e) {
        $message = "Lỗi CSDL: Không thể xóa vì Chữ ký số này đang được Token sử dụng.";
        $message_type = 'danger';
    }
}

// Lấy danh sách chữ ký số
$signatures = $pdo->query("SELECT * FROM signatures ORDER BY signature_id DESC")->fetchAll();


// --- LOGIC XỬ LÝ TOKEN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_token') {
    $current_tab = 'tokens';
    $signature_id = filter_input(INPUT_POST, 'signature_id', FILTER_VALIDATE_INT);
    
    if ($signature_id) {
        $token_value = generate_uuid();
        try {
            $stmt = $pdo->prepare("INSERT INTO tokens (token_value, signature_id, max_devices) VALUES (?, ?, ?)");
            $stmt->execute([$token_value, $signature_id, DEVICE_LIMIT]);
            $message = "Tạo Token mới thành công! Token: <code class='text-danger'>{$token_value}</code>";
            $message_type = 'success';
        } catch (\PDOException $e) {
            $message = "Lỗi CSDL khi tạo Token: " . $e->getMessage();
            $message_type = 'danger';
        }
    } else {
        $message = "Vui lòng chọn một Chữ ký số để liên kết Token.";
        $message_type = 'warning';
    }
}

// Xử lý kích hoạt/vô hiệu hóa Token
$toggle_token_id = filter_input(INPUT_GET, 'toggle_token_id', FILTER_VALIDATE_INT);
if ($toggle_token_id) {
    $current_tab = 'tokens';
    try {
        $stmt = $pdo->prepare("UPDATE tokens SET is_active = !is_active WHERE token_id = ?");
        $stmt->execute([$toggle_token_id]);
        $message = "Đã cập nhật trạng thái Token thành công!";
        $message_type = 'info';
    } catch (\PDOException $e) {
        $message = "Lỗi CSDL khi cập nhật trạng thái Token.";
        $message_type = 'danger';
    }
}

// Lấy danh sách Token (có kèm thông tin Chữ ký số)
$tokens_query = "
    SELECT t.*, s.api_key 
    FROM tokens t
    JOIN signatures s ON t.signature_id = s.signature_id
    ORDER BY t.created_at DESC
";
$tokens = $pdo->query($tokens_query)->fetchAll();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chữ ký & Token</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php echo generate_navbar(); ?>
    
    <div class="container mt-5">
        <h1 class="mb-4 text-secondary">Quản lý Chữ ký số và Token</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-4" id="managementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $current_tab == 'signatures' ? 'active' : ''; ?>" id="signatures-tab" data-bs-toggle="tab" data-bs-target="#signatures" type="button" role="tab" aria-controls="signatures" aria-selected="<?php echo $current_tab == 'signatures' ? 'true' : 'false'; ?>">
                    <i class="fas fa-lock me-2"></i> Quản lý Chữ ký số (API Key)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $current_tab == 'tokens' ? 'active' : ''; ?>" id="tokens-tab" data-bs-toggle="tab" data-bs-target="#tokens" type="button" role="tab" aria-controls="tokens" aria-selected="<?php echo $current_tab == 'tokens' ? 'true' : 'false'; ?>">
                    <i class="fas fa-key me-2"></i> Quản lý Token Truy cập
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            
            <!-- CHỮ KÝ SỐ TAB -->
            <div class="tab-pane fade <?php echo $current_tab == 'signatures' ? 'show active' : ''; ?>" id="signatures" role="tabpanel" aria-labelledby="signatures-tab">
                <h2 class="mt-3 mb-3 text-primary">Tạo/Sửa Chữ ký số & Payload</h2>

                <!-- Form Thêm/Sửa Chữ ký số -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">Form Chữ ký số</div>
                    <div class="card-body">
                        <form method="POST" action="management.php?tab=signatures">
                            <input type="hidden" name="action" value="manage_signature">
                            <input type="hidden" name="signature_id" id="signature_id">
                            <div class="mb-3">
                                <label for="api_key" class="form-label">Chữ ký số / API Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key" required>
                                <div class="form-text">Key bí mật mà client cần gửi kèm Token.</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <input type="text" class="form-control" id="description" name="description">
                            </div>
                            <div class="mb-3">
                                <label for="payload_json" class="form-label">Payload (Dữ liệu JSON trả về)</label>
                                <textarea class="form-control" id="payload_json" name="payload_json" rows="5" required>{
    "status": "success",
    "message": "Truy cập được phê duyệt",
    "config": {
        "timeout": 300,
        "mode": "secure"
    }
}</textarea>
                                <div class="form-text text-danger">Đảm bảo đây là JSON hợp lệ.</div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submit_sig_button">Thêm mới</button>
                            <button type="button" class="btn btn-secondary" onclick="resetSigForm()">Đặt lại</button>
                        </form>
                    </div>
                </div>

                <!-- Danh sách Chữ ký số -->
                <h2 class="mt-5 mb-3 text-primary">Danh sách Chữ ký số đã tạo</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle bg-white shadow-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>API Key</th>
                                <th>Mô tả</th>
                                <th>Payload (JSON)</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($signatures as $sig): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sig['signature_id']); ?></td>
                                    <td><code class="text-break"><?php echo htmlspecialchars($sig['api_key']); ?></code></td>
                                    <td><?php echo htmlspecialchars($sig['description']); ?></td>
                                    <td class="text-break"><pre style="max-height: 150px; overflow-y: auto; white-space: pre-wrap; font-size: 0.8em;"><?php echo htmlspecialchars($sig['payload_json']); ?></pre></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-sig-btn" 
                                            data-id="<?php echo $sig['signature_id']; ?>" 
                                            data-key="<?php echo htmlspecialchars($sig['api_key']); ?>" 
                                            data-desc="<?php echo htmlspecialchars($sig['description']); ?>" 
                                            data-payload="<?php echo htmlspecialchars($sig['payload_json']); ?>">Sửa</button>
                                        <a href="management.php?tab=signatures&delete_sig_id=<?php echo $sig['signature_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa Chữ ký số này không?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOKEN TAB -->
            <div class="tab-pane fade <?php echo $current_tab == 'tokens' ? 'show active' : ''; ?>" id="tokens" role="tabpanel" aria-labelledby="tokens-tab">
                <h2 class="mt-3 mb-3 text-success">Tạo Token Truy cập</h2>
                <p class="lead">Tạo Token độc nhất, mỗi Token được giới hạn chỉ <?php echo DEVICE_LIMIT; ?> thiết bị được truy cập.</p>

                <!-- Form Tạo Token mới -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">Tạo Token mới</div>
                    <div class="card-body">
                        <form method="POST" action="management.php?tab=tokens">
                            <input type="hidden" name="action" value="create_token">
                            <div class="mb-3">
                                <label for="token_signature_id" class="form-label">Chọn Chữ ký số (API Key)</label>
                                <select class="form-select" id="token_signature_id" name="signature_id" required>
                                    <option value="">-- Chọn một Chữ ký số đã tạo --</option>
                                    <?php foreach ($signatures as $sig): ?>
                                        <option value="<?php echo $sig['signature_id']; ?>">
                                            ID: <?php echo $sig['signature_id']; ?> - Key: <?php echo htmlspecialchars($sig['api_key']); ?> (<?php echo htmlspecialchars($sig['description']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Token này sẽ dùng Payload (dữ liệu) của Chữ ký số đã chọn.</div>
                            </div>
                            <button type="submit" class="btn btn-success">Tạo Token (Giới hạn <?php echo DEVICE_LIMIT; ?> thiết bị)</button>
                        </form>
                    </div>
                </div>

                <!-- Danh sách Token -->
                <h2 class="mt-5 mb-3 text-success">Danh sách Token đã tạo</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle bg-white shadow-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Token</th>
                                <th>Liên kết Key</th>
                                <th>Lượt truy cập</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tokens as $token): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($token['token_id']); ?></td>
                                    <td><code class="text-break"><?php echo htmlspecialchars($token['token_value']); ?></code></td>
                                    <td><?php echo htmlspecialchars($token['api_key']); ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php echo $token['access_count'] >= DEVICE_LIMIT ? 'bg-danger' : ($token['access_count'] > 0 ? 'bg-warning' : 'bg-primary'); ?>">
                                            <?php echo htmlspecialchars($token['access_count']); ?> / <?php echo DEVICE_LIMIT; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $token['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo $token['is_active'] ? 'Hoạt động' : 'Vô hiệu hóa'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($token['created_at'])); ?></td>
                                    <td>
                                        <a href="management.php?tab=tokens&toggle_token_id=<?php echo $token['token_id']; ?>" 
                                           class="btn btn-sm <?php echo $token['is_active'] ? 'btn-secondary' : 'btn-success'; ?>">
                                            <?php echo $token['is_active'] ? 'Vô hiệu hóa' : 'Kích hoạt lại'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle and Custom JS for Tabs and Forms -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS để chuyển đổi giữa các tab khi tải lại trang (do có tham số tab trong URL)
        document.addEventListener('DOMContentLoaded', function() {
            var triggerEl = document.querySelector('#managementTabs button.active');
            if (triggerEl) {
                new bootstrap.Tab(triggerEl).show();
            }
        });

        // Chức năng Sửa Chữ ký số
        document.querySelectorAll('.edit-sig-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('signature_id').value = this.dataset.id;
                document.getElementById('api_key').value = this.dataset.key;
                document.getElementById('description').value = this.dataset.desc;
                document.getElementById('payload_json').value = this.dataset.payload.replace(/\\n/g, '\n');
                document.getElementById('submit_sig_button').textContent = 'Cập nhật';
                document.getElementById('submit_sig_button').classList.remove('btn-primary');
                document.getElementById('submit_sig_button').classList.add('btn-warning');
                document.querySelector('#signatures .card-header').textContent = 'Chỉnh sửa Chữ ký số';
                // Chuyển sang tab signatures
                new bootstrap.Tab(document.getElementById('signatures-tab')).show();
            });
        });

        // Chức năng Đặt lại Form Chữ ký số
        function resetSigForm() {
            document.getElementById('signature_id').value = '';
            document.getElementById('api_key').value = '';
            document.getElementById('description').value = '';
            document.getElementById('payload_json').value = '{\n    "status": "success",\n    "message": "Truy cập được phê duyệt",\n    "config": {\n        "timeout": 300,\n        "mode": "secure"\n    }\n}';
            document.getElementById('submit_sig_button').textContent = 'Thêm mới';
            document.getElementById('submit_sig_button').classList.remove('btn-warning');
            document.getElementById('submit_sig_button').classList.add('btn-primary');
            document.querySelector('#signatures .card-header').textContent = 'Form Chữ ký số';
        }
    </script>
</body>
</html>
