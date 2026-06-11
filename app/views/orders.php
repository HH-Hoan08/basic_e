<?php require_once __DIR__ . '/inc/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Đơn hàng của tôi</h1>
            
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php if (empty($userOrders)): ?>
                        <div class="alert alert-info text-center py-4">
                            Bạn chưa có đơn hàng nào. <br>
                            <a href="index.php?page=shop" class="btn btn-success mt-3">Tiếp tục mua sắm</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userOrders as $order): ?>
                                    <tr>
                                        <td><strong>#<?= htmlspecialchars($order['id'] ?? 'N/A') ?></strong></td>
                                        <td><?= htmlspecialchars($order['created_at'] ?? 'N/A') ?></td>
                                        <td><strong class="text-danger"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                                        <td>
                                            <?php $status = $order['status'] ?? 'Đang xử lý'; ?>
                                            <span class="badge bg-<?= ($status == 'Đã giao') ? 'success' : (($status == 'Đã hủy') ? 'danger' : 'warning') ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-success">Xem chi tiết</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>