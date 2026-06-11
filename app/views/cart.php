<?php require_once __DIR__ . '/inc/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Giỏ hàng của bạn</h1>
            
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
                                <?php 
                                    $totalAmount = 0;
                                    foreach ($cart as $key => $item): 
                                        $itemTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                                        $totalAmount += $itemTotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="assets/img/<?= htmlspecialchars($item['image'] ?? 'shop_01.jpg') ?>" class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="...">
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
                                        <a href="index.php?page=cart&action=remove&key=<?= $key ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng tiền thanh toán:</td>
                                    <td><strong class="text-danger fs-5"><?= number_format($totalAmount, 0, ',', '.') ?>đ</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?page=shop" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
                        <div>
                            <button type="submit" class="btn btn-secondary me-2">Cập nhật giỏ hàng</button>
                            <a href="index.php?page=checkout" class="btn btn-success">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>