<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'app/view/inc/header.php';

switch ($page) {
    case 'shop':
        include 'app/view/shop.php';
        break;
    case 'contact':
        include 'app/view/contact.php';
        break;
    case 'about':
        include 'app/view/about.php';
        break;
    case 'shop-single':
        include 'app/view/shop-single.php';
        break;
    default:
        include 'app/view/home.php';
        break;
}
    
include 'app/view/inc/footer.php';
?>