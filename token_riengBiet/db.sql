-- license_schema.sql
CREATE DATABASE IF NOT EXISTS license_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE license_db;

CREATE TABLE IF NOT EXISTS licenses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  license_key VARCHAR(255) NOT NULL UNIQUE,
  secret VARCHAR(255) NOT NULL,
  max_devices TINYINT NOT NULL DEFAULT 5,
  revoked TINYINT(1) NOT NULL DEFAULT 0,
  target_url TEXT DEFAULT NULL,       -- per-license raw URL
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS access_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  level VARCHAR(10) NOT NULL,
  message TEXT NOT NULL,
  meta JSON NULL,
  ip VARCHAR(45) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- sample license (thay secret thật trước khi dùng)
INSERT INTO licenses (license_key, secret, max_devices, target_url) VALUES
('SAMPLE-KEY-ABC123', 'replace_with_random_secret_here', 5, 'https://raw.githubusercontent.com/nguyenthanhtung2k4/AMDIN_JUSST/refs/heads/main/GOP_UPLOAD_TOOL%5BV3.4%5D.py');
