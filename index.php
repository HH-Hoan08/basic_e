<?php
// File: /opt/lampp/htdocs/basic_e/index.php

// Bật hiển thị lỗi PHP trong quá trình phát triển (Chống trắng trang)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bắt đầu session ở đây, nơi duy nhất
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// [BẢO MẬT] Kiểm tra người dùng đã đăng nhập có bị khóa không trên mọi trang
if (isset($_SESSION['user'])) {
    // Nạp AdminModel để sử dụng hàm kiểm tra
    require_once __DIR__ . '/app/models/AdminModel.php';
    $adminModel = new AdminModel();

    // Nếu tài khoản bị khóa, hủy session và chuyển hướng về trang đăng nhập
    if ($adminModel->checkUserLockStatus($_SESSION['user'])) {
        session_unset();
        session_destroy();
        // Chuyển hướng với tham số 'locked=1' để hiển thị thông báo
        header("Location: http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/basic_e/index.php?page=login&locked=1");
        exit();
    }
}

// Định nghĩa các hằng số đường dẫn để sử dụng trong toàn bộ ứng dụng
define('ROOT_PATH', __DIR__); 
define('BASE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/basic_e/');
// Tải các file core của hệ thống   
require_once ROOT_PATH . '/app/core/App.php';
require_once ROOT_PATH . '/app/core/Controller.php';

// Khởi tạo ứng dụng
$app = new App();