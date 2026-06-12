<?php
class AuthController extends Controller {

    public function login() {
        if (isset($_SESSION['user'])) {
            header("Location: " . BASE_URL);
            exit();
        }

        $userModel = $this->model('userauth');
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user = $userModel->getUser($username, $username);

            if (!empty($user) && password_verify($password, $user[0]['password'])) {
                $_SESSION['user'] = $user[0]['username'];
                $_SESSION['role'] = $user[0]['role'] ?? 'user';
                header("Location: " . BASE_URL);
                exit();
            } else {
                $error = 'Đăng nhập thất bại do mật khẩu hoặc người dùng không tồn tại';
            }
        }

        $this->view('auth', [
            'page' => 'login',
            'pageTitle' => 'Basic Shop - Xác thực',
            'error' => $error,
            'success' => $success
        ]);
    }

    public function register() {
        if (isset($_SESSION['user'])) {
            header("Location: " . BASE_URL);
            exit();
        }

        $userModel = $this->model('userauth');
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $existingUser = $userModel->getUser($username, $email);
            if (!empty($existingUser)) {
                $error = 'Tên người dùng hoặc email đã tồn tại';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                if ($userModel->insertUser($fullname, $email, $username, $hashed_password)) {
                    header("Location: " . BASE_URL . "?page=login&success=1");
                    exit();
                } else {
                    $error = 'Đăng ký thất bại';
                }
            }
        }

        $this->view('auth', [
            'page' => 'register',
            'pageTitle' => 'Basic Shop - Xác thực',
            'error' => $error,
            'success' => $success
        ]);
    }

    public function logout() {
        session_unset();    // Xóa tất cả các biến trong session (bao gồm cart, role...)
        session_destroy();  // Phá hủy hoàn toàn phiên làm việc hiện tại
        header("Location: " . BASE_URL);
        exit();
    }
}
