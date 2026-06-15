<?php
include_once __DIR__ . '/../models/productModel.php';

class HomeController extends Controller {
    public function index() {
        $productModel = new ProductModel();

        // Lấy 3 sản phẩm được đánh dấu là "nổi bật" từ CSDL
        $featuredProducts = $productModel->getProducts([
            'is_featured' => 1,
            'limit' => 3,
            'sort' => 'bestseller' // Sắp xếp theo bán chạy nhất
        ]);

        // Lấy 3 sản phẩm mới nhất cho banner
        $bannerProducts = $productModel->getProducts([
            'limit' => 3,
            'sort' => 'newest'
        ]);

        // Lấy 3 thương hiệu ngẫu nhiên để hiển thị trên trang chủ
        $brands = $productModel->getRandomBrands(3);

        // Truyền dữ liệu sang view
        $this->view('home', [
            'pageTitle' => 'Basic Shop',
            'featuredProducts' => $featuredProducts,
            'brands' => $brands,
            'bannerProducts' => $bannerProducts,
            'page' => 'home'
        ]);
    }
}