<?php
class ProfileController extends Controller {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "?page=login");
            exit();
        }

        $userModel = $this->model('UserAuth');
        $username = $_SESSION['user'];
        
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['btn_update_avatar'])) {
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = ROOT_PATH . '/assets/img/avatars/'; 
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    
                    $fileName = time() . '_' . basename($_FILES['avatar']['name']);
                    $targetFilePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFilePath)) {
                        $userModel->updateAvatar($username, $fileName);
                        $success = "Cập nhật ảnh đại diện thành công!";
                    } else {
                        $error = "Có lỗi xảy ra khi tải ảnh lên.";
                    }
                }
            }

            if (isset($_POST['btn_update_info'])) {
                $fullname = trim($_POST['fullname']);
                $newEmail = trim($_POST['email']);
                $verifyCode = trim($_POST['verify_code'] ?? '');
                $currentUserInfo = $userModel->getUserByUsername($username);

                if ($newEmail !== $currentUserInfo['email'] && $verifyCode !== '123456') {
                    $error = "Mã xác nhận Email không chính xác! (Mã thử nghiệm: 123456)";
                } else {
                    $userModel->updateProfile($username, $fullname, $newEmail);
                    $success = "Cập nhật thông tin cá nhân thành công!";
                }
            }

            if (isset($_POST['btn_update_password'])) {
                $oldPass = $_POST['old_password'];
                $newPass = $_POST['new_password'];
                $confirmPass = $_POST['confirm_password'];
                $currentUserInfo = $userModel->getUserByUsername($username);

                if ($newPass !== $confirmPass) {
                    $error = "Mật khẩu xác nhận không khớp!";
                } elseif (password_verify($oldPass, $currentUserInfo['password'])) {
                    $hashed = password_hash($newPass, PASSWORD_DEFAULT);
                    $userModel->updatePassword($username, $hashed);
                    $success = "Đổi mật khẩu thành công!";
                } else {
                    $error = "Mật khẩu hiện tại không đúng!";
                }
            }
        }

        $userInfo = $userModel->getUserByUsername($username);
        $orders = $userModel->getUserOrders($username);

        $this->view('profile', [
            'pageTitle' => 'Basic Shop - Tài khoản của tôi',
            'page' => 'profile',
            'error' => $error,
            'success' => $success,
            'currentUserInfo' => $userInfo,
            'userOrders' => $orders
        ]);
    }
}
