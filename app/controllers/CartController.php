<?php
class CartController extends Controller {
    public function index() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu có action trên URL
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action === 'add') {
                $this->add();
                return;
            } elseif ($action === 'remove') {
                $this->remove();
                return;
            } elseif ($action === 'update') {
                $this->update();
                return;
            } elseif ($action === 'checkout') {
                $this->checkout();
                return;
            } elseif ($action === 'apply_voucher') {
                $this->applyVoucher();
                return;
            } elseif ($action === 'remove_voucher') {
                $this->removeVoucher();
                return;
            }
        }

        // Lấy thông báo (nếu có) và xóa ngay khỏi session để không hiện lại lúc f5
        $success = null;
        $error = null;
        if (isset($_SESSION['cart_success'])) {
            $success = $_SESSION['cart_success'];
            unset($_SESSION['cart_success']);
        }
        if (isset($_SESSION['cart_error'])) {
            $error = $_SESSION['cart_error'];
            unset($_SESSION['cart_error']);
        }

        // Tính toán tổng tiền và áp dụng voucher nếu có
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $voucher = $_SESSION['voucher'] ?? null;
        $discountAmount = 0;
        $finalTotal = $subtotal;

        if ($voucher) {
            $voucherModel = $this->model('VoucherModel');
            $discountAmount = $voucherModel->calculateDiscount($voucher, $subtotal);
            $finalTotal = max(0, $subtotal - $discountAmount);
        }

        $this->view('cart', [
            'pageTitle' => 'Basic Shop - Giỏ Hàng',
            'page' => 'cart',
            'cart' => $_SESSION['cart'],
            'success' => $success,
            'error' => $error,
            'subtotal' => $subtotal,
            'voucher' => $voucher,
            'discountAmount' => $discountAmount,
            'finalTotal' => $finalTotal,
        ]);
    }

    public function add() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $productId = null;
        $qty = 1;
        $size = '';
        $color = '';

        // Có thể lấy từ POST hoặc GET
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $qty = (int)($_POST['product_qty'] ?? 1);
            $size = $_POST['product_size'] ?? '';
            $color = $_POST['product_color'] ?? '';
        } else {
            $productId = $_GET['id'] ?? null;
        }

        if ($productId) {
            $productModel = $this->model('ProductModel');
            $product = $productModel->getById((int)$productId);

            if ($product) {
                // Tạo key duy nhất cho mỗi sp + kích thước + màu (nếu có)
                $cartKey = $productId . '_' . $size . '_' . $color;

                if (isset($_SESSION['cart'][$cartKey])) {
                    $_SESSION['cart'][$cartKey]['quantity'] += $qty;
                } else {
                    $_SESSION['cart'][$cartKey] = [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'price' => $product->getSalePrice() ?? $product->getPrice(),
                        'image' => $product->getImage(),
                        'quantity' => $qty,
                        'size' => $size,
                        'color' => $color
                    ];
                }
            }
        }

        // Chuyển hướng lại trang trước đó hoặc trang cart
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=cart';
        header("Location: " . $referer);
        exit();
    }

    public function remove() {
        $cartKey = $_GET['key'] ?? null;
        if ($cartKey && isset($_SESSION['cart'][$cartKey])) {
            unset($_SESSION['cart'][$cartKey]);
            $_SESSION['cart_success'] = "Đã xóa sản phẩm khỏi giỏ hàng thành công!";
        }
        header("Location: index.php?page=cart");
        exit();
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty'])) {
            foreach ($_POST['qty'] as $key => $qty) {
                if (isset($_SESSION['cart'][$key])) {
                    $qty = max(1, (int)$qty); // ít nhất là 1
                    $_SESSION['cart'][$key]['quantity'] = $qty;
                }
            }
        }
        header("Location: index.php?page=cart");
        exit();
    }

    private function applyVoucher() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voucher_code'])) {
            $code = strtoupper(trim($_POST['voucher_code']));
            
            if (empty($code)) {
                $_SESSION['cart_error'] = "Vui lòng nhập mã voucher.";
            } else {
                $voucherModel = $this->model('VoucherModel');
                $validationResult = $voucherModel->validateVoucher($code);

                if (is_array($validationResult)) {
                    $_SESSION['voucher'] = $validationResult;
                    $_SESSION['cart_success'] = "Áp dụng voucher '{$code}' thành công!";
                } else {
                    // Nếu không phải mảng, đó là chuỗi lỗi
                    $_SESSION['cart_error'] = "Lỗi: " . $validationResult;
                }
            }
        }
        header("Location: index.php?page=cart");
        exit();
    }

    private function removeVoucher() {
        unset($_SESSION['voucher']);
        $_SESSION['cart_success'] = "Đã gỡ bỏ voucher.";
        header("Location: index.php?page=cart");
        exit();
    }

    // Hàm xử lý thanh toán, lưu vào DB và trừ kho
    public function checkout() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?page=login");
            exit();
        }

        if (empty($_SESSION['cart'])) {
            header("Location: " . BASE_URL . "index.php?page=cart");
            exit();
        }

        // Sử dụng OrderModel để xử lý logic nghiệp vụ
        $orderModel = $this->model('OrderModel');
        
        $userAuthModel = $this->model('UserAuth'); // Nạp UserAuth model thông qua Controller
        $currentUser = $userAuthModel->getUserByUsername($_SESSION['user']);

        if (!$currentUser || !isset($currentUser['id'])) {
            // Trường hợp hiếm gặp: user trong session không tồn tại trong DB
            $_SESSION['cart_error'] = "Lỗi xác thực người dùng. Vui lòng đăng nhập lại.";
            header("Location: " . BASE_URL . "index.php?page=login");
            exit();
        }

        try {
            // Lấy thông tin voucher từ session để truyền vào model
            $voucherInfo = null;
            if (isset($_SESSION['voucher'])) {
                $voucherModel = $this->model('VoucherModel');
                $subtotal = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                }
                $discountAmount = $voucherModel->calculateDiscount($_SESSION['voucher'], $subtotal);
                $voucherInfo = ['id' => $_SESSION['voucher']['id'], 'discount_amount' => $discountAmount];
            }

            // Gọi model để tạo đơn hàng, truyền vào ID của người dùng và thông tin voucher
            $orderId = $orderModel->createOrder($currentUser['id'], $_SESSION['cart'], $voucherInfo);
            
            // Xóa giỏ hàng sau khi thanh toán thành công
            unset($_SESSION['cart']);
            unset($_SESSION['voucher']); // Xóa cả voucher đã áp dụng
            
            // Lưu thông báo thành công để hiển thị ở trang profile
            $_SESSION['profile_success'] = "Đặt hàng thành công! Mã đơn hàng của bạn là #" . $orderId;

            // Chuyển hướng đến trang lịch sử đơn hàng (profile) để người dùng xem trạng thái
            header("Location: " . BASE_URL . "index.php?page=profile");
            exit();

        } catch (Exception $e) {
            // Nếu có lỗi, lưu thông báo và quay lại giỏ hàng
            // Lỗi này sẽ hiển thị chi tiết vấn đề cho người dùng
            $_SESSION['cart_error'] = "Lỗi Thanh Toán: " . $e->getMessage();
            header("Location: " . BASE_URL . "index.php?page=cart");
            exit();
        }
    }
}
