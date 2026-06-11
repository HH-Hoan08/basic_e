<?php $pageTitle = 'Cửa hàng — Basic Shop'; ?>
<!-- Modal tìm kiếm -->
<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= BASE_URL ?>index.php" method="GET"
              class="modal-content modal-body border-0 p-0">
            <input type="hidden" name="page" value="shop">
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="q"
                       placeholder="Tìm kiếm sản phẩm..."
                       value="<?= htmlspecialchars($filter['q'] ?? '') ?>">
                <button type="submit" class="input-group-text bg-success text-light">
                    <i class="fa fa-fw fa-search text-white"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container py-5">
    <div class="row">

        <!-- ===== SIDEBAR ===== -->
        <div class="col-lg-3">
            <h5 class="mb-4">Danh mục</h5>

            <!-- Lọc giới tính -->
            <p class="fw-bold mb-2">
                Giới tính <i class="fa fa-chevron-down float-end text-muted"></i>
            </p>
            <div class="d-flex flex-column gap-1 mb-4">
                <?php
                $genders = ['all'=>'Tất cả','male'=>'Của nam','female'=>'Của nữ','unisex'=>'Unisex'];
                foreach ($genders as $val => $label):
                    $active = ($filter['gender'] === $val) ? 'text-success fw-bold' : 'text-muted';
                ?>
                    <a href="<?= BASE_URL ?>index.php?page=shop&gender=<?= $val ?>&sort=<?= $filter['sort'] ?>"
                       class="text-decoration-none <?= $active ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Lọc thương hiệu -->
            <p class="fw-bold mb-2">
                Thương hiệu <i class="fa fa-chevron-down float-end text-muted"></i>
            </p>
            <div class="d-flex flex-column gap-1 mb-4">
                <a href="<?= BASE_URL ?>index.php?page=shop&gender=<?= $filter['gender'] ?>"
                   class="text-decoration-none <?= empty($filter['brand_id']) ? 'text-success fw-bold' : 'text-muted' ?>">
                    Tất cả thương hiệu
                </a>
                <?php foreach ($brands as $b):
                    $active = ($filter['brand_id'] == $b->getId()) ? 'text-success fw-bold' : 'text-muted';
                ?>
                    <a href="<?= BASE_URL ?>index.php?page=shop&gender=<?= $filter['gender'] ?>&brand_id=<?= $b->getId() ?>"
                       class="text-decoration-none <?= $active ?>">
                        <?= htmlspecialchars($b->getName()) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Lọc giảm giá -->
            <p class="fw-bold mb-2">
                Giảm giá <i class="fa fa-chevron-down float-end text-muted"></i>
            </p>
            <a href="<?= BASE_URL ?>index.php?page=shop&gender=<?= $filter['gender'] ?>&on_sale=1"
               class="text-decoration-none <?= !empty($filter['on_sale']) ? 'text-success fw-bold' : 'text-muted' ?>">
                Chỉ xem hàng giảm giá
            </a>
        </div>

        <!-- ===== SẢN PHẨM ===== -->
        <div class="col-lg-9">

            <!-- Tab + sắp xếp -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <ul class="nav nav-pills gap-2">
                    <?php foreach (['all'=>'Tất cả','male'=>'Của nam','female'=>'Của nữ'] as $val=>$label): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $filter['gender']===$val ? 'active bg-success':'text-dark border' ?>"
                               href="<?= BASE_URL ?>index.php?page=shop&gender=<?= $val ?>&sort=<?= $filter['sort'] ?>">
                                <?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <select class="form-select w-auto" onchange="location.href=this.value">
                    <?php
                    $sorts = [
                        'featured'   => 'Nổi bật',
                        'newest'     => 'Mới nhất',
                        'bestseller' => 'Bán chạy nhất',
                        'price_asc'  => 'Giá tăng dần',
                        'price_desc' => 'Giá giảm dần',
                        'rating'     => 'Đánh giá cao',
                    ];
                    foreach ($sorts as $val => $label):
                        $url = BASE_URL.'index.php?page=shop&gender='.$filter['gender'].'&sort='.$val;
                    ?>
                        <option value="<?= $url ?>" <?= $filter['sort']===$val?'selected':'' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Thông báo tìm kiếm -->
            <?php if (!empty($filter['q'])): ?>
                <p class="text-muted mb-3">
                    Kết quả cho: <strong>"<?= htmlspecialchars($filter['q']) ?>"</strong>
                    — <?= $total ?> sản phẩm
                </p>
            <?php endif; ?>

            <!-- Lưới sản phẩm -->
            <?php if (empty($products)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-box-open fa-3x mb-3 d-block"></i>
                    <p>Không tìm thấy sản phẩm phù hợp.</p>
                    <a href="<?= BASE_URL ?>index.php?page=shop" class="btn btn-outline-success">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                <?php foreach ($products as $p):
                    $displayPrice = $p->getDisplayPrice();
                    $hasDiscount  = $p->hasDiscount();
                    $discount     = $p->getDiscountPercent();
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm product-card">

                            <?php if ($hasDiscount): ?>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                    -<?= $discount ?>%
                                </span>
                            <?php endif; ?>

                            <?php if ($p->getIsFeatured()): ?>
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                    Nổi bật
                                </span>
                            <?php endif; ?>
                            
                            <!-- link ảnh sản phẩm, nếu ảnh lỗi sẽ hiển thị ảnh mặc định no-image.jpg
                            <a href="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= $p->getSlug() ?>">
                                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($p->getImage() ?? 'no-image.jpg') ?>"
                                    class="card-img-top product-img"
                                    alt="<?= htmlspecialchars($p->getName()) ?>"
                                    onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg'">
                            </a> -->
                           

                            <div class="card-body">
                                <p class="text-muted small mb-1">
                                    <?= htmlspecialchars($p->getBrandName() ?? '') ?>
                                </p>
                                <h6 class="card-title mb-1">
                                    <a href="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= $p->getSlug() ?>"
                                       class="text-dark text-decoration-none">
                                        <?= htmlspecialchars($p->getName()) ?>
                                    </a>
                                </h6>

                                <!-- Sao đánh giá -->
                                <div class="mb-2">
                                    <?php $rating = round($p->getAvgRating());
                                    for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa fa-star <?= $i<=$rating?'text-warning':'text-muted' ?>"
                                           style="font-size:12px;"></i>
                                    <?php endfor; ?>
                                    <small class="text-muted">(<?= $p->getAvgRating() ?>)</small>
                                </div>

                                <!-- Giá -->
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-success fw-bold">
                                        <?= number_format($displayPrice,0,',','.') ?>đ
                                    </span>
                                    <?php if ($hasDiscount): ?>
                                        <span class="text-muted text-decoration-line-through small">
                                            <?= number_format($p->getPrice(),0,',','.') ?>đ
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                <a href="<?= BASE_URL ?>index.php?page=cart&action=add&id=<?= $p->getId() ?>"
                                   class="btn btn-outline-success btn-sm w-100">
                                    <i class="fa fa-cart-plus me-1"></i>Thêm vào giỏ
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>

                <!-- Phân trang -->
                <?php if ($totalPages > 1): ?>
                <nav class="mt-5 d-flex justify-content-center">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++):
                            $url = BASE_URL.'index.php?page=shop&gender='.$filter['gender']
                                  .'&sort='.$filter['sort'].'&pg='.$i;
                        ?>
                            <li class="page-item <?= $filter['page']==$i?'active':'' ?>">
                                <a class="page-link <?= $filter['page']==$i?'bg-success border-success':'' ?>"
                                   href="<?= $url ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>
