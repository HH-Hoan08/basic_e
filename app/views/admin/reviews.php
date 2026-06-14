<h2 class="mb-4">Quản lý Đánh giá</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><i class="fas fa-<?= $message['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-1"></i> <?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Người dùng</th>
                        <th>Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reviews)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có đánh giá nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td class="fw-bold">#<?= htmlspecialchars($review['id']) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= htmlspecialchars($review['product_slug'] ?? '') ?>" target="_blank">
                                    <?= htmlspecialchars($review['product_name']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($review['username']) ?></td>
                            <td>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-secondary' ?>" style="font-size:12px;"></i>
                                <?php endfor; ?>
                                (<?= $review['rating'] ?>)
                            </td>
                            <td style="max-width: 300px;">
                                <p class="mb-0 text-muted small text-truncate"><?= htmlspecialchars($review['comment'] ?? 'Không có bình luận') ?></p>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $review['is_visible'] ? 'success' : 'secondary' ?>">
                                    <?= $review['is_visible'] ? 'Đang hiển thị' : 'Đã ẩn' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <form action="<?= BASE_URL ?>index.php?page=admin&action=reviews" method="POST" class="d-inline">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <input type="hidden" name="is_visible" value="<?= $review['is_visible'] ? 0 : 1 ?>">
                                        <button type="submit" name="toggle_visibility" class="btn btn-sm btn-outline-secondary" title="<?= $review['is_visible'] ? 'Ẩn' : 'Hiện' ?>">
                                            <i class="fas fa-eye<?= $review['is_visible'] ? '-slash' : '' ?>"></i>
                                        </button>
                                    </form>
                                    <form action="<?= BASE_URL ?>index.php?page=admin&action=reviews" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <button type="submit" name="delete_review" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>