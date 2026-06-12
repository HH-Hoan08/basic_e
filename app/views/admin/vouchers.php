<h2 class="mb-4">Quản lý Voucher</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><i class="fas fa-<?= $message['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-1"></i> <?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= BASE_URL ?>index.php?page=admin&action=add_voucher" class="btn btn-success"><i class="fas fa-plus me-2"></i>Thêm voucher mới</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã Voucher</th>
                        <th>Loại</th>
                        <th>Giá trị</th>
                        <th>Số lượng</th>
                        <th>Đã dùng</th>
                        <th>Ngày hết hạn</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vouchers)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có voucher nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($vouchers as $voucher): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($voucher['code']) ?></td>
                            <td><?= $voucher['type'] === 'percent' ? 'Phần trăm' : 'Số tiền cố định' ?></td>
                            <td class="text-danger fw-bold">
                                <?= $voucher['type'] === 'percent' ? htmlspecialchars($voucher['value']) . '%' : number_format($voucher['value'], 0, ',', '.') . 'đ' ?>
                            </td>
                            <td><?= htmlspecialchars($voucher['quantity']) ?></td>
                            <td><?= htmlspecialchars($voucher['used_count']) ?></td>
                            <td><?= !empty($voucher['expires_at']) ? date('d/m/Y', strtotime($voucher['expires_at'])) : 'Không hết hạn' ?></td>
                            <td>
                                <span class="badge bg-<?= $voucher['is_active'] ? 'success' : 'secondary' ?>"><?= $voucher['is_active'] ? 'Hoạt động' : 'Vô hiệu' ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>index.php?page=admin&action=edit_voucher&id=<?= $voucher['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Sửa</a>
                                <form action="<?= BASE_URL ?>index.php?page=admin&action=delete_voucher" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này?');">
                                    <input type="hidden" name="voucher_id" value="<?= $voucher['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Xóa</button>
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