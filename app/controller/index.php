<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
define('BASE_URL', 'http://localhost/basic_e/');

if ($page === 'about') {
    $pageTitle = 'Basic Shop - Về Chúng Tôi';
} else if ($page === 'shop') {
    $pageTitle = 'Basic Shop - Cửa Hàng';
} else if ($page === 'contact') {
    $pageTitle = 'Basic Shop - Liên Hệ';
} else {
    $pageTitle = 'Basic Shop';
}

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
    default:
        include '../view/home.php';
        break;
}
    
include '../view/inc/footer.php';
?>