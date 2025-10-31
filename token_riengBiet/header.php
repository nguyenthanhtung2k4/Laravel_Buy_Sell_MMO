<?php
// file: header.php
// Đảm bảo các file khác đã require db.php trước khi gọi header.php
if (!defined('ADMIN_USER')) { require_once __DIR__ . '/db.php'; }

// Yêu cầu đăng nhập cơ bản
if (!isset($_SERVER['PHP_AUTH_USER']) || !($_SERVER['PHP_AUTH_USER'] === ADMIN_USER && $_SERVER['PHP_AUTH_PW'] === ADMIN_PASS)) {
    header('WWW-Authenticate: Basic realm="License Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}
// $title phải được định nghĩa trong file gọi

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> | License Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .card { transition: all 0.3s ease; }
        .nav-link { 
            padding: 0.5rem 0; 
            border-bottom: 2px solid transparent; 
            transition: border-bottom 0.2s ease;
        }
        .nav-link:hover { border-bottom-color: #60a5fa; }
        .nav-link.active { border-bottom-color: #3b82f6; font-weight: 600; color: #1e40af; }
    </style>
</head>
<body class="p-4 sm:p-8">
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow-2xl">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Hệ thống Quản lý License</h1>
            <nav class="mt-4 flex space-x-6 border-b pb-2">
                <a href="index.php" class="nav-link <?= $current_page === 'index.php' ? 'active' : 'text-gray-600 hover:text-blue-600' ?>">Tổng quan</a>
                <a href="hmacs.php" class="nav-link <?= $current_page === 'hmacs.php' ? 'active' : 'text-gray-600 hover:text-blue-600' ?>">Quản lý HMACs</a> <a href="licenses.php" class="nav-link <?= $current_page === 'licenses.php' ? 'active' : 'text-gray-600 hover:text-blue-600' ?>">Quản lý Tokens</a>
                <a href="logs.php" class="nav-link <?= $current_page === 'logs.php' ? 'active' : 'text-gray-600 hover:text-blue-600' ?>">Kiểm soát & Logs</a>
            </nav>
        </header>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Lỗi!</strong>
                <span class="block sm:inline"> <?= $error ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($message) && !empty($message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Thành công!</strong>
                <span class="block sm:inline"> <?= $message ?></span>
            </div>
        <?php endif; ?>

        <main class="py-4"></main>