<h2 class="mb-4">Quản lý Sản phẩm</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><i class="fas fa-<?= $message['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-1"></i> <?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= BASE_URL ?>index.php?page=admin&action=add_product" class="btn btn-success"><i class="fas fa-plus me-2"></i>Thêm sản phẩm mới</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có sản phẩm nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($product['image'] ?? 'shop_01.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($product['brand_name'] ?? 'N/A') ?></td>
                            <td class="text-danger">
                                <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                    <?= number_format($product['sale_price'], 0, ',', '.') ?>đ<br>
                                    <small class="text-muted text-decoration-line-through"><?= number_format($product['price'], 0, ',', '.') ?>đ</small>
                                <?php else: ?>
                                    <?= number_format($product['price'], 0, ',', '.') ?>đ
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['stock']) ?></td>
                            <td>
                                <span class="badge bg-<?= $product['is_active'] ? 'success' : 'secondary' ?>"><?= $product['is_active'] ? 'Đang bán' : 'Ngừng bán' ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>index.php?page=admin&action=edit_product&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Sửa</a>
                                <form action="<?= BASE_URL ?>index.php?page=admin&action=delete_product" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
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