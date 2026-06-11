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
        $types  = '';

        if (!empty($filter['gender']) && $filter['gender'] !== 'all') {
            $where[]  = 'p.gender = ?';
            $params[] = $filter['gender'];
            $types   .= 's';
        }
        if (!empty($filter['brand_id'])) {
            $where[]  = 'p.brand_id = ?';
            $params[] = (int)$filter['brand_id'];
            $types   .= 'i';
        }
        if (!empty($filter['category_id'])) {
            $where[]  = 'p.category_id = ?';
            $params[] = (int)$filter['category_id'];
            $types   .= 'i';
        }
        if (!empty($filter['on_sale']))   $where[] = 'p.sale_price IS NOT NULL';
        if (!empty($filter['q'])) {
            $where[]  = '(p.name LIKE ? OR p.brand LIKE ?)';
            $kw       = '%' . $filter['q'] . '%';
            $params[] = $kw;
            $params[] = $kw;
            $types   .= 'ss';
        }

        $orderMap = [
            'featured'   => 'p.is_featured DESC, p.avg_rating DESC',
            'price_asc'  => 'COALESCE(p.sale_price, p.price) ASC',
            'price_desc' => 'COALESCE(p.sale_price, p.price) DESC',
            'newest'     => 'p.created_at DESC',
            'bestseller' => 'p.sold_count DESC',
            'rating'     => 'p.avg_rating DESC',
        ];
        $sort   = $orderMap[$filter['sort'] ?? 'featured'];
        $limit  = 9;
        $offset = (max(1, (int)($filter['page'] ?? 1)) - 1) * $limit;

        $sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name
                FROM   products p
                LEFT JOIN brands     b ON p.brand_id    = b.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE  " . implode(' AND ', $where) . "
                ORDER  BY $sort LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;
        $types   .= 'ii';

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            for ($i = 0; $i < count($params); $i++) {
                $stmt->bindValue($i + 1, $params[$i], $types[$i] === 'i' ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
        }
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

    // Đếm sản phẩm (phân trang)
    public function countProducts(array $filter = []): int {
        $where  = ['p.is_active = 1'];
        $params = [];
        $types  = '';

        if (!empty($filter['gender']) && $filter['gender'] !== 'all') {
            $where[]  = 'p.gender = ?';
            $params[] = $filter['gender'];
            $types   .= 's';
        }
        if (!empty($filter['brand_id'])) {
            $where[]  = 'p.brand_id = ?';
            $params[] = (int)$filter['brand_id'];
            $types   .= 'i';
        }
        if (!empty($filter['on_sale']))   $where[] = 'p.sale_price IS NOT NULL';
        if (!empty($filter['q'])) {
            $where[]  = '(p.name LIKE ? OR p.brand LIKE ?)';
            $kw       = '%' . $filter['q'] . '%';
            $params[] = $kw;
            $params[] = $kw;
            $types   .= 'ss';
        }

        $sql  = "SELECT COUNT(*) FROM products p WHERE " . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            for ($i = 0; $i < count($params); $i++) {
                $stmt->bindValue($i + 1, $params[$i], $types[$i] === 'i' ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}