<?php
include_once __DIR__ . '/../models/userauth.php';
include_once __DIR__ . '/../services/MailService.php';

class PasswordController extends Controller {
    private UserAuth $userAuthModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->userAuthModel = new UserAuth();
    }

    public function forgot() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->sendResetLink();
        } else {
            $this->showForgotPasswordForm();
        }
    }

    public function reset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updatePassword();
        } else {
            $this->showResetPasswordForm();
        }
    }

    private function showForgotPasswordForm() {
        $error = $_SESSION['auth_error'] ?? null;
        $success = $_SESSION['auth_success'] ?? null;
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);

        $this->view('forgot-password', [
            'pageTitle' => 'Quên mật khẩu',
            'error' => $error,
            'success' => $success
        ]);
    }

    private function sendResetLink() {
        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Địa chỉ email không hợp lệ.';
            header('Location: ' . BASE_URL . 'index.php?page=forgot-password');
            exit();
        }

        $user = $this->userAuthModel->getUserByEmail($email);
        if ($user) {
            $token = substr(str_shuffle("0123456789"), 0, 6); // Tạo OTP 6 số
            $this->userAuthModel->createPasswordResetToken($email, $token);
            
            $mailService = new MailService();
            $sent = $mailService->sendPasswordResetEmail($email, $token);

            // Thêm đoạn này để admin có thể debug nếu email không gửi được
            if (!$sent) {
                // Ghi log lỗi để admin kiểm tra (vd: trong file xampp/apache/logs/error.log)
                error_log("Lỗi gửi email đặt lại mật khẩu cho: " . $email);
            }
        }
        
        // Sửa lỗi: Chuyển hướng người dùng đến trang nhập OTP và hiển thị thông báo
        $_SESSION['auth_success'] = 'Một mã OTP đã được gửi đến email của bạn. Vui lòng kiểm tra và nhập vào bên dưới.';
        header('Location: ' . BASE_URL . 'index.php?page=reset-password&email=' . urlencode($email));
        exit();
    }

    private function showResetPasswordForm() {
        $error = $_SESSION['auth_error'] ?? null;
        $success = $_SESSION['auth_success'] ?? null;
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);

        $this->view('reset-password', [
            'pageTitle' => 'Đặt lại mật khẩu',
            'token' => $_GET['token'] ?? '',
            'email' => $_GET['email'] ?? '',
            'error' => $error,
            'success' => $success
        ]);
    }

    private function updatePassword() {
        $email = trim($_POST['email'] ?? '');
        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $redirectUrl = BASE_URL . 'index.php?page=reset-password&email=' . urlencode($email) . '&token=' . urlencode($token);

        if (empty($email) || empty($token) || empty($password)) {
            $_SESSION['auth_error'] = 'Vui lòng điền đầy đủ thông tin.';
        } elseif ($password !== $password_confirm) {
            $_SESSION['auth_error'] = 'Mật khẩu xác nhận không khớp.';
        } elseif (strlen($password) < 6) {
            $_SESSION['auth_error'] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        } else {
            $resetRecord = $this->userAuthModel->getPasswordResetToken($token);

            if (!$resetRecord || strtolower($resetRecord['email']) !== strtolower($email)) {
                $_SESSION['auth_error'] = 'Mã OTP không hợp lệ hoặc đã hết hạn.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $success = $this->userAuthModel->updatePasswordByEmail($email, $hashed_password);

                if ($success) {
                    $this->userAuthModel->deletePasswordResetToken($email);
                    $_SESSION['auth_success'] = 'Mật khẩu của bạn đã được cập nhật thành công. Vui lòng đăng nhập lại.';
                    header('Location: ' . BASE_URL . 'index.php?page=login');
                    exit();
                } else {
                    $_SESSION['auth_error'] = 'Đã có lỗi xảy ra khi cập nhật mật khẩu. Vui lòng thử lại.';
                }
            }
        }
        header('Location: ' . $redirectUrl);
        exit();
    }
}