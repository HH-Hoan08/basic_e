<?php
class ShopController extends Controller {
    public function index() {
        // Thêm logic lấy sản phẩm ở đây nếu cần
        $this->view('shop', ['pageTitle' => 'Basic Shop - Cửa Hàng']);
    }

    public function single($productId = null) {
        // Thêm logic lấy chi tiết sản phẩm với $productId ở đây
        $this->view('shop-single', ['pageTitle' => 'Basic Shop - Chi tiết sản phẩm', 'productId' => $productId]);
    }
}