<?php
class Controller {
    public function model($model) {
        // Giả định tên file model là chữ thường
        require_once ROOT_PATH . '/app/models/' . strtolower($model) . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        // Nơi tốt nhất để bắt đầu session là file index.php chính
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Luôn đảm bảo thông tin người dùng có sẵn cho view nếu đã đăng nhập
        if (!isset($data['isLoggedIn'])) {
            $data['isLoggedIn'] = isset($_SESSION['user']);
        }
        if ($data['isLoggedIn'] && !isset($data['currentUserInfo'])) {
            $userAuthModelForView = $this->model('UserAuth');
            $data['currentUserInfo'] = $userAuthModelForView->getUserByUsername($_SESSION['user']);
        }

        if (file_exists(ROOT_PATH . '/app/views/' . $view . '.php')) {
            extract($data);
            require_once ROOT_PATH . '/app/views/inc/header.php';
            require_once ROOT_PATH . '/app/views/' . $view . '.php';
            require_once ROOT_PATH . '/app/views/inc/footer.php';
        } else {
            die("View '{$view}' không tồn tại.");
        }
    }
}
