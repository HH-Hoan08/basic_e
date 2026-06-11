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
            'page'        => $_GET['page']         ?? 1,
        ];

        $products   = $this->productModel->getProducts($filter);
        $total      = $this->productModel->countProducts($filter);
        $categories = $this->productModel->getParentCategories();
        $brands     = $this->productModel->getBrands();
        $totalPages = (int)ceil($total / 9);

        $this->view('shop', [
            'pageTitle'  => 'Basic Shop - Cửa Hàng',
            'filter'     => $filter,
            'products'   => $products,
            'total'      => $total,
            'categories' => $categories,
            'brands'     => $brands,
            'totalPages' => $totalPages
        ]);
    }

    public function single(): void {
        $slug = $_GET['slug'] ?? '';

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

        $this->view('shop-single', [
            'pageTitle'    => $product->getName() . ' - Basic Shop',
            'product'      => $product,
            'variants'     => $variants,
            'images'       => $images,
            'related'      => $related,
            'reviews'      => $reviews,
            // truyền productModel sang view để lấy variant của SP liên quan
            'productModel' => $this->productModel 
        ]);
    }
}