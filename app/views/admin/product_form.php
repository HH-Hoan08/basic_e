<?php
$isEdit = isset($product) && !empty($product['id']);
$pageTitle = $isEdit ? 'Chỉnh sửa Sản phẩm' : 'Thêm Sản phẩm mới';
?>
<h2 class="mb-4"><?= $pageTitle ?></h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= BASE_URL ?>index.php?page=admin&action=<?= $isEdit ? 'edit_product&id=' . $product['id'] : 'add_product' ?>" method="POST" enctype="multipart/form-data">
            <?php if ($isEdit): ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Giá gốc <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price'] ?? '') ?>" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                            <input type="number" class="form-control" id="sale_price" name="sale_price" value="<?= htmlspecialchars($product['sale_price'] ?? '') ?>" min="0">
                            <small class="text-muted">Để trống nếu không giảm giá.</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="brand_id" class="form-label">Thương hiệu</label>
                        <select class="form-select" id="brand_id" name="brand_id">
                            <option value="">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id'] ?>" <?= (isset($product['brand_id']) && $product['brand_id'] == $brand['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($brand['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Giới tính <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="unisex" <?= (isset($product['gender']) && $product['gender'] == 'unisex') ? 'selected' : '' ?>>Unisex</option>
                            <option value="male" <?= (isset($product['gender']) && $product['gender'] == 'male') ? 'selected' : '' ?>>Nam</option>
                            <option value="female" <?= (isset($product['gender']) && $product['gender'] == 'female') ? 'selected' : '' ?>>Nữ</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($product['stock'] ?? 0) ?>" required min="0">
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <?php if ($isEdit && !empty($product['image'])): ?>
                            <div class="mt-2">
                                <small>Ảnh hiện tại:</small><br>
                                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($product['image']) ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?= (isset($product['is_active']) && $product['is_active'] == 1) || !$isEdit ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Đang hoạt động (hiển thị)</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" <?= (isset($product['is_featured']) && $product['is_featured'] == 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>index.php?page=admin&action=products" class="btn btn-secondary">Hủy</a>
                <button type="submit" name="save_product" class="btn btn-success"><?= $isEdit ? 'Cập nhật sản phẩm' : 'Lưu sản phẩm' ?></button>
            </div>
        </form>
    </div>
</div>