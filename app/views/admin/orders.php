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
                        <th>Cập nhật Trạng thái</th>
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
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>index.php?page=admin&action=view_order&order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Chi tiết</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>