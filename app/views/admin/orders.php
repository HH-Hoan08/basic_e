<h2 class="mb-4">Quản lý Đơn hàng</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><i class="fas fa-<?= $message['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-1"></i> <?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th style="width: 320px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có đơn hàng nào trong hệ thống.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="fw-bold">#<?= htmlspecialchars($order['id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($order['username'] ?? 'Khách') ?></td>
                            <td class="text-danger fw-bold"><?= number_format($order['total_price'] ?? 0, 0, ',', '.') ?> đ</td>
                            <td><?= htmlspecialchars($order['ordered_at'] ?? '') ?></td>
                            <td>
                                <?php 
                                    $badges = [
                                        'pending' => 'bg-warning text-dark',
                                        'confirmed' => 'bg-info text-dark',
                                        'shipping' => 'bg-primary',
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ];
                                    $statusText = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'shipping' => 'Đang giao',
                                        'delivered' => 'Thành công',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    $currentStatus = $order['status'] ?? 'pending';
                                ?>
                                <span class="badge <?= $badges[$currentStatus] ?? 'bg-secondary' ?>">
                                    <?= $statusText[$currentStatus] ?? ucfirst($currentStatus) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($currentStatus === 'cancelled'): ?>
                                    <span class="text-muted fst-italic me-2">Không thể sửa</span>
                                    <a href="<?= BASE_URL ?>index.php?page=admin&action=view_order&order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary flex-shrink-0" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                <?php else: ?>
                                    <form action="<?= BASE_URL ?>index.php?page=admin&action=orders" method="POST" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id'] ?? '') ?>">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                            <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                            <option value="shipping" <?= $currentStatus === 'shipping' ? 'selected' : '' ?>>Đang giao</option>
                                            <option value="delivered" <?= $currentStatus === 'delivered' ? 'selected' : '' ?>>Thành công</option>
                                            <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-outline-success flex-shrink-0">Lưu</button>
                                        <a href="<?= BASE_URL ?>index.php?page=admin&action=view_order&order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary flex-shrink-0" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>