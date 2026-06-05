<?php
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
define('BASE_URL', 'http://localhost/basic_e/');

include '../models/xl_data.php';
$db = new xl_data();

$error = '';
$success = '';

// Xử lý Đăng xuất
if ($page === 'logout') {
    unset($_SESSION['user']);
    header("Location: " . BASE_URL . "index.php");
    exit();
}

// Xử lý Đăng ký và Đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($page === 'register') {
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $check = $db->read_item("SELECT * FROM users WHERE username = ? OR email = ?", [$username, $email]);
        if (count($check) > 0) {
            $error = "Tên đăng nhập hoặc Email đã được sử dụng!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $res = $db->execute_item("INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)", [$fullname, $email, $username, $hashed_password]);
            if ($res) {
                $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                $page = 'login';
                $_GET['page'] = 'login'; // Chuyển form đăng nhập
            } else {
                $error = "Đã xảy ra lỗi, vui lòng thử lại!";
            }
        }
    } elseif ($page === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $db->read_item("SELECT * FROM users WHERE username = ? OR email = ?", [$username, $username]);
        if (count($user) > 0 && password_verify($password, $user[0]['password'])) {
            $_SESSION['user'] = $user[0]['username']; // Lưu phiên đăng nhập
            header("Location: " . BASE_URL . "index.php"); // Về trang chủ
            exit();
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không chính xác!";
        }
    }
}

if ($page === 'about') {
    $pageTitle = 'Basic Shop - Về Chúng Tôi';
} else if ($page === 'shop') {
    $pageTitle = 'Basic Shop - Cửa Hàng';
} else if ($page === 'contact') {
    $pageTitle = 'Basic Shop - Liên Hệ';
} else if ($page === 'login' || $page === 'register') {
    $pageTitle = 'Basic Shop - Xác thực';
} else {
    $pageTitle = 'Basic Shop';
}

$isLoggedIn = isset($_SESSION['user']);

include '../view/inc/header.php';

switch ($page) {
    case 'shop':
        include '../view/shop.php';
        break;
    case 'contact':
        include '../view/contact.php';
        break;
    case 'about':
        include '../view/about.php';
        break;
    case 'shop-single':
        include '../view/shop-single.php';
        break;
    case 'login':
    case 'register':
        include '../view/auth.php';
        break;
    default:
        include '../view/home.php';
        break;
}
    
include '../view/inc/footer.php';
?>