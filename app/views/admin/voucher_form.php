<?php
$isEdit = isset($voucher) && !empty($voucher['id']);
$pageTitle = $isEdit ? 'Chỉnh sửa Voucher' : 'Thêm Voucher mới';
?>
<h2 class="mb-4"><?= $pageTitle ?></h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= BASE_URL ?>index.php?page=admin&action=<?= $isEdit ? 'edit_voucher&id=' . $voucher['id'] : 'add_voucher' ?>" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">Mã Voucher <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase" id="code" name="code" value="<?= htmlspecialchars($voucher['code'] ?? '') ?>" required>
                    <small class="text-muted">Mã sẽ tự động được viết hoa.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="percent" <?= (isset($voucher['type']) && $voucher['type'] == 'percent') ? 'selected' : '' ?>>Giảm theo phần trăm (%)</option>
                        <option value="fixed" <?= (isset($voucher['type']) && $voucher['type'] == 'fixed') ? 'selected' : '' ?>>Giảm số tiền cố định (đ)</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="value" class="form-label">Giá trị <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="value" name="value" value="<?= htmlspecialchars($voucher['value'] ?? '') ?>" required min="0">
                    <small class="text-muted">Nhập số phần trăm (vd: 10) hoặc số tiền (vd: 50000).</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($voucher['quantity'] ?? 100) ?>" required min="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="expires_at" class="form-label">Ngày hết hạn</label>
                    <input type="date" class="form-control" id="expires_at" name="expires_at" value="<?= htmlspecialchars($voucher['expires_at'] ?? '') ?>">
                    <small class="text-muted">Để trống nếu không có ngày hết hạn.</small>
                </div>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?= (isset($voucher['is_active']) && $voucher['is_active'] == 1) || !$isEdit ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Hoạt động (Cho phép sử dụng)</label>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>index.php?page=admin&action=vouchers" class="btn btn-secondary">Hủy</a>
                <button type="submit" name="save_voucher" class="btn btn-success"><?= $isEdit ? 'Cập nhật Voucher' : 'Lưu Voucher' ?></button>
            </div>
        </form>
    </div>
</div>