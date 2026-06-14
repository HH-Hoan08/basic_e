<?php
include_once __DIR__ . '/../models/productModel.php';

class AboutController extends Controller {
    public function index() {
        // Khởi tạo model để lấy dữ liệu
        $productModel = new ProductModel();
        // Lấy danh sách các thương hiệu
        $brands = $productModel->getBrands();

        // Truyền danh sách thương hiệu sang view
        $this->view('about', [
            'pageTitle' => 'Về Chúng Tôi - Basic Shop',
            'brands' => $brands,
            'page' => 'about' // Thêm biến page để header có thể load đúng CSS
        ]);
    }
}