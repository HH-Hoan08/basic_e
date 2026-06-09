<?php
include_once __DIR__ . '/database.php';

class ProductModel {
    private PDO $db;

    public function __construct() {
        $dbObj = new database();
        $this->db = $dbObj->connect();
    }

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
        if (!empty($filter['on_sale'])) {
            $where[] = 'p.sale_price IS NOT NULL';
        }
        if (!empty($filter['q'])) {
            $where[]  = '(p.name LIKE ? OR p.brand LIKE ?)';
            $kw       = '%' . $filter['q'] . '%';
            $params[] = $kw;
            $params[] = $kw;
        }

        $orderMap = [
            'featured'   => 'p.is_featured DESC, p.avg_rating DESC',
            'price_asc'  => 'COALESCE(p.sale_price, p.price) ASC',
            'price_desc' => 'COALESCE(p.sale_price, p.price) DESC',
            'newest'     => 'p.created_at DESC',
            'bestseller' => 'p.sold_count DESC',
            'rating'     => 'p.avg_rating DESC',
        ];
        $sort   = $orderMap[$filter['sort'] ?? 'featured'] ?? $orderMap['featured'];
        $limit  = 9;
        $offset = (max(1, (int)($filter['page'] ?? 1)) - 1) * $limit;

        $sql = "SELECT p.*, b.name AS brand_name, b.logo AS brand_logo,
                       c.name AS category_name
                FROM   products p
                LEFT JOIN brands     b ON p.brand_id    = b.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE  " . implode(' AND ', $where) . "
                ORDER  BY $sort
                LIMIT  ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        foreach ($params as $index => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($index + 1, $value, $type);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countProducts(array $filter = []): int {
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
        if (!empty($filter['q'])) {
            $where[]  = '(p.name LIKE ? OR p.brand LIKE ?)';
            $kw       = '%' . $filter['q'] . '%';
            $params[] = $kw;
            $params[] = $kw;
        }

        $sql  = "SELECT COUNT(*) FROM products p WHERE " . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        foreach ($params as $index => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($index + 1, $value, $type);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getParentCategories(): array {
        $stmt = $this->db->query(
            "SELECT * FROM categories
             WHERE parent_id IS NULL AND is_active = 1
             ORDER BY sort_order ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBrands(): array {
        $stmt = $this->db->query(
            "SELECT * FROM brands WHERE is_active = 1 ORDER BY sort_order ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBySlug(string $slug): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.*, b.name AS brand_name, c.name AS category_name
             FROM   products p
             LEFT JOIN brands     b ON p.brand_id    = b.id
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE  p.slug = ? AND p.is_active = 1 LIMIT 1"
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}