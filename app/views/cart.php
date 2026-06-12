<?php require_once __DIR__ . '/inc/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Giỏ hàng của bạn</h1>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (empty($cart)): ?>
                <div class="alert alert-info text-center py-4">
                    Giỏ hàng của bạn đang trống. <br>
                    <a href="index.php?page=shop" class="btn btn-success mt-3">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <form action="index.php?page=cart&action=update" method="POST">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th style="width: 150px;">Số lượng</th>
                                    <th>Tổng cộng</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $key => $item): 
                                    $itemTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($item['image'] ?? 'shop_01.jpg') ?>" class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="...">
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($item['name'] ?? 'Sản phẩm') ?></h6>
                                                <?php if(!empty($item['size'])): ?>
                                                    <small class="text-muted">Size: <?= htmlspecialchars($item['size']) ?></small><br>
                                                <?php endif; ?>
                                                <?php if(!empty($item['color'])): ?>
                                                    <small class="text-muted">Màu: <?= htmlspecialchars($item['color']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= number_format($item['price'] ?? 0, 0, ',', '.') ?>đ</td>
                                    <td>
                                        <input type="number" name="qty[<?= $key ?>]" class="form-control" value="<?= $item['quantity'] ?? 1 ?>" min="1">
                                    </td>
                                    <td><strong class="text-danger"><?= number_format($itemTotal, 0, ',', '.') ?>đ</strong></td>
                                    <td>
                                        <a href="index.php?page=cart&action=remove&key=<?= $key ?>" class="btn btn-sm btn-outline-danger">Xóa</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-secondary">Cập nhật giỏ hàng</button>
                    </div>
                </form>

                <div class="row mt-4">
                    <!-- Voucher Form -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Mã giảm giá</h5>
                                <?php if (isset($voucher)): ?>
                                    <div class="alert alert-success d-flex justify-content-between align-items-center p-2">
                                        <span>Đang áp dụng: <strong><?= htmlspecialchars($voucher['code']) ?></strong></span>
                                        <a href="index.php?page=cart&action=remove_voucher" class="btn-close"></a>
                                    </div>
                                <?php else: ?>
                                    <form action="index.php?page=cart&action=apply_voucher" method="POST" class="d-flex gap-2">
                                        <input type="text" name="voucher_code" class="form-control text-uppercase" placeholder="Nhập mã voucher">
                                        <button type="submit" class="btn btn-success">Áp dụng</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Tổng cộng</h5>
                                <div class="d-flex justify-content-between mb-2"><span>Tạm tính:</span> <span><?= number_format($subtotal, 0, ',', '.') ?>đ</span></div>
                                <?php if (isset($voucher)): ?>
                                    <div class="d-flex justify-content-between mb-2 text-success"><span>Giảm giá (<?= htmlspecialchars($voucher['code']) ?>):</span> <span>- <?= number_format($discountAmount, 0, ',', '.') ?>đ</span></div>
                                <?php endif; ?>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5"><span class="text-danger">Thành tiền:</span> <span class="text-danger"><?= number_format($finalTotal, 0, ',', '.') ?>đ</span></div>
                                <a href="index.php?page=cart&action=checkout" class="btn btn-lg btn-success w-100 mt-3">Tiến hành thanh toán</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>