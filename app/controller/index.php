<?php
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
define('BASE_URL', 'http://localhost/basic_e/');

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