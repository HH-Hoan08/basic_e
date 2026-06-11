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
            }
        }

        $this->view('cart', [
            'pageTitle' => 'Basic Shop - Giỏ Hàng',
            'page' => 'cart',
            'cart' => $_SESSION['cart']
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
}
