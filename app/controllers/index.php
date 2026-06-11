<?php
session_start();

define('BASE_URL', 'http://localhost/basic_e/');
$isLoggedIn = isset($_SESSION['user']);

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include '../models/xl_data.php';
$db = new xl_data();

include './AuthController.php';
include './profile_controller.php';

$modelUser = new AuthController();
$profileController = new ProfileController();

// Xử lý Đăng xuất
if ($page === 'logout') {
    $modelUser->logout();
}

// Xử lý Đăng ký và Đăng nhập
$authResult = $modelUser->handleAuth($page);
$error = $authResult['error'];
$success = $authResult['success'];

// Khai báo biến chung cho Header và Profile
$currentUserInfo = null;
$userOrders = [];

if ($isLoggedIn) {
    // Lấy thông tin user hiện tại để hiển thị Avatar trên Header
    $userAuthModel = new UserAuth();
    $currentUserInfo = $userAuthModel->getUserByUsername($_SESSION['user']);
    
    if ($page === 'profile') {
        $profileData = $profileController->handleProfile();
        if (!empty($profileData['error'])) $error = $profileData['error'];
        if (!empty($profileData['success'])) $success = $profileData['success'];
        $currentUserInfo = $profileData['userInfo'];
        $userOrders = $profileData['orders'];
    }
}

if(isset($GLOBALS['page'])) {
    $page = $GLOBALS['page'];
}

if ($page === 'about') {
    $pageTitle = 'Basic Shop - Về Chúng Tôi';
} else if ($page === 'shop') {
    $pageTitle = 'Basic Shop - Cửa Hàng';
} else if ($page === 'contact') {
    $pageTitle = 'Basic Shop - Liên Hệ';
} else if ($page === 'login' || $page === 'register') {
    $pageTitle = 'Basic Shop - Xác thực';
} else if ($page === 'profile') {
    $pageTitle = 'Basic Shop - Tài khoản của tôi';
} else {
    $pageTitle = 'Basic Shop';
}

//Giao diện

include '../views/inc/header.php';

switch ($page) {
    case 'shop':
        include '../views/shop.php';
        break;
    case 'contact':
        include '../views/contact.php';
        break;
    case 'about':
        include '../views/about.php';
        break;
    case 'shop-single':
        include '../views/shop-single.php';
        break;
    case 'login':
    case 'register':
        include '../views/auth.php';
        break;
    case 'profile':
        include '../views/profile.php';
        break;
    default:
        include '../views/home.php';
        break;
}

include '../views/inc/footer.php';
?>