<?php
include_once __DIR__ . '/database.php';

class OrderModel {
    private PDO $db;

    public function __construct() {
        $this->db = (new database())->connect();
    }

    /**
     * Tạo đơn hàng mới, cập nhật kho sản phẩm trong một giao dịch (transaction).
     * Sẽ trả về ID đơn hàng nếu thành công, hoặc ném ra Exception nếu thất bại.
     * @param int $userId
     * @param array $cart
     * @param array|null $voucherInfo Mảng chứa 'id' và 'discount_amount' của voucher
     * @return int
     * @throws Exception
     */
    public function createOrder(int $userId, array $cart, ?array $voucherInfo = null): int {
        // Tính tổng tiền (subtotal)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        if ($subtotal <= 0) {
            throw new Exception("Giỏ hàng không hợp lệ hoặc tổng tiền bằng 0.");
        }

        $voucherId = $voucherInfo['id'] ?? null;
        $discountAmount = $voucherInfo['discount_amount'] ?? 0;
        $finalPrice = max(0, $subtotal - $discountAmount);

        $this->db->beginTransaction();

        try {
            // 1. Chèn đơn hàng mới vào bảng `orders`
            // *** KIỂM TRA TỒN KHO TRƯỚC KHI TẠO ĐƠN HÀNG ***
            $sqlCheckStock = "SELECT name, stock FROM products WHERE id = ? FOR UPDATE"; // Khóa dòng để kiểm tra, tránh race condition
            $stmtCheckStock = $this->db->prepare($sqlCheckStock);
            foreach ($cart as $item) {
                $stmtCheckStock->execute([$item['id']]);
                $product = $stmtCheckStock->fetch(PDO::FETCH_ASSOC);

                // Nếu sản phẩm không tồn tại hoặc không đủ hàng
                if (!$product || $product['stock'] < $item['quantity']) {
                    $stockAvailable = $product['stock'] ?? 0;
                    throw new Exception("Sản phẩm '{$item['name']}' không đủ số lượng trong kho (chỉ còn {$stockAvailable}). Vui lòng cập nhật giỏ hàng.");
                }
            }

            $sqlOrder = "INSERT INTO orders (user_id, voucher_id, total_price, discount_amount, status, ordered_at) VALUES (?, ?, ?, ?, 'pending', NOW())";
            $stmtOrder = $this->db->prepare($sqlOrder);
            $stmtOrder->execute([$userId, $voucherId, $finalPrice, $discountAmount]);
            $orderId = (int)$this->db->lastInsertId();

            // 2. Chèn chi tiết đơn hàng vào `order_items`
            // Dựa trên thông tin bạn cung cấp, bảng `order_items` có các cột: order_id, product_id, quantity, unit_price
            $sqlItems = "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
            $stmtItems = $this->db->prepare($sqlItems);

            // 3. Cập nhật tồn kho (stock) và đã bán (sold_count) cho từng sản phẩm
            $sqlUpdateProduct = "UPDATE products SET stock = GREATEST(0, COALESCE(stock, 0) - ?), sold_count = COALESCE(sold_count, 0) + ? WHERE id = ?";
            $stmtUpdateProduct = $this->db->prepare($sqlUpdateProduct);

            foreach ($cart as $item) {
                // Chèn vào order_items
                $stmtItems->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
                // Cập nhật products
                $stmtUpdateProduct->execute([$item['quantity'], $item['quantity'], $item['id']]);
            }

            // 4. Cập nhật số lượng đã dùng của voucher (nếu có)
            if ($voucherId) {
                $sqlVoucher = "UPDATE vouchers SET used_count = used_count + 1 WHERE id = ?";
                $stmtVoucher = $this->db->prepare($sqlVoucher);
                $stmtVoucher->execute([$voucherId]);
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Ném lại lỗi để Controller có thể bắt và hiển thị cho người dùng
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Hủy một đơn hàng (chỉ khi ở trạng thái 'pending') và hoàn lại kho.
     * @param int $orderId ID của đơn hàng cần hủy
     * @param int $userId ID của người dùng để xác thực
     * @return bool
     * @throws Exception
     */
    public function cancelOrder(int $orderId, int $userId): bool {
        $this->db->beginTransaction();

        try {
            // 1. Lấy thông tin đơn hàng và kiểm tra quyền sở hữu + trạng thái
            $sqlGetOrder = "SELECT status FROM orders WHERE id = ? AND user_id = ?";
            $stmtGetOrder = $this->db->prepare($sqlGetOrder);
            $stmtGetOrder->execute([$orderId, $userId]);
            $order = $stmtGetOrder->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                throw new Exception("Đơn hàng không tồn tại hoặc bạn không có quyền hủy.");
            }

            if ($order['status'] !== 'pending') {
                throw new Exception("Chỉ có thể hủy đơn hàng ở trạng thái 'Chờ xác nhận'.");
            }

            // 2. Lấy danh sách sản phẩm trong đơn hàng từ `order_items`
            $sqlGetItems = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
            $stmtGetItems = $this->db->prepare($sqlGetItems);
            $stmtGetItems->execute([$orderId]);
            $items = $stmtGetItems->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($items)) {
                 // 3. Hoàn lại số lượng tồn kho (stock) và giảm số lượng đã bán (sold_count)
                $sqlUpdateProduct = "UPDATE products SET stock = COALESCE(stock, 0) + ?, sold_count = GREATEST(0, COALESCE(sold_count, 0) - ?) WHERE id = ?";
                $stmtUpdateProduct = $this->db->prepare($sqlUpdateProduct);

                foreach ($items as $item) {
                    $stmtUpdateProduct->execute([$item['quantity'], $item['quantity'], $item['product_id']]);
                }
            }

            // 4. Cập nhật trạng thái đơn hàng thành 'cancelled'
            $sqlCancelOrder = "UPDATE orders SET status = 'cancelled' WHERE id = ?"; // Đã bỏ cột 'updated_at'
            $stmtCancelOrder = $this->db->prepare($sqlCancelOrder);
            $stmtCancelOrder->execute([$orderId]);

            $this->db->commit();
            return $stmtCancelOrder->rowCount() > 0;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Ném lại lỗi để Controller xử lý
            throw $e;
        }
    }

    /**
     * Lấy danh sách các sản phẩm trong một đơn hàng.
     * @param int $orderId ID của đơn hàng.
     * @return array Mảng chứa các item trong đơn hàng.
     */
    public function getOrderItems(int $orderId): array {
        $sql = "SELECT oi.*, p.name as product_name, p.image as product_image
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết của một đơn hàng, bao gồm cả thông tin người dùng và các sản phẩm trong đơn.
     * @param int $orderId ID của đơn hàng.
     * @param int|null $userId (Tùy chọn) ID của người dùng để xác thực quyền sở hữu.
     * @return array|null Thông tin chi tiết đơn hàng hoặc null nếu không tìm thấy/không có quyền.
     * @throws Exception
     */
    public function getOrderDetails(int $orderId, ?int $userId = null): ?array {
        try {
            $sql = "SELECT o.*, u.username, u.fullname, u.email
                    FROM orders o
                    JOIN users u ON o.user_id = u.id
                    WHERE o.id = ?";
            $params = [$orderId];

            if ($userId !== null) {
                $sql .= " AND o.user_id = ?";
                $params[] = $userId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                $order['items'] = $this->getOrderItems($orderId);
            }

            return $order;

        } catch (Exception $e) {
            // Ném lại lỗi để Controller xử lý
            throw $e;
        }
    }
}