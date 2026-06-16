<h2 class="mb-4">Quản lý Danh mục</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><i class="fas fa-<?= $message['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-1"></i> <?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= BASE_URL ?>index.php?page=admin&action=add_category" class="btn btn-success"><i class="fas fa-plus me-2"></i>Thêm danh mục mới</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Danh mục cha</th>
                        <th>Mô tả</th>
                        <th>Thứ tự</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có danh mục nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td class="fw-bold">#<?= htmlspecialchars($category['id']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($category['name']) ?></td>
                            <td><?= htmlspecialchars($category['parent_name'] ?? 'Không có') ?></td>
                            <td><?= htmlspecialchars($category['description'] ?? '') ?></td>
                            <td><?= htmlspecialchars($category['sort_order']) ?></td>
                            <td>
                                <span class="badge bg-<?= $category['is_active'] ? 'success' : 'secondary' ?>"><?= $category['is_active'] ? 'Hoạt động' : 'Đã ẩn' ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>index.php?page=admin&action=edit_category&id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Sửa</a>
                                <form action="<?= BASE_URL ?>index.php?page=admin&action=delete_category" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                    <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
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