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
        // Lấy thông báo từ session (nếu có)
        if (isset($_SESSION['profile_error'])) {
            $error = $_SESSION['profile_error'];
            unset($_SESSION['profile_error']);
        }
        $success = '';
        // [SỬA] Lấy thông báo thành công từ session (sau khi hủy đơn,...)
        if (isset($_SESSION['profile_success'])) {
            $success = $_SESSION['profile_success'];
            unset($_SESSION['profile_success']);
        }

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
                $address  = trim($_POST['address'] ?? '');
                $verifyCode = trim($_POST['verify_code'] ?? '');
                $currentUserInfo = $userModel->getUserByUsername($username);

                if ($newEmail !== $currentUserInfo['email'] && $verifyCode !== '123456') {
                    $error = "Mã xác nhận Email không chính xác! (Mã thử nghiệm: 123456)";
                } else {
                    $userModel->updateProfile($username, $fullname, $newEmail, $address);
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
        $orders = [];
        // Đảm bảo $userInfo tồn tại và có 'id' trước khi lấy đơn hàng
        // Sửa lỗi: Truyền vào user ID (int) thay vì username (string)
        if ($userInfo && isset($userInfo['id'])) {
            $orders = $userModel->getUserOrders($userInfo['id']);
        }

        // Nếu có action là 'view_order', chuyển sang xử lý chi tiết đơn hàng
        if (isset($_GET['action']) && $_GET['action'] === 'view_order') {
            return $this->viewOrder($userInfo['id']);
        }

        // [MỚI] Xử lý hủy đơn hàng từ người dùng
        if (isset($_GET['action']) && $_GET['action'] === 'cancel_order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int)($_POST['order_id'] ?? 0);
            $orderModel = $this->model('OrderModel');
            
            try {
                // Gọi model để hủy đơn, truyền cả userId để xác thực quyền sở hữu
                if ($orderModel->cancelOrder($orderId, $userInfo['id'])) {
                    $_SESSION['profile_success'] = "Đã hủy đơn hàng #{$orderId} thành công. Kho hàng đã được cập nhật.";
                } else {
                    $_SESSION['profile_error'] = "Không thể hủy đơn hàng #{$orderId}.";
                }
            } catch (Exception $e) {
                $_SESSION['profile_error'] = "Lỗi: " . $e->getMessage();
            }

            header("Location: " . BASE_URL . "index.php?page=profile#orders");
            exit();
        }

        // lấy email admin gửi cho user
        $emailModel = $this->model('AdminModel');
        $userEmails = [];
        if ($userInfo) {
            $tier = $userModel->getUserTier($userInfo['id']);
            $userEmails = $emailModel->getEmailsForUser($userInfo['username'], $tier);
        }

        // Lấy đánh giá của user
        $productModel = $this->model('ProductModel');
        $userReviews  = [];
        if ($userInfo && isset($userInfo['id'])) {
            $userReviews = $productModel->getReviewsByUser($userInfo['id']);
        }

        $this->view('profile', [
            'pageTitle' => 'Basic Shop - Tài khoản của tôi',
            'page' => 'profile',
            'error' => $error,
            'success' => $success,
            'currentUserInfo' => $userInfo,
            'userOrders' => $orders,
            'userEmails' => $userEmails,
            'userReviews' => $userReviews,
        ]);
    }

    // Phương thức để xem chi tiết một đơn hàng cụ thể
    private function viewOrder(int $userId) {
        $orderId = (int)($_GET['order_id'] ?? 0);
        $orderModel = $this->model('OrderModel');
        $error = '';
        $order = null;

        try {
            $order = $orderModel->getOrderDetails($orderId, $userId); // Truyền userId để xác thực quyền sở hữu
            if (!$order) {
                $error = "Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập.";
            }
        } catch (Exception $e) {
            $error = "Lỗi khi tải chi tiết đơn hàng: " . $e->getMessage();
        }

        $this->view('order_detail', [
            'pageTitle' => 'Chi tiết đơn hàng #' . ($orderId > 0 ? $orderId : 'N/A'),
            'order' => $order,
            'error' => $error,
        ]);
    }
}
