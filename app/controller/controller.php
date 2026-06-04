<?php
class HomeController {
    public function index() {
        // Kiểm tra xem session 'user' có tồn tại hay không
        $isLoggedIn = isset($_SESSION['user']); 

        // Nếu muốn lấy tên user ra hiển thị sau này:
        $user = $isLoggedIn ? $_SESSION['user'] : null;

        // Gọi View và truyền biến $isLoggedIn sang
        include "/app/view/home.php"; // Hoặc file nào chứa cái thanh nav của bạn
    }
}
