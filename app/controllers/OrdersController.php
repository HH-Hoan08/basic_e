<?php
class OrdersController extends Controller {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "?page=login");
            exit();
        }

        // Yêu cầu mới: Hợp nhất trang đơn hàng vào trang cá nhân.
        // Chuyển hướng tất cả các truy cập vào trang đơn hàng cũ sang tab đơn hàng của trang cá nhân.
        header("Location: " . BASE_URL . "index.php?page=profile#orders");
        exit();
    }
}
