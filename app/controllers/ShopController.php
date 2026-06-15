<?php
include_once __DIR__ . '/../models/productModel.php';

class ShopController extends Controller {
    private ProductModel $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->productModel = new ProductModel();
    }

    public function index(): void {
        $filter = [
            'gender'      => $_GET['gender']      ?? 'all',
            'brand_id'    => $_GET['brand_id']    ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'on_sale'     => $_GET['on_sale']      ?? null,
            'sort'        => $_GET['sort']         ?? 'featured',
            'q'           => $_GET['q']            ?? null,
            // Sửa: Dùng 'p' cho phân trang để tránh xung đột với tham số định tuyến 'page'
            'page'        => (int)($_GET['p'] ?? 1),
        ];

        $products          = $this->productModel->getProducts($filter);
        $total             = $this->productModel->countProducts($filter);
        $categories        = $this->productModel->getParentCategories(); // For header nav
        $sidebarCategories = $this->productModel->getAvailableCategories($filter); // For sidebar filter
        $brands            = $this->productModel->getBrands();
        $totalPages        = (int)ceil($total / 9);

        $this->view('shop', [
            'pageTitle'         => 'Basic Shop - Cửa Hàng',
            'filter'            => $filter,
            'products'          => $products,
            'total'             => $total,
            'categories'        => $categories, // For header
            'sidebarCategories' => $sidebarCategories, // For sidebar
            'brands'            => $brands,
            'totalPages'        => $totalPages
        ]);
    }

    public function single(): void {
        $slug = $_GET['slug'] ?? '';

        // nếu có POST request, đây là yêu cầu gửi review
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
            $this->submitReview();
            return;
        }

        // nếu không có slug chuyển về trang shop
        if (empty($slug)) {
            header('Location: ' . BASE_URL . 'index.php?page=shop');
            exit();
        }
        // lấy sản phẩm theo slug từ database
        $product = $this->productModel->getBySlug($slug);
        // nếu không tìm thấy sản phẩm thì chuyển về trang shop
        if (!$product) {
            header('Location: ' . BASE_URL . 'index.php?page=shop');
            exit();
        }

        $variants = $this->productModel->getVariants($product->getId());
        $images   = $this->productModel->getImages($product->getId());
        $related  = $this->productModel->getRelated($product->getCategoryId(), $slug, 4);
        $reviews  = $this->productModel->getReviews($product->getId());

        // Kiểm tra xem người dùng có thể đánh giá sản phẩm không
        $canReview = ['can' => false, 'reason' => 'Vui lòng đăng nhập để đánh giá.'];
        if (isset($_SESSION['user'])) {
            $userModel = $this->model('UserAuth');
            $currentUser = $userModel->getUserByUsername($_SESSION['user']);
            if ($currentUser) {
                $canReview = $this->productModel->canUserReviewProduct($currentUser['id'], $product->getId());
            }
        }

        // Lấy thông báo về việc gửi review từ session (nếu có)
        $reviewMessage = null;
        if (isset($_SESSION['review_message'])) {
            $reviewMessage = $_SESSION['review_message'];
            unset($_SESSION['review_message']);
        }

        $this->view('shop-single', [
            'pageTitle'    => $product->getName() . ' - Basic Shop',
            'product'      => $product,
            'variants'     => $variants,
            'images'       => $images,
            'related'      => $related,
            'reviews'      => $reviews,
            'canReview'    => $canReview,
            'reviewMessage' => $reviewMessage,
            // truyền productModel sang view để lấy variant của SP liên quan
            'productModel' => $this->productModel 
        ]);
    }

    private function submitReview() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit();
        }

        $slug = $_POST['slug'] ?? '';
        $productId = (int)($_POST['product_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        $redirectUrl = BASE_URL . 'index.php?page=shop-single&slug=' . $slug;

        if (empty($slug) || $productId === 0 || $rating < 1 || $rating > 5) {
            $_SESSION['review_message'] = ['type' => 'danger', 'text' => 'Dữ liệu không hợp lệ. Vui lòng thử lại.'];
            header('Location: ' . $redirectUrl);
            exit();
        }

        $userModel = $this->model('UserAuth');
        $currentUser = $userModel->getUserByUsername($_SESSION['user']);
        
        if (!$currentUser) {
            header('Location: ' . BASE_URL . 'index.php?page=login');
            exit();
        }

        $canReview = $this->productModel->canUserReviewProduct($currentUser['id'], $productId);

        if (!$canReview['can']) {
            $_SESSION['review_message'] = ['type' => 'warning', 'text' => $canReview['reason']];
        } else {
            $success = $this->productModel->addReview($currentUser['id'], $productId, $rating, $comment);
            if ($success) {
                $_SESSION['review_message'] = ['type' => 'success', 'text' => 'Cảm ơn bạn đã gửi đánh giá! Đánh giá của bạn đã được ghi nhận.'];
            } else {
                $_SESSION['review_message'] = ['type' => 'danger', 'text' => 'Đã có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.'];
            }
        }

        header('Location: ' . $redirectUrl);
        exit();
    }
}