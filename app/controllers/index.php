<?php
// File router này đang được sử dụng theo cách cũ.
// Để các thành phần MVC như CartController và AdminController hoạt động,
// chúng ta cần nạp file Controller.php gốc của hệ thống.
include_once __DIR__ . '/../core/Controller.php';
@session_start();

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
    
    // Handle order cancellation
    if ($page === 'profile' && ($_GET['action'] ?? '') === 'cancel_order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        include_once __DIR__ . '/../models/OrderModel.php';
        $orderModel = new OrderModel();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $userId = $currentUserInfo['id']; // Lấy ID người dùng đã được truy vấn ở trên

        try {
            if ($orderModel->cancelOrder($orderId, $userId)) {
                 $_SESSION['profile_success'] = "Đã hủy đơn hàng #" . $orderId . " thành công. Hàng trong kho đã được cập nhật.";
            } else {
                 $_SESSION['profile_error'] = "Không thể hủy đơn hàng. Vui lòng liên hệ hỗ trợ.";
            }
        } catch (Exception $e) {
            $_SESSION['profile_error'] = "Lỗi hủy đơn hàng: " . $e->getMessage();
        }
        // Chuyển hướng về tab đơn hàng
        header("Location: " . BASE_URL . "index.php?page=profile#orders");
        exit();
    }

    if ($page === 'profile') {
        // Lấy thông báo từ session và gán vào biến $success, $error để view có thể hiển thị
        // Điều này cũng sẽ lấy thông báo từ các trang khác (vd: checkout thành công)
        if (isset($_SESSION['profile_success'])) {
            $success = $_SESSION['profile_success'];
            unset($_SESSION['profile_success']);
        }
        if (isset($_SESSION['profile_error'])) {
            $error = $_SESSION['profile_error'];
            unset($_SESSION['profile_error']);
        }

        $profileData = $profileController->handleProfile();
        if (!empty($profileData['error'])) $error = $profileData['error'];
        if (!empty($profileData['success'])) $success = $profileData['success'];
        $currentUserInfo = $profileData['userInfo'];
        $userOrders = $profileData['orders'];

        if (isset($_GET['action']) && $_GET['action'] === 'view_order') {
            // ProfileController sẽ tự xử lý việc render view order_detail
        }
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

// Chỉ load Header và Footer của trang khách nếu không phải là trang admin
if (strpos($page, 'admin') === false) {
    include '../views/inc/header.php';
}

switch ($page) {
    case 'shop':
        include_once './ShopController.php';
        $shopController = new ShopController();
        $shopController->index();
        break;
    case 'contact':
        include '../views/contact.php';
        break;
    case 'about':
        include '../views/about.php';
        break;
    case 'shop-single':
        include_once './ShopController.php';
        $shopController = new ShopController();
        $shopController->single();
        break;
    case 'forgot-password':
        include_once './PasswordController.php';
        $passwordController = new PasswordController();
        $passwordController->forgot();
        break;
    case 'reset-password':
        include_once './PasswordController.php';
        $passwordController = new PasswordController();
        $passwordController->reset();
        break;
    case 'login':
    case 'register':
        include '../views/auth.php';
        break;
    case 'profile':
        include '../views/profile.php';
        break;
    case 'admin':
        // Điều hướng toàn bộ chức năng admin sang AdminController
        include_once './AdminController.php';
        $adminController = new AdminController();
        $adminController->index();
        break;
    case 'cart':
        include_once './CartController.php';
        // Giờ đây, CartController có thể kế thừa từ lớp Controller đã được nạp ở trên
        // và sử dụng các phương thức như model() hoặc view() một cách chính xác.
        $cartController = new CartController();
        $cartController->i