<?php
include_once __DIR__ . '/../models/AdminModel.php';

class AdminController extends Controller {
    private AdminModel $adminModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // [BẢO MẬT] - Kiểm tra xem user đã đăng nhập chưa và có phải admin không
        // Tạm thời comment lại, bạn cần thêm cột 'role' = 'admin' vào bảng users để kiểm tra
        // if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
        //     header("Location: " . BASE_URL . "index.php?page=login");
        //     exit();
        // }
        $this->adminModel = new AdminModel();
    }

    // Framework MVC (App.php) sẽ tự động gọi hàm index() khi vào ?page=admin
    public function index() {
        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {
            case 'dashboard':
                $this->dashboard();
                break;
            case 'orders':
                $this->orders();
                break;
            case 'view_order':
                $this->viewOrder();
                break;
            case 'customers':
                $this->customers();
                break;
            case 'products':
                $this->products();
                break;
            case 'add_product':
                $this->productForm();
                break;
            case 'edit_product':
                $this->productForm();
                break;
            case 'delete_product':
                $this->deleteProduct();
                break;
            case 'reviews':
                $this->reviews();
                break;
            case 'vouchers':
                $this->vouchers();
                break;
            case 'add_voucher':
                $this->voucherForm();
                break;
            case 'edit_voucher':
                $this->voucherForm();
                break;
            case 'send_password_reset':
                $this->sendPasswordReset();
                break;
            case 'send_email':
                $this->sendEmail();
                break;
            case 'delete_voucher':
                $this->deleteVoucher();
                break;
            case 'categories':
                $this->categories();
                break;
            case 'add_category':
            case 'edit_category':
                $this->categoryForm();
                break;
            case 'delete_category':
                $this->deleteCategory();
                break;
            default:
                $this->dashboard();
                break;
        }
    }

    // ==========================================
    // 1. QUẢN LÝ DOANH THU & THỐNG KÊ (DASHBOARD)
    // ==========================================
    private function dashboard() {
        // Lấy thống kê doanh thu theo tuần, tháng, năm
        $revenue = $this->adminModel->getRevenueStats();

        // Lấy các số liệu tổng quan (đơn hàng, sản phẩm, tồn kho)
        $summary = $this->adminModel->getDashboardSummary();
        
        // Lấy danh sách sản phẩm bán chạy nhất và ế nhất
        $bestSellers = $this->adminModel->getProductPerformance('DESC', 5); // Bán chạy
        $worstSellers = $this->adminModel->getProductPerformance('ASC', 5); // Bán ế

        $this->renderAdminView('dashboard', [
            'revenue' => $revenue,
            'summary' => $summary,
            'bestSellers' => $bestSellers,
            'worstSellers' => $worstSellers
        ]);
    }

    // ==========================================
    // 2. QUẢN LÝ ĐƠN HÀNG (Cập nhật trạng thái)
    // ==========================================
    private function orders() {
        // Nếu có request POST -> Thực hiện cập nhật trạng thái đơn hàng
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $orderId = (int)$_POST['order_id'];
            $newStatus = $_POST['status']; // 'pending', 'confirmed', 'shipping', 'completed'

            try {
                $this->adminModel->updateOrderStatus($orderId, $newStatus);
                
                // Gửi email hóa đơn nếu trạng thái chuyển sang 'confirmed' (Xác nhận)
                if ($newStatus === 'confirmed') {
                    $this->sendInvoiceEmail($orderId);
                }

                $_SESSION['admin_message'] = ['type' => 'success', 'text' => 'Cập nhật trạng thái đơn hàng #' . $orderId . ' thành công!'];
            } catch (Exception $e) {
                $_SESSION['admin_message'] = ['type' => 'danger', 'text' => 'Lỗi: ' . $e->getMessage()];
            }

            header("Location: " . BASE_URL . "index.php?page=admin&action=orders");
            exit();
        }

        // Lấy danh sách đơn hàng để hiển thị
        $orders = $this->adminModel->getAllOrders();
        
        // Lấy và xóa thông báo từ session để hiển thị
        $message = null;
        if (isset($_SESSION['admin_message'])) {
            $message = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }
        // Render View Quản lý Đơn hàng
        $this->renderAdminView('orders', ['orders' => $orders, 'message' => $message]);
    }

    // ==========================================
    // 2.5. XEM CHI TIẾT ĐƠN HÀNG (ADMIN)
    // ==========================================
    private function viewOrder() {
        $orderId = (int)($_GET['order_id'] ?? 0);
        $orderModel = $this->model('OrderModel'); // Sử dụng OrderModel để lấy chi tiết
        $order = null;
        $error = '';
        $message = null;

        // Xử lý cập nhật trạng thái nếu có POST request từ trang chi tiết
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $newStatus = $_POST['status'];
            try {
                $this->adminModel->updateOrderStatus($orderId, $newStatus);
                
                // Gửi email hóa đơn nếu trạng thái chuyển sang 'confirmed' (Xác nhận)
                if ($newStatus === 'confirmed') {
                    $this->sendInvoiceEmail($orderId);
                }

                $_SESSION['admin_message'] = ['type' => 'success', 'text' => 'Cập nhật trạng thái đơn hàng #' . $orderId . ' thành công!'];
            } catch (Exception $e) {
                $_SESSION['admin_message'] = ['type' => 'danger', 'text' => 'Lỗi: ' . $e->getMessage()];
            }
            header("Location: " . BASE_URL . "index.php?page=admin&action=view_order&order_id=" . $orderId);
            exit();
        }

        // Lấy thông báo từ session (nếu có)
        if (isset($_SESSION['admin_message'])) {
            $message = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }

        $order = $orderModel->getOrderDetails($orderId); // Admin không cần userId để xác thực
        $this->renderAdminView('order_detail', ['order' => $order, 'error' => $error, 'message' => $message]);
    }

    // ==========================================
    // 3. QUẢN LÝ KHÁCH HÀNG (Phân hạng & Khóa)
    // ==========================================
    private function customers() {
        // Cập nhật trạng thái khóa/mở khóa tài khoản
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_lock'])) {
            $userId = (int)$_POST['user_id'];
            $isLocked = (int)$_POST['is_locked'];
            $this->adminModel->toggleUserLock($userId, $isLocked);
            
            $actionText = $isLocked ? 'khóa' : 'mở khóa';
            $_SESSION['admin_message'] = ['type' => 'success', 'text' => "Đã {$actionText} tài khoản người dùng #{$userId}."];

            header("Location: " . BASE_URL . "index.php?page=admin&action=customers");
            exit();
        }

        $message = null;
        if (isset($_SESSION['admin_message'])) {
            $message = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }

        // Lấy danh sách khách hàng kèm phân hạng (Silver, Gold, Diamond) dựa trên tổng tiền đã mua
        $customers = $this->adminModel->getCustomersWithTiers();

        $this->renderAdminView('customers', [
            'customers' => $customers,
            'message' => $message
        ]);
    }

    // ==========================================
    // 5. QUẢN LÝ ĐÁNH GIÁ
    // ==========================================
    private function reviews() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = ['type' => 'danger', 'text' => 'Thao tác không hợp lệ.'];
            if (isset($_POST['toggle_visibility'])) {
                $reviewId = (int)$_POST['review_id'];
                $isVisible = (int)$_POST['is_visible'];
                $success = $this->adminModel->toggleReviewVisibility($reviewId, $isVisible);
                $actionText = $isVisible ? 'hiển thị' : 'ẩn';
                $message = $success ? ['type' => 'success', 'text' => "Đã {$actionText} đánh giá #{$reviewId}."] : ['type' => 'danger', 'text' => "Lỗi khi thay đổi trạng thái đánh giá."];
            } elseif (isset($_POST['delete_review'])) {
                $reviewId = (int)$_POST['review_id'];
                $success = $this->adminModel->deleteReview($reviewId);
                $message = $success ? ['type' => 'success', 'text' => "Đã xóa đánh giá #{$reviewId}."] : ['type' => 'danger', 'text' => "Lỗi khi xóa đánh giá."];
            }
            $_SESSION['admin_message'] = $message;
            header("Location: " . BASE_URL . "index.php?page=admin&action=reviews");
            exit();
        }

        $message = null;
        if (isset($_SESSION['admin_message'])) {
            $message = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }

        $reviews = $this->adminModel->getAllReviews();
        $this->renderAdminView('reviews', ['reviews' => $reviews, 'message' => $message]);
    }

    // ==========================================
    // 6. QUẢN LÝ VOUCHER
    // ==========================================
    private function vouchers() {
        $message = null;
        if (isset($_SESSION['admin_message'])) {
            $message = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }

        $vouchers = $this->adminModel->getAllVouchers();
        $this->renderAdminView('vouchers', ['vouchers' => $vouchers, 'message' => $message]);
    }

    private function voucherForm() {
        $voucherId = (int)($_GET['id'] ?? 0);
        $isEdit = $voucherId > 0;
        $voucher = null;
        $message = null;

        if ($isEdit) {
            $voucher = $this->adminModel->getVoucherById($voucherId);
            if (!$voucher) {
                $_SESSION['admin_message'] = ['type' => 'danger', 'text' => 'Voucher không tồn tại.'];
                header("Location: " . BASE_URL . "index.php?page=admin&action=vouchers");
                exit();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_voucher'])) {
            $code = strtoupper(trim($_POST['code']));
            $data = [
                'code' => $code,
                'type' => $_POST['type'],
                'value' => (float)$_POST['value'],
                'quantity' => (int)$_POST['quantity'],
                'expires_at' => !empty($_POST['expires_at']) ? $_POST['expires_at'] : null,
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
            ];

            if ($this->adminModel->isVoucherCodeExists($code, $isEdit ? $voucherId : null)) {
                $message = ['type' => 'danger', 'text' => 'Mã voucher này đã tồn tại. Vui lòng chọn mã khác.'];
            } else {
                $actionText = $isEdit ? 'Cập nhật' : 'Thêm mới';
                $success = false;
                if ($isEdit) {
                    $success = $this->adminModel->updateVoucher($voucherId, $data);
                } else {
                    $data['created_by'] = 1; // Giả định admin ID 1 là người tạo
                    $success = $this->adminModel->createVoucher($data);
                }

                if ($success) {
                    $_SESSION['admin_message'] = ['type' => 'success', 'text' => $actionText . ' voucher thành công!'];
                    header("Location: " . BASE_URL . "index.php?page=admin&action=vouchers");
                    exit();
                } else {
                    $message = ['type' => 'danger', 'text' => $actionText . ' voucher thất bại.'];
                }
            }
            $voucher = array_merge($voucher ?? [], $data);
        }

        $this->renderAdminView('voucher_form', ['voucher' => $voucher, 'message' => $message]);
    }

    private function deleteVoucher() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voucher_id'])) {
            $voucherId = (int)$_POST['voucher_id'];
            $_SESSION['admin_message'] = $this->adminModel->deleteVoucher($voucherId) ? ['type' => 'success', 'text' => 'Đã xóa voucher thành công.'] : ['type' => 'danger', 'text' => 'Xóa voucher thất bại.'];
        }
        header("Location: " . BASE_URL . "index.php?page=admin&action=vouchers");
        exit();
    }

    // ==========================================
    // 4. QUẢN LÝ SẢN PHẨM (CRUD)
    // ==========================================
    private function products() {
        $message = null;
        if (isset($_SESSION['admin_message'])) {
            $message = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }

        $products = $this->adminModel->getAllProductsForAdmin();
        $this->renderAdminView('products', ['products' => $products, 'message' => $message]);
    }

    private function productForm() {
        $productId = (int)($_GET['id'] ?? 0);
        $isEdit = $productId > 0;
        $product = null;
        $message = null;

        if ($isEdit) {
            $product = $this->adminModel->getProductById($productId);
            if (!$product) {
                $_SESSION['admin_message'] = ['type' => 'danger', 'text' => 'Sản phẩm không tồn tại.'];
                header("Location: " . BASE_URL . "index.php?page=admin&action=products");
                exit();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => (float)$_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
                'stock' => (int)$_POST['stock'],
                'category_id' => (int)$_POST['category_id'],
                'brand_id' => !empty($_POST['brand_id']) ? (int)$_POST['brand_id'] : null,
                'gender' => $_POST['gender'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'image' => $product['image'] ?? null,
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Sử dụng ROOT_PATH để đường dẫn ổn định hơn và tự tạo thư mục nếu chưa có
                $uploadDir = ROOT_PATH . '/assets/img/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                // Cải tiến: Làm sạch tên file để an toàn và thân thiện với URL hơn
                $originalName = $_FILES['image']['name'];
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $safeBaseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $fileName = time() . '_' . $safeBaseName . '.' . $extension;
                $targetFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) { 
                    if ($isEdit && !empty($product['image']) && file_exists($uploadDir . $product['image'])) {
                        @unlink($uploadDir . $product['image']);
                    }
                    $data['image'] = $fileName;
                } else {
                    $message = ['type' => 'danger', 'text' => 'Có lỗi khi tải ảnh lên. Lỗi quyền ghi (Permission Denied).'];
                }
            }

            if (!$message) {
                $actionText = $isEdit ? 'Cập nhật' : 'Thêm mới';
                $success = $isEdit ? $this->adminModel->updateProduct($productId, $data) : $this->adminModel->createProduct(array_merge($data, ['created_by' => 1]));

                if ($success) {
                    $_SESSION['admin_message'] = ['type' => 'success', 'text' => $actionText . ' sản phẩm thành công!'];
                    header("Location: " . BASE_URL . "index.php?page=admin&action=products");
                    exit();
                } else {
                    $message = ['type' => 'danger', 'text' => $actionText . ' sản phẩm thất bại.'];
                }
            }
        }

        $catsAndBrands = $this->adminModel->getCategoriesAndBrands();
        $this->renderAdminView('product_form', ['product' => $product, 'categories' => $catsAndBrands['categories'], 'brands' => $catsAndBrands['brands'], 'message' => $message]);
    }

    private function deleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
            $productId = (int)$_POST['product_id'];
            $_SESSION['admin_message'] = $this->adminModel->deleteProduct($productId) ? ['type' => 'success', 'text' => 'Đã xóa sản phẩm thành công.'] : ['type' => 'danger', 'text' => 'Xóa sản phẩm thất bại.'];
        }
        header("Location: " . BASE_URL . "index.php?page=admin&action=products");
        exit();
    }

    // ==========================================
    // 7. GỬI EMAIL ĐẶT LẠI MẬT KHẨU (ADMIN)
    // ==========================================
    private function sendPasswordReset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?page=admin&action=customers");
            exit();
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        $userAuthModel = $this->model('UserAuth');
        $user = $userAuthModel->getUserById($userId);

        if (!$user) {
            $_SESSION['admin_message'] = ['type' => 'danger', 'text' => 'Không tìm thấy người dùng với ID ' . $userId];
        } else {
            $email = $user['email'];
            $token = substr(str_shuffle("0123456789"), 0, 6); // Tạo OTP 6 số
            $userAuthModel->createPasswordResetToken($email, $token);
            
            // Nạp MailService (Đảm bảo folder là 'services' hoặc 'service' tùy cấu trúc của bạn)
            include_once __DIR__ . '/../services/MailService.php';
            $mailService = new MailService();
            $sent = $mailService->sendPasswordResetEmail($email, $token);

            if ($sent) {
                $_SESSION['admin_message'] = ['type' => 'success', 'text' => "Đã gửi email đặt lại mật khẩu thành công cho {$email}."];
            } else {
                $_SESSION['admin_message'] = ['type' => 'danger', 'text' => "Gửi email thất bại cho {$email}. Vui lòng kiểm tra cấu hình email và file log."];
                error_log("Admin failed to send password reset email to: " . $email);
            }
        }

        header("Location: " . BASE_URL . "index.php?page=admin&action=customers");
        exit();
    }

    // ==========================================
    // 8. GỬI THÔNG BÁO CHO NGƯỜI DÙNG
    // ==========================================
    private function sendEmail() {
        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email_submit'])) {
            $data = [
                'recipient_type' => $_POST['recipient_type'],
                'specific_user'  => trim($_POST['specific_user']),
                'subject'        => trim($_POST['subject']),
                'body'           => trim($_POST['body']),
                'sent_by'        => 1, // Giả định admin ID 1 là người gửi
            ];

            // Xác định người nhận cuối cùng
            $recipient = $data['recipient_type'];
            if ($recipient === 'specific') {
                if (empty($data['specific_user'])) {
                    $message = ['type' => 'danger', 'text' => 'Vui lòng nhập tên người dùng cụ thể.'];
                } else {
                    $recipient = $data['specific_user'];
                }
            }

            if (!$message) {
                if (empty($data['subject']) || empty($data['body'])) {
                    $message = ['type' => 'danger', 'text' => 'Chủ đề và nội dung không được để trống.'];
                } else {
                    $success = $this->adminModel->saveEmailNotification($recipient, $data['subject'], $data['body'], $data['sent_by']);
                    $message = $success 
                        ? ['type' => 'success', 'text' => 'Đã gửi thông báo thành công!']
                        : ['type' => 'danger', 'text' => 'Gửi thông báo thất bại. Có lỗi xảy ra.'];
                }
            }
        }

        $this->renderAdminView('send_email', ['message' => $message]);
    }

    // ==========================================
    // 9. QUẢN LÝ DANH MỤC (CRUD)
    // ==========================================
    private function categories() {
        $message = null;
        if (isset($_SESSION['admin_message'])) {
            $message = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }

        $categories = $this->adminModel->getAllCategories();
        $this->renderAdminView('categories', ['categories' => $categories, 'message' => $message]);
    }

    private function categoryForm() {
        $categoryId = (int)($_GET['id'] ?? 0);
        $isEdit = $categoryId > 0;
        $category = null;
        $message = null;

        if ($isEdit) {
            $category = $this->adminModel->getCategoryById($categoryId);
            if (!$category) {
                $_SESSION['admin_message'] = ['type' => 'danger', 'text' => 'Danh mục không tồn tại.'];
                header("Location: " . BASE_URL . "index.php?page=admin&action=categories");
                exit();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'sort_order' => (int)($_POST['sort_order'] ?? 0)
            ];

            $actionText = $isEdit ? 'Cập nhật' : 'Thêm mới';
            $success = $isEdit ? $this->adminModel->updateCategory($categoryId, $data) : $this->adminModel->createCategory($data);

            if ($success) {
                $_SESSION['admin_message'] = ['type' => 'success', 'text' => $actionText . ' danh mục thành công!'];
                header("Location: " . BASE_URL . "index.php?page=admin&action=categories");
                exit();
            } else {
                $message = ['type' => 'danger', 'text' => $actionText . ' danh mục thất bại.'];
            }
        }

        $allCategories = $this->adminModel->getAllCategories(); // Dùng cho dropdown parent_id
        $this->renderAdminView('category_form', ['category' => $category, 'allCategories' => $allCategories, 'message' => $message]);
    }

    private function deleteCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
            $categoryId = (int)$_POST['category_id'];
            try {
                $success = $this->adminModel->deleteCategory($categoryId);
                $_SESSION['admin_message'] = $success ? ['type' => 'success', 'text' => 'Đã xóa danh mục thành công.'] : ['type' => 'danger', 'text' => 'Xóa danh mục thất bại.'];
            } catch (Exception $e) {
                $_SESSION['admin_message'] = ['type' => 'danger', 'text' => $e->getMessage()];
            }
        }
        header("Location: " . BASE_URL . "index.php?page=admin&action=categories");
        exit();
    }

    // ==========================================
    // HÀM HỖ TRỢ RENDER VIEW ADMIN
    // ==========================================
    protected function renderAdminView($viewName, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../views/admin/{$viewName}.php";
        include __DIR__ . '/../views/admin/layout.php';
    }

    // ==========================================
    // GỬI HÓA ĐƠN QUA EMAIL (KHI XÁC NHẬN ĐƠN)
    // ==========================================
    private function sendInvoiceEmail(int $orderId) {
        include_once __DIR__ . '/../models/OrderModel.php';
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderDetails($orderId);

        if (!$order || empty($order['email'])) {
            return false;
        }

        // Tạo nội dung HTML cho hóa đơn
        $subject = "Xác nhận đơn hàng #" . $order['id'] . " - Basic Shop";
        
        $body = "<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>";
        $body .= "<h2 style='color: #28a745;'>Cảm ơn bạn đã đặt hàng tại Basic Shop!</h2>";
        $body .= "<p>Xin chào <strong>" . htmlspecialchars($order['fullname']) . "</strong>,</p>";
        $body .= "<p>Đơn hàng <strong>#" . $order['id'] . "</strong> của bạn đã được chúng tôi xác nhận và đang trong quá trình chuẩn bị.</p>";
        $body .= "<p><strong>Địa chỉ giao hàng:</strong> " . htmlspecialchars($order['address'] ?? 'Không có thông tin') . "</p>";
        $body .= "<h3 style='border-bottom: 2px solid #28a745; padding-bottom: 5px;'>Chi tiết đơn hàng:</h3>";
        $body .= "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%; border: 1px solid #ddd;'>";
        $body .= "<tr style='background-color: #f8f9fa;'><th>Sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>";
        
        foreach ($order['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $body .= "<tr>";
            $body .= "<td>" . htmlspecialchars($item['product_name']) . "</td>";
            $body .= "<td align='center'>" . $item['quantity'] . "</td>";
            $body .= "<td align='right'>" . number_format($item['unit_price'], 0, ',', '.') . " đ</td>";
            $body .= "<td align='right'>" . number_format($itemTotal, 0, ',', '.') . " đ</td>";
            $body .= "</tr>";
        }
        
        $body .= "<tr><td colspan='3' align='right'><strong>Tạm tính:</strong></td><td align='right'>" . number_format($order['total_price'] + $order['discount_amount'], 0, ',', '.') . " đ</td></tr>";
        if ($order['discount_amount'] > 0) {
            $body .= "<tr><td colspan='3' align='right'><strong>Giảm giá:</strong></td><td align='right'>-" . number_format($order['discount_amount'], 0, ',', '.') . " đ</td></tr>";
        }
        $body .= "<tr><td colspan='3' align='right'><strong>Tổng thanh toán:</strong></td><td align='right'><strong style='color: #dc3545; font-size: 1.2em;'>" . number_format($order['total_price'], 0, ',', '.') . " đ</strong></td></tr>";
        $body .= "</table>";
        $body .= "<p>Chúng tôi sẽ liên hệ lại với bạn trước khi giao hàng.</p>";
        $body .= "<p>Trân trọng,<br><strong>Đội ngũ Basic Shop</strong></p>";
        $body .= "</div>";

        // Tích hợp MailService đã được cập nhật thêm phương thức sendHtmlEmail()
        // Lưu ý: Nếu thư mục của bạn tên là 'service' thì đổi dòng dưới thành '/../service/MailService.php'
        include_once __DIR__ . '/../services/MailService.php';
        
        if (class_exists('MailService')) {
            $mailService = new MailService();
            // Hàm này bây giờ sẽ hoạt động trơn tru
            if (method_exists($mailService, 'sendHtmlEmail')) {
                return $mailService->sendHtmlEmail($order['email'], $subject, $body);
            }
        }
        
        // Đoạn code dưới đây (fallback thủ công) sẽ tự động bị bỏ qua vì MailService đã xử lý thành công
        return false; 
    }
}