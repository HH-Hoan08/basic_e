<?php
include_once __DIR__ . '/../models/productModel.php';

class ShopController {
    private ProductModel $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->productModel = new ProductModel();
    }

    public function showShop(): void {
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

        include __DIR__ . '/../view/shop.php';
    }
}