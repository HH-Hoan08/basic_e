<h2 class="mb-4">Quản lý Sản phẩm</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message['type']) ?>"><i class="fas fa-<?= $message['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-1"></i> <?= htmlspecialchars($message['text']) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= BASE_URL ?>index.php?page=admin&action=add_product" class="btn btn-success"><i class="fas fa-plus me-2"></i>Thêm sản phẩm mới</a>
</div>

<!-- Bộ lọc sản phẩm -->
<div class="mb-3">
    <strong>Lọc theo:</strong>
    <a href="<?= BASE_URL ?>index.php?page=admin&action=products&filter=newest" class="btn btn-sm <?= ($current_filter ?? 'newest') === 'newest' ? 'btn-primary' : 'btn-outline-primary' ?>">Mới nhất</a>
    <a href="<?= BASE_URL ?>index.php?page=admin&action=products&filter=bestseller" class="btn btn-sm <?= ($current_filter ?? '') === 'bestseller' ? 'btn-primary' : 'btn-outline-primary' ?>">Bán chạy</a>
    <a href="<?= BASE_URL ?>index.php?page=admin&action=products&filter=worstseller" class="btn btn-sm <?= ($current_filter ?? '') === 'worstseller' ? 'btn-primary' : 'btn-outline-primary' ?>">Bán ế</a>
</div>

<form action="<?= BASE_URL ?>index.php?page=admin&action=bulk_action_products" method="POST" id="bulk-action-form">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Danh sách sản phẩm</span>
            <div id="bulk-actions-container" class="d-none">
                <div class="input-group">
                    <select name="bulk_action" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">Chọn hành động...</option>
                        <option value="apply_discount">Áp dụng giảm giá (%)</option>
                        <option value="remove_discount">Xóa giảm giá</option>
                        <option value="delete" class="text-danger">Xóa sản phẩm đã chọn</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary" type="submit">Áp dụng</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;"><input class="form-check-input" type="checkbox" id="select-all-checkbox"></th>
                        <th style="width: 80px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Đã bán</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="10" class="text-center py-4 text-muted">Chưa có sản phẩm nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="text-center"><input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="<?= $product['id'] ?>"></td>
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
                            <td class="fw-bold"><?= htmlspecialchars($product['sold_count'] ?? 0) ?></td>
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
</form>

<!-- [MỚI] Modal Nhập Phần Trăm Giảm Giá -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="discountModalLabel">Áp dụng giảm giá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="discount-percentage-input" class="form-label">Nhập phần trăm giảm giá (%):</label>
        <input type="number" class="form-control" id="discount-percentage-input" placeholder="Ví dụ: 10" min="1" max="100">
        <div id="discount-error" class="text-danger mt-2 d-none"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="confirm-discount-btn">Xác nhận</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActionsContainer = document.getElementById('bulk-actions-container');
    const bulkForm = document.getElementById('bulk-action-form');

    // [MỚI] Khởi tạo các thành phần của modal
    const discountModalElement = document.getElementById('discountModal');
    const discountModal = new bootstrap.Modal(discountModalElement);
    const confirmDiscountBtn = document.getElementById('confirm-discount-btn');
    const discountInput = document.getElementById('discount-percentage-input');
    const discountError = document.getElementById('discount-error');

    // Hàm để hiển thị/ẩn menu hành động hàng loạt
    function toggleBulkActions() {
        const anyChecked = Array.from(productCheckboxes).some(cb => cb.checked);
        bulkActionsContainer.classList.toggle('d-none', !anyChecked);
    }

    // Sự kiện cho checkbox "chọn tất cả"
    selectAllCheckbox.addEventListener('change', function () {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });

    // Sự kiện cho từng checkbox sản phẩm
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            // [CẢI TIẾN] Tự động check/uncheck ô "chọn tất cả"
            selectAllCheckbox.checked = Array.from(productCheckboxes).every(cb => cb.checked);
            toggleBulkActions();
        });
    });

    // [MỚI] Xử lý form hành động hàng loạt
    bulkForm.addEventListener('submit', function(e) {
        const action = this.elements['bulk_action'].value;

        if (action === '') {
            alert('Vui lòng chọn một hành động.');
            e.preventDefault();
            return;
        }

        // Nếu là "Áp dụng giảm giá", hiển thị modal thay vì submit ngay
        if (action === 'apply_discount') {
            e.preventDefault(); // Ngăn form submit
            discountInput.value = ''; // Reset input
            discountError.classList.add('d-none'); // Ẩn thông báo lỗi cũ
            discountModal.show();
            return;
        }

        // Đối với các hành động khác, chỉ cần dùng confirm() như cũ
        let confirmMessage = 'Bạn có chắc chắn muốn thực hiện hành động này với các mục đã chọn?';
        if (action === 'remove_discount') {
            confirmMessage = 'Bạn có chắc muốn xóa giảm giá cho các sản phẩm đã chọn?';
        } else if (action === 'delete') {
            confirmMessage = 'Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?';
        }

        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });

    // [MỚI] Xử lý khi nhấn nút "Xác nhận" trong modal
    confirmDiscountBtn.addEventListener('click', function() {
        const percentageValue = parseFloat(discountInput.value);

        if (isNaN(percentageValue) || percentageValue <= 0 || percentageValue > 100) {
            discountError.textContent = "Vui lòng nhập một số từ 1 đến 100.";
            discountError.classList.remove('d-none');
            return;
        }

        // Xóa input cũ nếu có để tránh trùng lặp
        const oldInput = bulkForm.querySelector('input[name="discount_percentage"]');
        if (oldInput) oldInput.remove();
        
        // Tạo input ẩn chứa giá trị % và thêm vào form
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'discount_percentage';
        input.value = percentageValue;
        bulkForm.appendChild(input);

        // Đóng modal và submit form
        discountModal.hide();
        bulkForm.submit();
    });
});
</script>