<?php
class OrdersController extends Controller {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "?page=login");
            exit();
        }

        $userModel = $this->model('UserAuth');
        $username = $_SESSION['user'];
        
        $orders = $userModel->getUserOrders($username);

        $this->view('orders', [
            'pageTitle' => 'Basic Shop - Đơn hàng của tôi',
            'page' => 'orders',
            'userOrders' => $orders
        ]);
    }
}
