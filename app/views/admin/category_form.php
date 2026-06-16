<?php
$isEdit = isset($category) && !empty($category['id']);
$pageTitle = $isEdit ? 'Chỉnh sửa Danh mục' : 'Thêm Danh mục mới';
?>
<h2 class="mb-4"><?= $pageTitle ?></h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= BASE_URL ?>index.php?page=admin&action=<?= $isEdit ? 'edit_category&id=' . $category['id'] : 'add_category' ?>" method="POST">
            
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="parent_id" class="form-label">Danh mục cha</label>
                <select class="form-select" id="parent_id" name="parent_id">
                    <option value="">-- Không có (Danh mục gốc) --</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <?php if (!$isEdit || $cat['id'] != $category['id']): // Không cho chọn chính nó làm cha ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($category['parent_id']) && $category['parent_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="sort_order" class="form-label">Thứ tự hiển thị</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= htmlspecialchars($category['sort_order'] ?? 0) ?>" required min="0">
                    <small class="text-muted">Số càng nhỏ, danh mục càng xuất hiện phía trên.</small>
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end pb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?= (isset($category['is_active']) && $category['is_active'] == 1) || !$isEdit ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Đang hoạt động (hiển thị)</label>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>index.php?page=admin&action=categories" class="btn btn-secondary">Hủy</a>
                <button type="submit" name="save_category" class="btn btn-success"><?= $isEdit ? 'Cập nhật danh mục' : 'Lưu danh mục' ?></button>
            </div>
        </form>
    </div>
</div>