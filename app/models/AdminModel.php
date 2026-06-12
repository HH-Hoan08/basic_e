<?php
include_once __DIR__ . '/database.php';

class AdminModel {
    private PDO $db;

    public function __construct() {
        $this->db = (new database())->connect();
    }

    // 1. Lấy thống kê doanh thu theo thời gian
    public function getRevenueStats(): array {
        // Lấy doanh thu trong Tuần, Tháng, Năm hiện tại
        // Giả sử bảng 'orders' có cột 'total_price' và 'created_at', trạng thái 'completed'
        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN YEARWEEK(ordered_at, 1) = YEARWEEK(CURDATE(), 1) THEN total_price ELSE 0 END), 0) as revenue_week,
                COALESCE(SUM(CASE WHEN MONTH(ordered_at) = MONTH(CURDATE()) AND YEAR(ordered_at) = YEAR(CURDATE()) THEN total_price ELSE 0 END), 0) as revenue_month,
                COALESCE(SUM(CASE WHEN YEAR(ordered_at) = YEAR(CURDATE()) THEN total_price ELSE 0 END), 0) as revenue_year
            FROM orders 
            WHERE status = 'delivered'
        ";
        
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['revenue_week' => 0, 'revenue_month' => 0, 'revenue_year' => 0];
        } catch (Exception $e) {
            return ['revenue_week' => 0, 'revenue_month' => 0, 'revenue_year' => 0];
        }
    }

    // 2. Lấy sản phẩm bán chạy hoặc bán ế nhất
    public function getProductPerformance(string $orderBy = 'DESC', int $limit = 5): array {
        // DESC = Bán chạy nhất, ASC = Ế nhất
        $sql = "SELECT id, name, image, price, sold_count, stock 
                FROM products 
                ORDER BY sold_count {$orderBy} 
                LIMIT " . (int)$limit;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2.5 Lấy các số liệu tổng quan cho Dashboard
    public function getDashboardSummary(): array {
        $sql = "
            SELECT 
                (SELECT COUNT(*) FROM orders) as total_orders,
                (SELECT COUNT(*) FROM products) as total_products,
                (SELECT SUM(stock) FROM products) as total_stock,
                (SELECT COUNT(*) FROM orders WHERE status = 'pending') as pending_orders
        ";
        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Đảm bảo không có giá trị null trả về
            return [
                'total_orders'   => (int)($result['total_orders'] ?? 0),
                'total_products' => (int)($result['total_products'] ?? 0),
                'total_stock'    => (int)($result['total_stock'] ?? 0),
                'pending_orders' => (int)($result['pending_orders'] ?? 0),
            ];
        } catch (Exception $e) {
            // Trả về mảng 0 nếu có lỗi
            return ['total_orders' => 0, 'total_products' => 0, 'total_stock' => 0, 'pending_orders' => 0];
        }
    }

    // 3. Cập nhật trạng thái đơn hàng
    public function updateOrderStatus(int $orderId, string $status): bool {
        $this->db->beginTransaction();

        try {
            // 1. Lấy trạng thái hiện tại của đơn hàng, khóa dòng để tránh race condition
            $sqlGetCurrentStatus = "SELECT status FROM orders WHERE id = ? FOR UPDATE";
            $stmtGetCurrentStatus = $this->db->prepare($sqlGetCurrentStatus);
            $stmtGetCurrentStatus->execute([$orderId]);
            $currentOrder = $stmtGetCurrentStatus->fetch(PDO::FETCH_ASSOC);

            if (!$currentOrder) {
                throw new Exception("Đơn hàng không tồn tại.");
            }
            $currentStatus = $currentOrder['status'];

            // Nếu trạng thái không thay đổi thì không làm gì cả
            if ($currentStatus === $status) {
                $this->db->rollBack(); // Không có gì để commit
                return true;
            }

            // 2. Lấy danh sách sản phẩm trong đơn hàng
            $sqlGetItems = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
            $stmtGetItems = $this->db->prepare($sqlGetItems);
            $stmtGetItems->execute([$orderId]);
            $items = $stmtGetItems->fetchAll(PDO::FETCH_ASSOC);

            // 3. Xử lý logic hoàn/trừ kho nếu có sản phẩm trong đơn
            if (!empty($items)) {
                // Trường hợp 1: Hủy đơn hàng (chuyển sang 'cancelled')
                if ($status === 'cancelled' && $currentStatus !== 'cancelled') {
                    $sqlUpdateProduct = "UPDATE products SET stock = COALESCE(stock, 0) + ?, sold_count = GREATEST(0, COALESCE(sold_count, 0) - ?) WHERE id = ?";
                    $stmtUpdateProduct = $this->db->prepare($sqlUpdateProduct);
                    foreach ($items as $item) {
                        $stmtUpdateProduct->execute([$item['quantity'], $item['quantity'], $item['product_id']]);
                    }
                }
                // Trường hợp 2: Khôi phục đơn hàng đã hủy (chuyển từ 'cancelled' sang trạng thái khác)
                else if ($currentStatus === 'cancelled' && $status !== 'cancelled') {
                    // Kiểm tra tồn kho trước khi khôi phục
                    $sqlCheckStock = "SELECT name, stock FROM products WHERE id = ?";
                    $stmtCheckStock = $this->db->prepare($sqlCheckStock);
                    foreach ($items as $item) {
                        $stmtCheckStock->execute([$item['product_id']]);
                        $product = $stmtCheckStock->fetch(PDO::FETCH_ASSOC);
                        if (!$product || $product['stock'] < $item['quantity']) {
                            throw new Exception("Không đủ tồn kho cho sản phẩm '{$product['name']}' để khôi phục đơn hàng.");
                        }
                    }

                    // Nếu đủ tồn kho, tiến hành trừ kho
                    $sqlUpdateProduct = "UPDATE products SET stock = GREATEST(0, COALESCE(stock, 0) - ?), sold_count = COALESCE(sold_count, 0) + ? WHERE id = ?";
                    $stmtUpdateProduct = $this->db->prepare($sqlUpdateProduct);
                    foreach ($items as $item) {
                        $stmtUpdateProduct->execute([$item['quantity'], $item['quantity'], $item['product_id']]);
                    }
                }
            }

            // 4. Cập nhật trạng thái đơn hàng
            $sql = "UPDATE orders SET status = ? WHERE id = ?"; // Đã bỏ cột 'updated_at'
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status, $orderId]);

            $this->db->commit();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e; // Ném lại lỗi để Controller xử lý
        }
    }

    // 4. Lấy tất cả đơn hàng (cho trang Quản lý Đơn hàng)
    public function getAllOrders(): array {
        try {
            // Join với bảng users để lấy username hiển thị trong trang admin
            $sql = "SELECT o.*, u.username 
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    ORDER BY o.ordered_at DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return []; // Trả về mảng rỗng nếu bảng chưa tạo
        }
    }

    // 5. Quản lý khách hàng & Xếp hạng (Silver, Gold, Diamond)
    public function getCustomersWithTiers(): array {
        // Tính tổng tiền mua hàng để xếp hạng (Gợi ý)
        $sql = "
            SELECT u.id, u.username, u.fullname, u.email, u.is_locked,
                   COALESCE(SUM(o.total_price), 0) as total_spent,
                   CASE 
                       WHEN SUM(o.total_price) >= 50000000 THEN 'Diamond'
                       WHEN SUM(o.total_price) >= 20000000 THEN 'Gold'
                       WHEN SUM(o.total_price) >= 5000000 THEN 'Silver'
                       ELSE 'Member'
                   END as tier
            FROM users u 
            LEFT JOIN orders o ON u.id = o.user_id AND o.status = 'delivered'
            WHERE u.role != 'admin' OR u.role IS NULL
            GROUP BY u.id
            ORDER BY total_spent DESC
        ";
        try {
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // 6. Khóa / Mở khóa tài khoản khách hàng
    public function toggleUserLock(int $userId, int $isLocked): bool {
        $sql = "UPDATE users SET is_locked = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$isLocked, $userId]);
    }

    // ==========================================
    // 7. QUẢN LÝ SẢN PHẨM (CRUD)
    // ==========================================

    public function getAllProductsForAdmin(): array {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                ORDER BY p.created_at DESC";
        try {
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getProductById(int $id): ?array {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    }

    public function getCategoriesAndBrands(): array {
        try {
            $sql_cats = "SELECT id, name FROM categories ORDER BY name ASC";
            $categories = $this->db->query($sql_cats)->fetchAll(PDO::FETCH_ASSOC);

            $sql_brands = "SELECT id, name FROM brands ORDER BY name ASC";
            $brands = $this->db->query($sql_brands)->fetchAll(PDO::FETCH_ASSOC);

            return ['categories' => $categories, 'brands' => $brands];
        } catch (Exception $e) {
            return ['categories' => [], 'brands' => []];
        }
    }

    private function generateSlug(string $name, ?int $excludeId = null): string {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM products WHERE slug = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug, $excludeId ?? 0]);
            if (!$stmt->fetch()) {
                break;
            }
            $slug = $originalSlug . '-' . $counter++;
        }
        return $slug;
    }

    public function createProduct(array $data): bool {
        $data['slug'] = $this->generateSlug($data['name']);

        $sql = "INSERT INTO products (name, slug, description, price, sale_price, stock, category_id, brand_id, gender, image, is_active, is_featured, created_by)
                VALUES (:name, :slug, :description, :price, :sale_price, :stock, :category_id, :brand_id, :gender, :image, :is_active, :is_featured, :created_by)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateProduct(int $id, array $data): bool {
        // Nếu tên thay đổi, tạo slug mới
        $currentProduct = $this->getProductById($id);
        if ($currentProduct['name'] !== $data['name']) {
            $data['slug'] = $this->generateSlug($data['name'], $id);
        }

        $sql = "UPDATE products SET 
                    name = :name, 
                    description = :description, 
                    price = :price, 
                    sale_price = :sale_price, 
                    stock = :stock, 
                    category_id = :category_id, 
                    brand_id = :brand_id, 
                    gender = :gender, 
                    is_active = :is_active, 
                    is_featured = :is_featured";
        
        if (isset($data['slug'])) $sql .= ", slug = :slug";
        if (!empty($data['image'])) $sql .= ", image = :image";
        
        $sql .= " WHERE id = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteProduct(int $id): bool {
        $product = $this->getProductById($id);
        
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$id]);

        if ($success && $product && !empty($product['image'])) {
            // Sử dụng ROOT_PATH đã được định nghĩa ở file index.php chính
            $imagePath = ROOT_PATH . '/assets/img/' . $product['image'];
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }
        return $success;
    }

    // ==========================================
    // 8. QUẢN LÝ ĐÁNH GIÁ
    // ==========================================

    public function getAllReviews(): array {
        $sql = "SELECT r.*, u.username, p.name as product_name
                FROM reviews r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN products p ON r.product_id = p.id
                ORDER BY r.created_at DESC";
        try {
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function toggleReviewVisibility(int $reviewId, int $isVisible): bool {
        $sql = "UPDATE reviews SET is_visible = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$isVisible, $reviewId]);
        
        if ($success) {
            $this->recalculateProductRatingFromReview($reviewId);
        }
        return $success;
    }

    public function deleteReview(int $reviewId): bool {
        // Lấy product_id trước khi xóa
        $productId = $this->getProductIdFromReview($reviewId);

        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$reviewId]);

        if ($success && $productId) {
            $this->recalculateProductRating($productId);
        }
        return $success;
    }

    private function getProductIdFromReview(int $reviewId): ?int {
        $sqlGetProductId = "SELECT product_id FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sqlGetProductId);
        $stmt->execute([$reviewId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['product_id'] : null;
    }

    private function recalculateProductRating(int $productId): void {
        $sql = "UPDATE products p SET 
                    p.avg_rating = (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE product_id = p.id AND is_visible = 1)
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
    }

    private function recalculateProductRatingFromReview(int $reviewId): void {
        $productId = $this->getProductIdFromReview($reviewId);
        if ($productId) {
            $this->recalculateProductRating($productId);
        }
    }

    // ==========================================
    // 9. QUẢN LÝ VOUCHER
    // ==========================================

    public function getAllVouchers(): array {
        $sql = "SELECT v.*, a.fullname as creator_name 
                FROM vouchers v 
                LEFT JOIN admins a ON v.created_by = a.id 
                ORDER BY v.id DESC";
        try {
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getVoucherById(int $id): ?array {
        $sql = "SELECT * FROM vouchers WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
        return $voucher ?: null;
    }

    public function isVoucherCodeExists(string $code, ?int $excludeId = null): bool {
        $sql = "SELECT id FROM vouchers WHERE code = ?";
        $params = [$code];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }

    public function createVoucher(array $data): bool {
        $sql = "INSERT INTO vouchers (code, type, value, quantity, expires_at, is_active, created_by) 
                VALUES (:code, :type, :value, :quantity, :expires_at, :is_active, :created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateVoucher(int $id, array $data): bool {
        $sql = "UPDATE vouchers SET 
                    code = :code, 
                    type = :type, 
                    value = :value, 
                    quantity = :quantity, 
                    expires_at = :expires_at, 
                    is_active = :is_active 
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteVoucher(int $id): bool {
        $sql = "DELETE FROM vouchers WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}