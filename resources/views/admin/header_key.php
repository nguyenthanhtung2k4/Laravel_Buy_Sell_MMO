<?php 
// file: Header.php
require_once __DIR__ . '/../../../core/is_user.php';

// Kiểm tra login và quyền admin như trong file cũ
if(empty($getUser['username'])) {
    include($_SERVER['DOCUMENT_ROOT'].'/resources/views/errors/404.php');
    die();
}
if ($getUser['level'] != 1) {
    include($_SERVER['DOCUMENT_ROOT'].'/resources/views/errors/404.php');
    die();
}

if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$title?></title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> 
    
    <script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <link class="main-stylesheet" href="<?=BASE_URL('public/assets/');?>cute/cute-alert.css" rel="stylesheet"
        type="text/css">
    <script src="<?=BASE_URL('public/assets/');?>cute/cute-alert.js" defer></script>
    <script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/jquery/jquery.min.js"></script>
    <style>
    /* Reset các style cũ để Tailwind hoạt động tốt hơn */
    .table-wrapper {
        /* Loại bỏ CSS cũ nếu dùng Tailwind */
        max-height: none !important; 
        overflow-y: visible !important;
    }
    /* Thêm các style cố định cho header (fixed) nếu cần trong Tailwind */
    .header-fixed {
        position: sticky;
        top: 0;
        z-index: 50;
    }
    </style>
    
</head>
<body class="bg-gray-100 font-sans antialiased">

<header class="bg-white shadow-md header-fixed">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="<?=BASE_URL('admin');?>" class="text-xl font-bold text-gray-900 flex items-center">
                    <img src="<?=BASE_URL('');?>public/tailieu/theme/assets/images/media/world.svg" alt="logo" class="h-8 w-8 mr-2">
                    <span class="hidden sm:inline">Admin Dashboard</span>
                </a>
            </div>

            <div class="flex items-center md:hidden">
                <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
            
            <div class="hidden md:flex items-center space-x-4">
                <span class="text-sm text-gray-700">Xin chào, <?=h($getUser['username'])?></span>
                <a href="<?=BASE_URL('logout');?>" class="px-3 py-1 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 transition duration-150">Đăng Xuất</a>
            </div>
        </div>
    </div>
</header>

<div class="flex">