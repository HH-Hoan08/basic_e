<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
define('BASE_URL', 'http://localhost/basic_e/');

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