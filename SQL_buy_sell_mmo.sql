CREATE DATABASE  IF NOT EXISTS `license_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `license_db`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: license_db
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access_logs`
--

DROP TABLE IF EXISTS `access_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `access_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `level` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` json DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_logs`
--

LOCK TABLES `access_logs` WRITE;
/*!40000 ALTER TABLE `access_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `access_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_logo`
--

DROP TABLE IF EXISTS `api_logo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_logo` (
  `id` int NOT NULL,
  `shortName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `logo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_logo`
----

DROP TABLE IF EXISTS `author_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `author_info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `team` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `team_members` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `other_account` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `market_account` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `work_category` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `created_at` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author_info`
--

LOCK TABLES `author_info` WRITE;
/*!40000 ALTER TABLE `author_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `author_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank` (
  `id` int NOT NULL AUTO_INCREMENT,
  `short_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `accountNumber` text COLLATE utf8mb4_general_ci NOT NULL,
  `accountName` text COLLATE utf8mb4_general_ci NOT NULL,
  `token` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank`
--

LOCK TABLES `bank` WRITE;
/*!40000 ALTER TABLE `bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(32) DEFAULT NULL,
  `username` varchar(32) NOT NULL,
  `loaithe` varchar(32) NOT NULL,
  `menhgia` int NOT NULL,
  `thucnhan` int DEFAULT '0',
  `seri` text NOT NULL,
  `pin` text NOT NULL,
  `createdate` datetime NOT NULL,
  `status` varchar(32) NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_general_ci NOT NULL,
  `slug` text COLLATE utf8mb4_general_ci NOT NULL,
  `detail` longtext COLLATE utf8mb4_general_ci,
  `note` longtext COLLATE utf8mb4_general_ci,
  `status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_vietnamese_ci,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`

-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `license_id` int NOT NULL,
  `device_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_addr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_seen` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_license_device` (`license_id`,`device_id`),
  KEY `idx_last_seen` (`last_seen`),
  CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`license_id`) REFERENCES `licenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--


--
-- Table structure for table `dongtien`
--

DROP TABLE IF EXISTS `dongtien`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dongtien` (
  `id` int NOT NULL,
  `sotientruoc` int DEFAULT NULL,
  `sotienthaydoi` int DEFAULT NULL,
  `sotiensau` int DEFAULT NULL,
  `thoigian` datetime DEFAULT NULL,
  `noidung` text COLLATE utf8mb3_vietnamese_ci,
  `username` varchar(255) COLLATE utf8mb3_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_vietnamese_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dongtien`
--

LOCK TABLES `dongtien` WRITE;
/*!40000 ALTER TABLE `dongtien` DISABLE KEYS */;
/*!40000 ALTER TABLE `dongtien` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorite`
--

DROP TABLE IF EXISTS `favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite`
--

LOCK TABLES `favorite` WRITE;
/*!40000 ALTER TABLE `favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hmacs`
--

DROP TABLE IF EXISTS `hmacs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hmacs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hmacs`
--

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` text COLLATE utf8mb4_vietnamese_ci,
  `trans_id` text COLLATE utf8mb4_vietnamese_ci,
  `payment_method` text COLLATE utf8mb4_vietnamese_ci,
  `amount` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_vietnamese_ci,
  `status` int NOT NULL DEFAULT '0',
  `create_time` text COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licenses`
--

DROP TABLE IF EXISTS `licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `licenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `license_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hmac_id` int NOT NULL,
  `max_devices` tinyint NOT NULL DEFAULT '5',
  `revoked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `license_key` (`license_key`),
  KEY `hmac_id` (`hmac_id`),
  CONSTRAINT `licenses_ibfk_1` FOREIGN KEY (`hmac_id`) REFERENCES `hmacs` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licenses`
--

-- Table structure for table `log_balance`
--

DROP TABLE IF EXISTS `log_balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_balance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `money_before` text COLLATE utf8mb4_vietnamese_ci,
  `money_change` text COLLATE utf8mb4_vietnamese_ci,
  `money_after` text COLLATE utf8mb4_vietnamese_ci,
  `time` text COLLATE utf8mb4_vietnamese_ci,
  `content` text COLLATE utf8mb4_vietnamese_ci,
  `user_id` text COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_balance`
--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `ip` text COLLATE utf8mb4_vietnamese_ci,
  `device` text COLLATE utf8mb4_vietnamese_ci,
  `create_date` text COLLATE utf8mb4_vietnamese_ci,
  `action` text COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tieude` text COLLATE utf8mb4_general_ci NOT NULL,
  `noidung` text COLLATE utf8mb4_general_ci NOT NULL,
  `images` text COLLATE utf8mb4_general_ci,
  `code` text COLLATE utf8mb4_general_ci,
  `status` text COLLATE utf8mb4_general_ci NOT NULL,
  `create_date` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `redirect_links`
--

DROP TABLE IF EXISTS `redirect_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `redirect_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `license_id` int NOT NULL,
  `device_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_expires_at` (`expires_at`),
  KEY `license_id` (`license_id`),
  CONSTRAINT `redirect_links_ibfk_1` FOREIGN KEY (`license_id`) REFERENCES `licenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `redirect_links`
--

--
-- Table structure for table `server_cron`
--

DROP TABLE IF EXISTS `server_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `server_cron` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_vietnamese_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `limit_second` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb3_vietnamese_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_vietnamese_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server_cron`
--

LOCK TABLES `server_cron` WRITE;
/*!40000 ALTER TABLE `server_cron` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server_hosting`
--

DROP TABLE IF EXISTS `server_hosting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `server_hosting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `uname` text NOT NULL,
  `backup` text NOT NULL,
  `hostname` text NOT NULL,
  `whmusername` text NOT NULL,
  `whmpassword` text NOT NULL,
  `ip` text NOT NULL,
  `nameserver1` text NOT NULL,
  `nameserver2` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server_hosting`
--

LOCK TABLES `server_hosting` WRITE;
/*!40000 ALTER TABLE `server_hosting` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_hosting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_vietnamese_ci,
  `value` text COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

-- Table structure for table `sevice_code`
--

DROP TABLE IF EXISTS `sevice_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sevice_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sevice_code`
--


--
-- Table structure for table `tbl_his_code`
--

DROP TABLE IF EXISTS `tbl_his_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_his_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `magd` text COLLATE utf8mb4_vietnamese_ci,
  `price` int DEFAULT NULL,
  `create_date` text COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_his_code`
--


--
-- Table structure for table `tbl_his_cron`
--

DROP TABLE IF EXISTS `tbl_his_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_his_cron` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8mb3_vietnamese_ci DEFAULT NULL,
  `id_server` varchar(255) COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `url` longtext COLLATE utf8mb3_vietnamese_ci,
  `second` longtext COLLATE utf8mb3_vietnamese_ci,
  `time_his` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expired_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expired_timestamp` int DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb3_vietnamese_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_vietnamese_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_his_cron`
--

LOCK TABLES `tbl_his_cron` WRITE;
/*!40000 ALTER TABLE `tbl_his_cron` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_his_cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_his_domain`
--

DROP TABLE IF EXISTS `tbl_his_domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_his_domain` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL COMMENT 'ID người dùng đăng ký',
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên miền đăng ký',
  `nameserver1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nameserver 1',
  `nameserver2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nameserver 2',
  `years` int unsigned NOT NULL DEFAULT '1' COMMENT 'Số năm đăng ký',
  `price` bigint unsigned NOT NULL COMMENT 'Giá tiền đã thanh toán',
  `status` enum('success','fail','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Trạng thái đăng ký',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo yêu cầu',
  `update_time` datetime DEFAULT NULL COMMENT 'Thời gian cập nhật (nếu có)',
  `end_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo yêu cầu',
  `api_response` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi lại phản hồi từ API',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `domain` (`domain`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_his_domain`
--

LOCK TABLES `tbl_his_domain` WRITE;
/*!40000 ALTER TABLE `tbl_his_domain` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_his_domain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_his_hosting`
--

DROP TABLE IF EXISTS `tbl_his_hosting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_his_hosting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` text,
  `name` text,
  `price` text,
  `domain` text,
  `taikhoan` text,
  `matkhau` text,
  `email` text,
  `server` text,
  `create_date` text,
  `end_day` text,
  `status` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_his_hosting`
--

LOCK TABLES `tbl_his_hosting` WRITE;
/*!40000 ALTER TABLE `tbl_his_hosting` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_his_hosting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_his_vps`
--

DROP TABLE IF EXISTS `tbl_his_vps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_his_vps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `namevps` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` bigint NOT NULL DEFAULT '0',
  `cpu` int NOT NULL DEFAULT '1',
  `ram` int NOT NULL DEFAULT '1',
  `disk` int NOT NULL DEFAULT '10',
  `os` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','active','expired','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_his_vps`
--

LOCK TABLES `tbl_his_vps` WRITE;
/*!40000 ALTER TABLE `tbl_his_vps` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_his_vps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_list_code`
--

DROP TABLE IF EXISTS `tbl_list_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_list_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` text COLLATE utf8mb4_vietnamese_ci,
  `code` text COLLATE utf8mb4_vietnamese_ci,
  `price` int DEFAULT '0',
  `sale` int DEFAULT '0',
  `images` text COLLATE utf8mb4_vietnamese_ci,
  `list_images` text COLLATE utf8mb4_vietnamese_ci,
  `intro` longtext COLLATE utf8mb4_vietnamese_ci,
  `content` text COLLATE utf8mb4_vietnamese_ci,
  `view` bigint DEFAULT '0',
  `sold` bigint DEFAULT '0',
  `link_down` text COLLATE utf8mb4_vietnamese_ci,
  `sevice_code` longtext COLLATE utf8mb4_vietnamese_ci,
  `status` int DEFAULT '0',
  `ghim` int DEFAULT '0',
  `create_date` text COLLATE utf8mb4_vietnamese_ci,
  `update_date` text COLLATE utf8mb4_vietnamese_ci,
  `hmac_id` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_list_code`
--

--
-- Table structure for table `tbl_list_domain`
--

DROP TABLE IF EXISTS `tbl_list_domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_list_domain` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `price` text NOT NULL,
  `image` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_list_domain`
--

LOCK TABLES `tbl_list_domain` WRITE;
/*!40000 ALTER TABLE `tbl_list_domain` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_list_domain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_list_hosting`
--

DROP TABLE IF EXISTS `tbl_list_hosting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_list_hosting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cate_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `language` varchar(50) DEFAULT NULL,
  `cpmod` varchar(255) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL,
  `dungluong` varchar(100) DEFAULT NULL,
  `bangthong` varchar(100) DEFAULT NULL,
  `miencon` varchar(255) DEFAULT NULL,
  `mienkhac` varchar(255) DEFAULT NULL,
  `mienbidanh` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_list_hosting`
--

LOCK TABLES `tbl_list_hosting` WRITE;
/*!40000 ALTER TABLE `tbl_list_hosting` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_list_hosting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_list_vps`
--

DROP TABLE IF EXISTS `tbl_list_vps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_list_vps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `namevps` text COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `cpu` text COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `ram` text COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `disk` text COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `ip` text COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `price` text COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `bandwidth` text COLLATE utf8mb3_vietnamese_ci NOT NULL,
  `daban` int NOT NULL DEFAULT '0',
  `view` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_list_vps`
--

LOCK TABLES `tbl_list_vps` WRITE;
/*!40000 ALTER TABLE `tbl_list_vps` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_list_vps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` text COLLATE utf8mb4_vietnamese_ci,
  `name` text COLLATE utf8mb4_vietnamese_ci,
  `password` text COLLATE utf8mb4_vietnamese_ci,
  `email` text COLLATE utf8mb4_vietnamese_ci,
  `level` int NOT NULL DEFAULT '0',
  `seller` int NOT NULL DEFAULT '0',
  `address` text COLLATE utf8mb4_vietnamese_ci,
  `skill` text COLLATE utf8mb4_vietnamese_ci,
  `description` text COLLATE utf8mb4_vietnamese_ci,
  `profile_picture` text COLLATE utf8mb4_vietnamese_ci,
  `token` text COLLATE utf8mb4_vietnamese_ci,
  `apikey` varchar(255) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `ip` text COLLATE utf8mb4_vietnamese_ci,
  `device` text COLLATE utf8mb4_vietnamese_ci,
  `type` text COLLATE utf8mb4_vietnamese_ci,
  `otp` text COLLATE utf8mb4_vietnamese_ci,
  `discount` int NOT NULL DEFAULT '0',
  `money` int NOT NULL DEFAULT '0',
  `total_money` int NOT NULL DEFAULT '0',
  `banned` int NOT NULL DEFAULT '0',
  `create_date` text COLLATE utf8mb4_vietnamese_ci,
  `update_date` text COLLATE utf8mb4_vietnamese_ci,
  `time_session` text COLLATE utf8mb4_vietnamese_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-31 15:35:47
