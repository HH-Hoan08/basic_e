<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Khách hàng</h1>
</div>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($message['text']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-users me-1"></i>
        Danh sách khách hàng
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Họ và Tên</th>
                        <th>Email</th>
                        <th>Tổng chi tiêu</th>
                        <th>Hạng</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Không có khách hàng nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($customer['id']) ?></td>
                                <td><?= htmlspecialchars($customer['fullname']) ?></td>
                                <td><?= htmlspecialchars($customer['email']) ?></td>
                                <td><strong><?= number_format($customer['total_spent'], 0, ',', '.') ?>đ</strong></td>
                                <td>
                                    <?php 
                                    $tier = $customer['tier'];
                                    $tierClass = ['Member' => 'secondary', 'Silver' => 'info', 'Gold' => 'warning', 'Diamond' => 'primary'][$tier] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $tierClass ?>"><?= htmlspecialchars($tier) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $customer['is_locked'] ? 'danger' : 'success' ?>"><?= $customer['is_locked'] ? 'Đã khóa' : 'Hoạt động' ?></span>
                                </td>
                                <td class="text-center">
                                    <form action="<?= BASE_URL ?>index.php?page=admin&action=customers" method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= $customer['id'] ?>">
                                        <input type="hidden" name="is_locked" value="<?= $customer['is_locked'] ? 0 : 1 ?>">
                                        <button type="submit" name="toggle_lock" class="btn btn-sm btn-outline-<?= $customer['is_locked'] ? 'success' : 'warning' ?>" title="<?= $customer['is_locked'] ? 'Mở khóa' : 'Khóa' ?>"><i class="fas fa-<?= $customer['is_locked'] ? 'unlock' : 'lock' ?>"></i></button>
                                    </form>
                                    <form action="<?= BASE_URL ?>index.php?page=admin&action=send_password_reset" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn gửi email đặt lại mật khẩu cho người dùng này?');">
                                        <input type="hidden" name="user_id" value="<?= $customer['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Gửi email đặt lại mật khẩu"><i class="fas fa-key"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>