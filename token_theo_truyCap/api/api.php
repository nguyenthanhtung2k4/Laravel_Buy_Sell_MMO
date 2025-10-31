<?php
// File: api/api.php
// Backend Handler gộp logic xác thực và logic phục vụ dữ liệu một lần.
require '../config.php';

header('Content-Type: application/json');

// Lấy action từ query parameter
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (!isset($action)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing action parameter.']);
    exit;
}

// --- HÀNH ĐỘNG 1: ACCESS/Xác thực ---
if ($action === 'access') {
    
    // Lấy thông tin từ request POST
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $signature = filter_input(INPUT_POST, 'signature', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user_identifier = $_SERVER['HTTP_USER_AGENT'] ?? $_SERVER['REMOTE_ADDR']; // User Agent hoặc IP
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $response = ['status' => 'error', 'message' => 'Lỗi không xác định.'];
    $log_status = 'UNKNOWN_ERROR';
    $data_link = null;

    try {
        if (empty($token) || empty($signature)) {
            $response['message'] = 'Thiếu Token hoặc Chữ ký số.';
            $log_status = 'FAILED_MISSING_DATA';
        } else {
            // 1. Truy vấn Token và liên kết Signature
            $stmt = $pdo->prepare("
                SELECT t.*, s.payload_json 
                FROM tokens t 
                JOIN signatures s ON t.signature_id = s.signature_id
                WHERE t.token_value = ? AND s.api_key = ? AND t.is_active = TRUE
            ");
            $stmt->execute([$token, $signature]);
            $access_data = $stmt->fetch();

            if (!$access_data) {
                $response['message'] = 'Token hoặc Chữ ký số không hợp lệ hoặc Token đã bị vô hiệu hóa.';
                $log_status = 'FAILED_INVALID_CREDENTIALS';
            } else {
                // 2. Kiểm tra Giới hạn thiết bị (5 thiết bị)
                if ($access_data['access_count'] >= DEVICE_LIMIT) {
                    $response['message'] = 'Token đã đạt giới hạn truy cập (' . DEVICE_LIMIT . ' thiết bị).';
                    $log_status = 'FAILED_LIMIT_REACHED';
                } else {
                    // 3. CẤP PHÉP: Tăng lượt truy cập và tạo Link dữ liệu độc nhất
                    
                    $unique_data_id = generate_uuid(); 
                    $cache_file = '../cache/' . $unique_data_id . '.json';
                    
                    if (!is_dir('../cache')) {
                        mkdir('../cache', 0777, true);
                    }
                    
                    // Ghi Payload vào file cache
                    file_put_contents($cache_file, $access_data['payload_json']);
                    
                    // Đường link dữ liệu độc nhất trỏ về action=data
                    $data_link = BASE_URL . 'api/api.php?action=data&id=' . $unique_data_id;

                    // Cập nhật lượt truy cập Token
                    $update_stmt = $pdo->prepare("UPDATE tokens SET access_count = access_count + 1 WHERE token_id = ?");
                    $update_stmt->execute([$access_data['token_id']]);

                    // Phản hồi thành công
                    $response['status'] = 'success';
                    $response['message'] = 'Xác thực thành công. Truy cập link dữ liệu độc nhất.';
                    $response['data_url'] = $data_link;
                    $log_status = 'SUCCESS';
                }
            }
        }
    } catch (\PDOException $e) {
        $response['message'] = 'Lỗi CSDL: ' . $e->getMessage();
        $log_status = 'FAILED_DB_ERROR';
    } catch (\Exception $e) {
        $response['message'] = 'Lỗi hệ thống: ' . $e->getMessage();
        $log_status = 'FAILED_SYSTEM_ERROR';
    }

    // Ghi log truy cập
    try {
        $log_stmt = $pdo->prepare("
            INSERT INTO access_log (token_value, user_identifier, ip_address, status, data_link) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $log_stmt->execute([
            $token ?? 'N/A', 
            $user_identifier, 
            $ip_address, 
            $log_status, 
            $data_link
        ]);
    } catch (\PDOException $e) {
        error_log("Failed to insert access log: " . $e->getMessage());
    }
    
    echo json_encode($response);
    exit;
} 

// --- HÀNH ĐỘNG 2: DATA/Phục vụ dữ liệu một lần ---
elseif ($action === 'data') {
    
    $unique_data_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($unique_data_id)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Thiếu ID dữ liệu.']);
        exit;
    }

    $cache_file = '../cache/' . $unique_data_id . '.json';

    if (!file_exists($cache_file)) {
        // Nếu file không tồn tại (đã được tải hoặc ID sai)
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Đường link đã hết hạn hoặc không hợp lệ. Vui lòng tạo yêu cầu mới.']);
        exit;
    }

    try {
        // 1. Đọc dữ liệu Payload
        $payload_json = file_get_contents($cache_file);
        
        // 2. Phục vụ dữ liệu cho Client
        echo $payload_json;
        
        // 3. XÓA file để đảm bảo chỉ dùng được MỘT LẦN DUY NHẤT
        unlink($cache_file);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Lỗi nội bộ khi xử lý dữ liệu.']);
    }
    exit;
}

// --- HÀNH ĐỘNG KHÔNG HỢP LỆ ---
else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
    exit;
}
?>
