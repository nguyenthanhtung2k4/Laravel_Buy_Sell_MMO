-- file: db.sql (ĐÃ SỬA ĐỔI)
CREATE DATABASE IF NOT EXISTS license_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE license_db;

-- Bảng mới: hmacs (Chứa Secret Key và Target URL)
CREATE TABLE IF NOT EXISTS hmacs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  secret VARCHAR(255) NOT NULL,
  target_url TEXT NOT NULL,          -- URL đích THỰC THI (URL không giới hạn thiết bị)
  is_active TINYINT(1) NOT NULL DEFAULT 1, -- Active/Inactive HMAC
  description TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng licenses (Chứa Token và giới hạn thiết bị)
CREATE TABLE IF NOT EXISTS licenses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  license_key VARCHAR(255) NOT NULL UNIQUE,
  hmac_id INT NOT NULL,                     -- FOREIGN KEY: Liên kết đến HMAC Key
  max_devices TINYINT NOT NULL DEFAULT 5,
  revoked TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME NULL,
  FOREIGN KEY (hmac_id) REFERENCES hmacs(id) ON DELETE RESTRICT -- KHÔNG XÓA HMAC nếu còn Tokens liên quan
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng devices (Giữ nguyên)
CREATE TABLE IF NOT EXISTS devices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  license_id INT NOT NULL,
  device_id VARCHAR(255) NOT NULL,
  device_name VARCHAR(255),
  os_info VARCHAR(255),
  ip_addr VARCHAR(45),
  app_version VARCHAR(50),
  registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_seen DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY ux_license_device (license_id, device_id),
  INDEX idx_last_seen (last_seen),
  FOREIGN KEY (license_id) REFERENCES licenses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng redirect_links (Giữ nguyên)
CREATE TABLE IF NOT EXISTS redirect_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  license_id INT NOT NULL,
  device_id VARCHAR(255) NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  target_url TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME NOT NULL,
  used TINYINT(1) NOT NULL DEFAULT 0,
  INDEX idx_expires_at (expires_at),
  FOREIGN KEY (license_id) REFERENCES licenses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng access_logs (Giữ nguyên)
CREATE TABLE IF NOT EXISTS access_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  level VARCHAR(10) NOT NULL,
  message TEXT NOT NULL,
  meta JSON NULL,
  ip VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;