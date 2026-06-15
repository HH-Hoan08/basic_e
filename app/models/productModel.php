<?php
include_once __DIR__ . '/database.php';
include_once __DIR__ . '/entities/product.php';
include_once __DIR__ . '/entities/category.php';
include_once __DIR__ . '/entities/brand.php';
include_once __DIR__ . '/entities/productVariant.php';
include_once __DIR__ . '/entities/productImage.php';
include_once __DIR__ . '/entities/review.php';

class ProductModel {
    private PDO $db;

    public function __construct() {
        $this->db = (new database())->connect();
    }

    
    // Trả về mảng Product objects theo bộ lọc
    /**
     * @return Product[]
     */
    public function getProducts(array $filter = []): array {
        $where  = ['p.is_active = 1'];
        $params = [];

        if (!empty($filter['gender']) && $filter['gender'] !== 'all') {
            $where[]  = 'p.gender = ?';
            $params[] = $filter['gender'];
        }
        if (!empty($filter['brand_id'])) {
            $where[]  = 'p.brand_id = ?';
            $params[] = (int)$filter['brand_id'];
        }
        if (!empty($filter['category_id'])) {
            $where[]  = 'p.category_id = ?';
            $params[] = (int)$filter['category_id'];
        }
        if (!empty($filter['on_sale']))   $where[] = 'p.sale_price IS NOT NULL';
        // Thêm bộ lọc cho sản phẩm nổi bật (dùng cho trang chủ)
        if (!empty($filter['is_featured'])) $where[] = 'p.is_featured = 1';
        if (!empty($filter['q'])) { // Mở rộng tìm kiếm để kết quả phù hợp hơn
            $where[]  = '(p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ? OR c.name LIKE ?)';
            $kw       = '%' . $filter['q'] . '%';
            $params[] = $kw;
            $params[] = $kw;
            $params[] = $kw; // for description
            $params[] = $kw; // for category name
        }

        $orderMap = [
            'featured'   => 'p.is_featured DESC, p.avg_rating DESC',
            'price_asc'  => 'COALESCE(p.sale_price, p.price) ASC',
            'price_desc' => 'COALESCE(p.sale_price, p.price) DESC',
            'newest'     => 'p.created_at DESC',
            'bestseller' => 'p.sold_count DESC',
            'rating'     => 'p.avg_rating DESC',
            'random'     => 'RAND()',
        ];
        $sort   = $orderMap[$filter['sort'] ?? 'featured'];
        // Cho phép tùy chỉnh giới hạn (dùng cho trang chủ)
        $limit  = (int)($filter['limit'] ?? 9);
        $offset = (max(1, (int)($filter['page'] ?? 1)) - 1) * $limit;

        // Thêm subquery để đếm số review hiệu quả
        $sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name,
                       (SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.id AND r.is_visible = 1) as review_count
                FROM   products p
                LEFT JOIN brands     b ON p.brand_id    = b.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE  " . implode(' AND ', $where) . "
                ORDER  BY $sort LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);

        // Bind các tham số cho mệnh đề WHERE. PDO thường có thể tự xác định kiểu dữ liệu cho các tham số này.
        $paramIndex = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }

        // Bind tường minh LIMIT và OFFSET dưới dạng số nguyên để tránh lỗi SQL.
        $stmt->bindValue($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, $offset, PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Chuyển mảng thô thành mảng Product objects
        return array_map(fn($row) => new Product($row), $rows);
    }

    // Lấy 1 sản phẩm theo slug — trả về Product object
    
    public function getBySlug(string $slug): ?Product {
        $stmt = $this->db->prepare(
            "SELECT p.*, b.name AS brand_name, c.name AS category_name, c.id AS cat_id
             FROM   products p
             LEFT JOIN brands     b ON p.brand_id    = b.id
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE  p.slug = ? AND p.is_active = 1 LIMIT 1"
        );
        $stmt->bindValue(1, $slug, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Product($row) : null;
    }

    public function getById(int $id): ?Product {
        $stmt = $this->db->prepare(
            "SELECT p.*, b.name AS brand_name, c.name AS category_name, c.id AS cat_id
             FROM   products p
             LEFT JOIN brands     b ON p.brand_id    = b.id
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE  p.id = ? AND p.is_active = 1 LIMIT 1"
        );
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Product($row) : null;
    }

    /**
     * Lấy biến thể — trả về ProductVariant[]
     * @return ProductVariant[]
     */
    public function getVariants(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM product_variants WHERE product_id = ? ORDER BY size ASC"
        );
        $stmt->bindValue(1, $productId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new ProductVariant($row), $rows);
    }

    /**
     * Lấy ảnh phụ — trả về ProductImage[]
     * @return ProductImage[]
     */
    public function getImages(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC"
        );
        $stmt->bindValue(1, $productId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new ProductImage($row), $rows);
    }

    /**
     * Lấy sản phẩm liên quan — trả về Product[]
     * @return Product[]
     */
    public function getRelated(int $categoryId, string $slug, int $limit = 4): array {
        $stmt = $this->db->prepare(
            "SELECT p.*, b.name AS brand_name
             FROM   products p
             LEFT JOIN brands b ON p.brand_id = b.id
             WHERE  p.category_id = ? AND p.slug != ? AND p.is_active = 1
             ORDER  BY RAND() LIMIT ?"
        );
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $slug, PDO::PARAM_STR);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Product($row), $rows);
    }

    /**
     * Lấy đánh giá — trả về Review[]
     * @return Review[]
     */
    public function getReviews(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.fullname
             FROM   reviews r
             JOIN   users   u ON r.user_id = u.id
             WHERE  r.product_id = ? AND r.is_visible = 1
             ORDER  BY r.created_at DESC"
        );
        $stmt->bindValue(1, $productId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Review($row), $rows);
    }

    /**
     * Lấy danh mục cha — trả về Category[]
     * @return Category[]
     */
    public function getParentCategories(): array {
        $rows = $this->db->query(
            "SELECT * FROM categories WHERE parent_id IS NULL AND is_active = 1 ORDER BY sort_order ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Category($row), $rows);
    }

    /**
     * Lấy thương hiệu — trả về Brand[]
     * @return Brand[]
     */
    public function getBrands(): array {
        $rows = $this->db->query(
            "SELECT * FROM brands WHERE is_active = 1 ORDER BY sort_order ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Brand($row), $rows);
    }

    /**
     * Lấy thương hiệu ngẫu nhiên — trả về Brand[]
     * @param int $limit
     * @return Brand[]
     */
    public function getRandomBrands(int $limit = 3): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM brands WHERE is_active = 1 ORDER BY RAND() LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Brand($row), $rows);
    }

    /**
     * Lấy danh sách các danh mục có sản phẩm, dựa trên các bộ lọc khác (như hãng, giới tính).
     * Dùng cho sidebar trang shop để danh sách danh mục luôn phù hợp.
     * @param array $filter
     * @return array
     */
    public function getAvailableCategories(array $filter = []): array {
        $where = ['p.is_active = 1'];
        $params = [];

        if (!empty($filter['gender']) && $filter['gender'] !== 'all') {
            $where[] = 'p.gender = ?';
            $params[] = $filter['gender'];
        }
        if (!empty($filter['brand_id'])) {
            $where[] = 'p.brand_id = ?';
            $params[] = (int)$filter['brand_id'];
        }
        // Không lọc theo category_id ở đây vì chúng ta đang lấy chính danh sách category

        $sql = "SELECT c.id, c.name, c.slug, COUNT(p.id) as product_count
                FROM categories c
                JOIN products p ON c.id = p.category_id
                WHERE " . implode(' AND ', $where) . "
                GROUP BY c.id, c.name, c.slug
                HAVING product_count > 0
                ORDER BY c.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm sản phẩm (phân trang)
    public function countProducts(array $filter = []): int {
        $where  = ['p.is_active = 1'];
        $params = [];

        if (!empty($filter['gender']) && $filter['gender'] !== 'all') { // Giữ nguyên
            $where[]  = 'p.gender = ?';
            $params[] = $filter['gender'];
        }
        if (!empty($filter['brand_id'])) { // Giữ nguyên
            $where[]  = 'p.brand_id = ?';
            $params[] = (int)$filter['brand_id'];
        }
        if (!empty($filter['category_id'])) { // Giữ nguyên
            $where[]  = 'p.category_id = ?';
            $params[] = (int)$filter['category_id'];
        }
        if (!empty($filter['on_sale']))   $where[] = 'p.sale_price IS NOT NULL'; // Giữ nguyên
        if (!empty($filter['q'])) { // Mở rộng tìm kiếm
            $where[]  = '(p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ? OR c.name LIKE ?)';
            $kw       = '%' . $filter['q'] . '%';
            $params[] = $kw;
            $params[] = $kw;
            $params[] = $kw; // for description
            $params[] = $kw; // for category name
        }

        // Sửa lỗi: Cần JOIN với bảng categories để tìm kiếm theo tên danh mục
        $sql  = "SELECT COUNT(p.id) 
                 FROM products p 
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE " . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // ==========================================
    // REVIEW-RELATED METHODS
    // ==========================================

    public function canUserReviewProduct(int $userId, int $productId): array {
        // 1. Check if user has already reviewed this product
        $sqlCheckReviewed = "SELECT COUNT(*) FROM reviews WHERE user_id = ? AND product_id = ?";
        $stmt = $this->db->prepare($sqlCheckReviewed);
        $stmt->execute([$userId, $productId]);
        if ($stmt->fetchColumn() > 0) {
            return ['can' => false, 'reason' => 'Bạn đã đánh giá sản phẩm này.'];
        }

        // 2. Kiểm tra xem user đã mua sản phẩm này chưa (không cần chờ giao hàng)
        $sqlCheckPurchased = "SELECT COUNT(*) 
                              FROM orders o 
                              JOIN order_items oi ON o.id = oi.order_id 
                              WHERE o.user_id = ? AND oi.product_id = ?";
        $stmt = $this->db->prepare($sqlCheckPurchased);
        $stmt->execute([$userId, $productId]);
        if ($stmt->fetchColumn() == 0) {
            return ['can' => false, 'reason' => 'Bạn cần mua sản phẩm này để có thể đánh giá.'];
        }

        return ['can' => true, 'reason' => ''];
    }

    public function addReview(int $userId, int $productId, int $rating, string $comment): bool {
        $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, is_visible, created_at) 
                VALUES (?, ?, ?, ?, 1, NOW())";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$userId, $productId, $rating, $comment]);

        if ($success) {
            $this->updateProductAvgRating($productId);
        }
        return $success;
    }

    public function updateProductAvgRating(int $productId): void {
        // Recalculates the average rating for a product based on visible reviews.
        $sql = "UPDATE products p SET 
                    p.avg_rating = (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE product_id = p.id AND is_visible = 1)
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
    }

    //lấy tất cả đánh giá của 1 user
    public function getReviewsByUser(int $userId): array {
        $sql = "SELECT r.*, p.name AS product_name, p.slug AS product_slug,
                       p.image AS product_image
                FROM   reviews r
                JOIN   products p ON r.product_id = p.id
                WHERE  r.user_id = ?
                ORDER  BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // trả về mảng thô (có thêm product_name, product_slug) thay vì Review entity
        // vì cần JOIN data từ products
        return $rows;
    }
}