<?php
// File: /opt/lampp/htdocs/basic_e/index.php

// Bật hiển thị lỗi PHP trong quá trình phát triển (Chống trắng trang)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bắt đầu session ở đây, nơi duy nhất
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa các hằng số đường dẫn để sử dụng trong toàn bộ ứng dụng
define('ROOT_PATH', __DIR__); 
define('BASE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/basic_e/');

// Tải các file core của hệ thống
require_once ROOT_PATH . '/app/core/App.php';
require_once ROOT_PATH . '/app/core/Controller.php';

// Khởi tạo ứng dụng
$app = new App();