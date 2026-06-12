<?php
include_once __DIR__ . '/database.php';

class VoucherModel {
    private PDO $db;

    public function __construct() {
        $this->db = (new database())->connect();
    }

    /**
     * Lấy và xác thực một voucher bằng mã code.
     * @param string $code Mã voucher
     * @return array|string Trả về mảng thông tin voucher nếu hợp lệ, ngược lại trả về chuỗi lỗi.
     */
    public function validateVoucher(string $code) {
        $sql = "SELECT * FROM vouchers WHERE code = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$code]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$voucher) {
            return "Mã voucher không tồn tại.";
        }

        if (!$voucher['is_active']) {
            return "Voucher này đã bị vô hiệu hóa.";
        }

        if ($voucher['quantity'] <= $voucher['used_count']) {
            return "Voucher đã hết lượt sử dụng.";
        }

        if (!empty($voucher['expires_at']) && strtotime($voucher['expires_at']) < time()) {
            return "Voucher đã hết hạn sử dụng.";
        }

        return $voucher; // Trả về mảng thông tin voucher nếu hợp lệ
    }

    /**
     * Tính toán số tiền được giảm giá.
     * @param array $voucher Thông tin voucher
     * @param float $subtotal Tổng tiền giỏ hàng
     * @return float Số tiền được giảm
     */
    public function calculateDiscount(array $voucher, float $subtotal): float {
        if ($voucher['type'] === 'percent') {
            return ($subtotal * $voucher['value']) / 100;
        } elseif ($voucher['type'] === 'fixed') {
            // Đảm bảo không giảm giá nhiều hơn tổng tiền
            return min($voucher['value'], $subtotal);
        }
        return 0;
    }
}