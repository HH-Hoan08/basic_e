<?php require_once __DIR__ . '/inc/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Chi tiết Đơn hàng #<?= htmlspecialchars($order['id'] ?? 'N/A') ?></h1>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error ?></div>
            <?php endif; ?>

            <?php if (empty($order)): ?>
                <div class="alert alert-warning text-center py-4">
                    Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập.
                </div>
            <?php else: ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Thông tin Đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mã đơn hàng:</strong> #<?= htmlspecialchars($order['id']) ?></p>
                                <p><strong>Ngày đặt:</strong> <?= htmlspecialchars($order['ordered_at']) ?></p>
                                <p><strong>Tổng tiền:</strong> <strong class="text-danger"><?= number_format($order['total_price'], 0, ',', '.') ?>đ</strong></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Trạng thái:</strong> 
                                    <?php 
                                        $statusText = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'shipping' => 'Đang giao',
                                            'delivered' => 'Thành công',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        $statusBadges = [
                                            'pending' => 'bg-warning text-dark',
                                            'confirmed' => 'bg-info text-dark',
                                            'shipping' => 'bg-primary',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger'
                                        ];
                                        $currentStatus = $order['status'] ?? 'pending';
                                    ?>
                                    <span class="badge <?= $statusBadges[$currentStatus] ?? 'bg-secondary' ?>">
                                        <?= htmlspecialchars($statusText[$currentStatus] ?? ucfirst($currentStatus)) ?>
                                    </span>
                                </p>
                                <p><strong>Người đặt:</strong> <?= htmlspecialchars($order['fullname'] ?? $order['username']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Các sản phẩm trong đơn hàng</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($order['items'])): ?>
                                        <tr><td colspan="4" class="text-center py-3 text-muted">Không có sản phẩm nào trong đơn hàng này.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($item['product_image'] ?? 'shop_01.jpg') ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                                        <span><?= htmlspecialchars($item['product_name']) ?></span>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                                <td><?= number_format($item['unit_price'], 0, ',', '.') ?>đ</td>
                                                <td><strong class="text-danger"><?= number_format($item['quantity'] * $item['unit_price'], 0, ',', '.') ?>đ</strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="index.php?page=profile#orders" class="btn btn-secondary">Quay lại lịch sử đơn hàng</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>