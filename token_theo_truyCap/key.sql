-- File: database_schema.sql
-- Cấu trúc này dành cho MySQL/MariaDB

-- Bảng lưu trữ Chữ ký số (Signature/API Key) và Payload
CREATE TABLE signatures (
    signature_id INT AUTO_INCREMENT PRIMARY KEY,
    api_key VARCHAR(64) UNIQUE NOT NULL COMMENT 'Chữ ký số, key để xác thực',
    payload_json TEXT NOT NULL COMMENT 'Nội dung dữ liệu an toàn (JSON) trả về cho người dùng',
    description VARCHAR(255) COMMENT 'Mô tả về chữ ký/mục đích sử dụng',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng lưu trữ Token truy cập
CREATE TABLE tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    token_value VARCHAR(36) UNIQUE NOT NULL COMMENT 'Token UUID độc nhất',
    signature_id INT NOT NULL COMMENT 'Liên kết với Chữ ký số nào',
    max_devices INT DEFAULT 5 NOT NULL COMMENT 'Giới hạn số thiết bị được phép truy cập (yêu cầu của bạn)',
    access_count INT DEFAULT 0 NOT NULL COMMENT 'Số lần đã truy cập thành công',
    is_active BOOLEAN DEFAULT TRUE NOT NULL COMMENT 'Token còn hoạt động hay không',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (signature_id) REFERENCES signatures(signature_id)
);

-- Bảng lưu trữ Nhật ký truy cập (Live Control)
CREATE TABLE access_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    token_value VARCHAR(36) NOT NULL,
    user_identifier VARCHAR(255) COMMENT 'Thông tin thiết bị/người dùng truy cập',
    ip_address VARCHAR(45) NOT NULL,
    access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL COMMENT 'Trạng thái: SUCCESS, FAILED_SIGNATURE, FAILED_LIMIT',
    data_link VARCHAR(255) COMMENT 'Đường link dữ liệu duy nhất được trả về (nếu thành công)'
);
