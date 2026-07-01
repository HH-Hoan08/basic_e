<?php
$pageTitle = htmlspecialchars($product->getName()) . ' — Basic Shop';

// chuẩn bị dữ liệu từ entities
$sizes  = [];
$colors = []; // ['tên' => 'hex']
foreach ($variants as $v) {
    if ($v->getSize()  && !in_array($v->getSize(), $sizes))        
        $sizes[] = $v->getSize();
    if ($v->getColor() && !isset($colors[$v->getColor()]))          
        $colors[$v->getColor()] = $v->getColorHex() ?? '#ccc';
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/shop_single.css">

<!-- Bắt đầu Nội dung -->
<section class="bg-light">
    <div class="container pb-5">
        <div class="row">

            <!-- ===== CỘT ẢNH ===== -->
            <div class="col-lg-5 mt-5">

                <!-- Ảnh chính -->
                <div class="card mb-3">
                    <?php
                        $mainImageUrl = BASE_URL . 'assets/img/' . ($product->getImage() ?? 'no-image.jpg');
                    ?>
                    <img class="card-img img-fluid"
                         src="<?= htmlspecialchars($mainImageUrl) ?>"
                         alt="<?= htmlspecialchars($product->getName()) ?>"
                         id="product-detail"
                         onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg'">
                </div>

                <div class="row">
                    <!-- Bắt đầu Điều khiển -->
                    <div class="col-1 align-self-center">
                        <a href="#multi-item-example" role="button" data-bs-slide="prev">
                            <i class="text-dark fas fa-chevron-left"></i>
                            <span class="sr-only">Trước</span>
                        </a>
                    </div>
                    <!-- Kết thúc Điều khiển -->

                    <!-- Bắt đầu Trình bao bọc Carousel -->
                    <div id="multi-item-example"
                         class="col-10 carousel slide carousel-multi-item"
                         data-bs-ride="carousel">
                        <!-- Bắt đầu Các slide -->
                        <div class="carousel-inner product-links-wap" role="listbox">

                            <?php if (!empty($images)): ?>

                                <?php foreach (array_chunk($images, 3) as $i => $chunk): ?>
                                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                        <div class="row">
                                            <?php foreach ($chunk as $img): ?>
                                                <div class="col-4">
                                                    <?php
                                                        // Giả định getImageUrl() trả về tên file, cần thêm đường dẫn đầy đủ
                                                        $thumbUrl = BASE_URL . 'assets/img/' . ($img->getImageUrl() ?? 'no-image.jpg');
                                                    ?>
                                                    <a href="#" onclick="changeMainImg('<?= htmlspecialchars($thumbUrl) ?>', this); return false;">
                                                        <img class="card-img img-fluid thumb-img"
                                                         src="<?= htmlspecialchars($thumbUrl) ?>"
                                                         alt="<?= htmlspecialchars($product->getName()) ?>"
                                                         onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg'">
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <!-- Không có ảnh phụ: hiện ảnh chính 3 lần -->
                                <div class="carousel-item active">
                                    <div class="row">
                                        <?php for ($i = 0; $i < 3; $i++): ?>
                                            <div class="col-4">
                                                <a href="#" onclick="changeMainImg('<?= htmlspecialchars($mainImageUrl) ?>', this); return false;">
                                                    <img class="card-img img-fluid thumb-img" src="<?= htmlspecialchars($mainImageUrl) ?>"
                                                         alt="<?= htmlspecialchars($product->getName()) ?>"
                                                         onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg'">
                                                </a>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                        <!-- Kết thúc Các slide -->
                    </div>
                    <!-- Kết thúc Trình bao bọc Carousel -->

                    <!-- Bắt đầu Điều khiển -->
                    <div class="col-1 align-self-center">
                        <a href="#multi-item-example" role="button" data-bs-slide="next">
                            <i class="text-dark fas fa-chevron-right"></i>
                            <span class="sr-only">Sau</span>
                        </a>
                    </div>
                    <!-- Kết thúc Điều khiển -->
                </div>

            </div>
            <!-- Kết thúc cột -->

            <!-- ===== CỘT THÔNG TIN ===== -->
            <div class="col-lg-7 mt-5">
                <div class="card">
                    <div class="card-body">

                        <!-- Tên -->
                        <h1 class="h2"><?= htmlspecialchars($product->getName()) ?></h1>

                        <!-- Giá -->
                        <p class="h3 py-2">
                            <span class="text-success">
                                <?= $product->formatPrice($product->getDisplayPrice()) ?>
                            </span>
                            <?php if ($product->hasDiscount()): ?>
                                <span class="text-muted text-decoration-line-through fs-5 ms-2">
                                    <?= $product->formatPrice($product->getPrice()) ?>
                                </span>
                                <span class="badge bg-danger ms-2 fs-6">
                                    -<?= $product->getDiscountPercent() ?>%
                                </span>
                            <?php endif; ?>
                        </p>

                        <!-- Sao đánh giá -->
                        <p class="py-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star <?= $i <= $product->getRatingRounded() ? 'text-warning' : 'text-secondary' ?>"></i>
                            <?php endfor; ?>
                            <span class="list-inline-item text-dark">
                                Đánh giá <?= $product->getAvgRating() ?> | <?= count($reviews) ?> Bình luận
                            </span>
                        </p>

                        <!-- Thương hiệu -->
                        <ul class="list-inline">
                            <li class="list-inline-item"><h6>Thương hiệu:</h6></li>
                            <li class="list-inline-item">
                                <p class="text-muted">
                                    <strong><?= htmlspecialchars($product->getBrandName() ?? $product->getBrand() ?? 'N/A') ?></strong>
                                </p>
                            </li>
                        </ul>

                        <!-- Danh mục -->
                        <ul class="list-inline">
                            <li class="list-inline-item"><h6>Danh mục:</h6></li>
                            <li class="list-inline-item">
                                <p class="text-muted">
                                    <strong><?= htmlspecialchars($product->getCategoryName() ?? '') ?></strong>
                                </p>
                            </li>
                        </ul>

                        <!-- Mô tả -->
                        <h6>Mô tả:</h6>
                        <p><?= nl2br(htmlspecialchars($product->getDescription() ?? 'Chưa có mô tả.')) ?></p>

                        <!-- Màu sắc có sẵn -->
                        <?php if (!empty($colors)): ?>
                            <ul class="list-inline">
                                <li class="list-inline-item"><h6>Màu sắc có sẵn :</h6></li>
                                <li class="list-inline-item">
                                    <?php foreach ($colors as $colorName => $colorHex): ?>
                                        <span class="color-swatch me-1"
                                              style="background: <?= htmlspecialchars($colorHex) ?>;"
                                              title="<?= htmlspecialchars($colorName) ?>"
                                              onclick="selectColor(this, '<?= htmlspecialchars($colorName) ?>')">
                                        </span>
                                    <?php endforeach; ?>
                                </li>
                            </ul>
                        <?php else: ?>
                            <ul class="list-inline">
                                <li class="list-inline-item"><h6>Màu sắc có sẵn :</h6></li>
                                <li class="list-inline-item">
                                    <p class="text-muted"><strong>Liên hệ shop</strong></p>
                                </li>
                            </ul>
                        <?php endif; ?>

                        <!-- Tình trạng kho -->
                        <ul class="list-inline">
                            <li class="list-inline-item"><h6>Tình trạng:</h6></li>
                            <li class="list-inline-item">
                                <?php 
                                    $stock = $product->getStock();
                                    if ($stock > 10): 
                                ?>
                                    <span class="badge bg-success">Còn hàng</span>
                                <?php elseif ($stock > 0): ?>
                                    <span class="badge bg-warning text-dark">Chỉ còn <?= $stock ?> sản phẩm</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Hết hàng</span>
                                <?php endif; ?>
                            </li>
                        </ul>

                        <!-- Form chọn size + số lượng + mua -->
                        <form action="<?= BASE_URL ?>index.php?page=cart&action=add" method="POST">
                            <input type="hidden" name="product_id"    value="<?= $product->getId() ?>">
                            <input type="hidden" name="product_size"  id="product-size"     value="">
                            <input type="hidden" name="product_color" id="product-color"    value="">
                            <input type="hidden" name="product_qty"   id="product_qty"      value="1">

                            <div class="row">
                                <!-- Kích cỡ -->
                                <div class="col-auto">
                                    <ul class="list-inline pb-3">
                                        <li class="list-inline-item">
                                            Kích cỡ :
                                            <input type="hidden" name="product-size"
                                                   id="product-size-display" value="">
                                        </li>
                                        <?php if (!empty($sizes)): ?>
                                            <?php foreach ($sizes as $size): ?>
                                                <li class="list-inline-item">
                                                    <span class="btn btn-success btn-size"
                                                          onclick="selectSize(this, '<?= htmlspecialchars($size) ?>')">
                                                        <?= htmlspecialchars($size) ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-inline-item"><span class="btn btn-success btn-size active" onclick="selectSize(this,'S')">S</span></li>
                                            <li class="list-inline-item"><span class="btn btn-success btn-size" onclick="selectSize(this,'M')">M</span></li>
                                            <li class="list-inline-item"><span class="btn btn-success btn-size" onclick="selectSize(this,'L')">L</span></li>
                                            <li class="list-inline-item"><span class="btn btn-success btn-size" onclick="selectSize(this,'XL')">XL</span></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <!-- Số lượng -->
                                <div class="col-auto">
                                    <ul class="list-inline pb-3">
                                        <li class="list-inline-item text-right">
                                            Số lượng
                                        </li>
                                        <li class="list-inline-item">
                                            <span class="btn btn-success" id="btn-minus">-</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <span class="badge bg-secondary px-3" id="var-value" style="font-size: 1rem;">1</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <span class="btn btn-success" id="btn-plus">+</span>
                                        </li>
                                    </ul>
                                    <small id="qty-alert" class="text-danger d-none" style="display: block; margin-top: -1rem; margin-bottom: 1rem;">
                                        Đã đạt số lượng tối đa trong kho.
                                    </small>
                                </div>
                            </div>

                            <!-- Nút Mua / Thêm giỏ -->
                            <div class="row pb-3">
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-success btn-lg"
                                            name="submit" value="buy"
                                            <?= !$product->inStock() ? 'disabled' : '' ?>>
                                        Mua
                                    </button>
                                </div>
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-success btn-lg"
                                            name="submit" value="addtocard"
                                            <?= !$product->inStock() ? 'disabled' : '' ?>>
                                        Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <!-- Kết thúc cột thông tin -->

        </div>
    </div>
</section>
<!-- Kết thúc Nội dung -->

<!-- ===== BÌNH LUẬN & ĐÁNH GIÁ ===== -->
<section id="reviews" class="bg-light py-4 border-top">
    <div class="container">
        <div class="row">

            <!-- Cột trái: Danh sách đánh giá -->
            <div class="col-lg-7">
                <h5 class="mb-3">
                    <i class="fa fa-star text-warning me-1"></i>
                    Đánh giá từ khách hàng
                    <span class="badge bg-success ms-1"><?= count($reviews) ?></span>
                </h5>

                <?php if (!empty($reviewMessage)): ?>
                    <div class="alert alert-<?= $reviewMessage['type'] ?> d-flex align-items-center gap-2">
                        <i class="fa fa-<?= $reviewMessage['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($reviewMessage['text']) ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($reviews)): ?>
                    <div class="text-center py-4 text-muted border rounded-3">
                        <i class="fa fa-comment-slash fa-2x mb-2 d-block"></i>
                        Chưa có đánh giá nào. Hãy là người đầu tiên!
                    </div>
                <?php else: ?>
                    <!-- Tổng quan rating -->
                    <div class="card border-0 bg-white shadow-sm mb-3 p-3">
                        <div class="row align-items-center">
                            <div class="col-auto text-center">
                                <div class="display-4 fw-bold text-success">
                                    <?= number_format($product->getAvgRating(), 1) ?>
                                </div>
                                <div class="mb-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa fa-star <?= $i <= $product->getRatingRounded() ? 'text-warning' : 'text-muted' ?>"
                                           style="font-size:14px;"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted"><?= count($reviews) ?> đánh giá</small>
                            </div>
                            <div class="col">
                                <?php
                                // Đếm số lượng mỗi mức sao
                                $starCounts = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
                                foreach ($reviews as $r) {
                                    $s = $r->getRating();
                                    if (isset($starCounts[$s])) $starCounts[$s]++;
                                }
                                $total = count($reviews) ?: 1;
                                foreach ([5,4,3,2,1] as $star):
                                    $pct = round($starCounts[$star] / $total * 100);
                                ?>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <small class="text-muted" style="width:30px;"><?= $star ?> <i class="fa fa-star text-warning" style="font-size:10px;"></i></small>
                                        <div class="progress flex-grow-1" style="height:8px;">
                                            <div class="progress-bar bg-warning"
                                                 style="width:<?= $pct ?>%;"></div>
                                        </div>
                                        <small class="text-muted" style="width:30px;"><?= $starCounts[$star] ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách review -->
                    <?php foreach ($reviews as $r): ?>
                        <div class="card border-0 shadow-sm mb-2">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                                             style="width:36px;height:36px;font-size:14px;flex-shrink:0;">
                                            <?= mb_strtoupper(mb_substr($r->getUserFullname() ?? '?', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong class="d-block" style="font-size:14px;">
                                                <?= htmlspecialchars($r->getUserFullname() ?? 'Khách hàng') ?>
                                            </strong>
                                            <div>
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fa fa-star <?= $i <= $r->getRating() ? 'text-warning' : 'text-muted' ?>"
                                                       style="font-size:11px;"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fa fa-clock me-1"></i>
                                        <?= $r->getFormattedDate() ?>
                                    </small>
                                </div>
                                <?php if ($r->getComment()): ?>
                                    <p class="mb-0 text-muted small mt-2 ps-1">
                                        <?= nl2br(htmlspecialchars($r->getComment())) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Cột phải: Form gửi đánh giá -->
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="mb-3">
                        <i class="fa fa-pen me-1 text-success"></i> Gửi đánh giá của bạn
                    </h5>

                    <?php if (!$isLoggedIn): ?>
                        <!-- Chưa đăng nhập -->
                        <div class="text-center py-4">
                            <i class="fa fa-lock fa-2x text-muted mb-2 d-block"></i>
                            <p class="text-muted">Vui lòng đăng nhập để đánh giá sản phẩm.</p>
                            <a href="<?= BASE_URL ?>index.php?page=login"
                               class="btn btn-success px-4">
                                <i class="fa fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        </div>

                    <?php elseif (!$canReview['can']): ?>
                        <!-- Đã đăng nhập nhưng không đủ điều kiện -->
                        <div class="alert alert-warning d-flex gap-2 align-items-start">
                            <i class="fa fa-exclamation-triangle mt-1"></i>
                            <div>
                                <strong>Không thể đánh giá</strong><br>
                                <small><?= htmlspecialchars($canReview['reason']) ?></small>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Form đánh giá -->
                        <form action="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= htmlspecialchars($product->getSlug()) ?>"
                              method="POST">
                            <input type="hidden" name="submit_review" value="1">
                            <input type="hidden" name="product_id"   value="<?= $product->getId() ?>">
                            <input type="hidden" name="slug"         value="<?= htmlspecialchars($product->getSlug()) ?>">
                            <input type="hidden" name="rating"       id="ratingInput" value="0">

                            <!-- Chọn sao -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đánh giá <span class="text-danger">*</span></label>
                                <div class="star-rating d-flex gap-1" id="starRating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa fa-star text-muted"
                                           style="font-size:28px;cursor:pointer;transition:color .15s;"
                                           data-star="<?= $i ?>"
                                           onmouseover="hoverStar(<?= $i ?>)"
                                           onmouseout="resetStar()"
                                           onclick="selectStar(<?= $i ?>)"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted" id="starLabel">Chọn số sao</small>
                            </div>

                            <!-- Nhận xét -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="reviewComment">
                                    Nhận xét
                                </label>
                                <textarea class="form-control"
                                          id="reviewComment"
                                          name="comment"
                                          rows="4"
                                          placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                            </div>

                            <button type="submit"
                                    class="btn btn-success w-100"
                                    id="submitReviewBtn"
                                    disabled>
                                <i class="fa fa-paper-plane me-1"></i> Gửi đánh giá
                            </button>
                        </form>

                        <!-- Script star rating -->
                        <script>
                        let selectedStar = 0;
                        const starLabels = ['', 'Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Xuất sắc'];

                        function hoverStar(n) {
                            document.querySelectorAll('#starRating .fa-star').forEach((s, i) => {
                                s.classList.toggle('text-warning', i < n);
                                s.classList.toggle('text-muted',   i >= n);
                            });
                        }

                        function resetStar() {
                            hoverStar(selectedStar);
                        }

                        function selectStar(n) {
                            selectedStar = n;
                            document.getElementById('ratingInput').value = n;
                            document.getElementById('starLabel').textContent = starLabels[n];
                            document.getElementById('submitReviewBtn').disabled = (n === 0);
                            hoverStar(n);
                        }
                        </script>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
.rating-stars {
    display: inline-block;
    direction: rtl; /* Right to left to make stars select from left */
}
.rating-stars input[type="radio"] {
    display: none;
}
.rating-stars label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}
.rating-stars input[type="radio"]:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}
</style>


<!-- Bắt đầu Sản phẩm liên quan -->
<section class="py-5">
    <div class="container">
        <div class="row text-left p-2 pb-3">
            <h4>Sản phẩm liên quan</h4>
        </div>

        <div id="carousel-related-product" class="row">

            <?php if (!empty($related)): ?>
                <?php foreach ($related as $rp): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 product-wap rounded-0">
                            <div class="card-header p-0 position-relative">
                                <a href="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= $rp->getSlug() ?>" class="product-img-container">
                                    <?php
                                        $relatedImgUrl = BASE_URL . 'assets/img/' . ($rp->getImage() ?? 'no-image.jpg');
                                    ?>
                                    <img class="card-img rounded-0 img-fluid product-img"
                                        src="<?= htmlspecialchars($relatedImgUrl) ?>"
                                        alt="<?= htmlspecialchars($rp->getName()) ?>"
                                        onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg'">
                                </a>
                                <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a class="btn btn-success text-white"
                                               href="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= $rp->getSlug() ?>">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="btn btn-success text-white mt-2"
                                               href="<?= BASE_URL ?>index.php?page=cart&action=add&id=<?= $rp->getId() ?>">
                                                <i class="fas fa-cart-plus"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <a href="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= $rp->getSlug() ?>"
                                   class="h3 text-decoration-none">
                                    <?= htmlspecialchars($rp->getName()) ?>
                                </a>
                                <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                    <li class="text-muted small">
                                        <?= htmlspecialchars($rp->getBrandName() ?? $rp->getBrand() ?? '') ?>
                                    </li>
                                    <li class="pt-2">
                                        <?php
                                        // Lấy màu của sản phẩm liên quan
                                        $relVariants = $productModel->getVariants($rp->getId());
                                        $relColors   = [];
                                        foreach ($relVariants as $rv) {
                                            if ($rv->getColor() && !isset($relColors[$rv->getColor()])) {
                                                $relColors[$rv->getColor()] = $rv->getColorHex() ?? '#ccc';
                                            }
                                        }
                                        foreach ($relColors as $rColorName => $rColorHex):
                                        ?>
                                            <span class="product-color-dot float-left rounded-circle ml-1"
                                                  style="background:<?= htmlspecialchars($rColorHex) ?>;
                                                         width:14px;height:14px;display:inline-block;
                                                         border:1px solid #ccc;"
                                                  title="<?= htmlspecialchars($rColorName) ?>">
                                            </span>
                                        <?php endforeach; ?>
                                    </li>
                                </ul>
                                <ul class="list-unstyled d-flex justify-content-center mb-1">
                                    <li>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa fa-star <?= $i <= $rp->getRatingRounded() ? 'text-warning' : 'text-muted' ?>"></i>
                                        <?php endfor; ?>
                                    </li>
                                </ul>
                                <p class="text-center mb-0">
                                    <?= $rp->formatPrice($rp->getDisplayPrice()) ?>
                                </p>
                            </div>
                        </div>
                <?php endforeach; ?>

            <?php else: ?>
                <p class="text-muted">Không có sản phẩm liên quan.</p>
            <?php endif; ?>

        </div>
    </div>
</section>
<!-- Kết thúc Sản phẩm liên quan -->

<script>
// Đổi ảnh chính + active thumbnail
function changeMainImg(src, el) {
    document.getElementById('product-detail').src = src;
    document.querySelectorAll('.thumb-img').forEach(t => t.classList.remove('active'));
    el.querySelector('img').classList.add('active');
}

// Chọn size
function selectSize(btn, size) {
    document.querySelectorAll('.btn-size').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('product-size').value = size;
}

// Chọn màu
function selectColor(el, color) {
    document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('product-color').value = color;
}

// Tăng / giảm số lượng
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('product_qty');
    const qtyDisplay = document.getElementById('var-value');
    const btnPlus = document.getElementById('btn-plus');
    const btnMinus = document.getElementById('btn-minus');
    const qtyAlert = document.getElementById('qty-alert');
    const maxQty = <?= (int)$product->getStock() ?>;
    
    let max = maxQty;
    
    function updateQtyControls() {
        let currentQty = parseInt(qtyInput.value);
    
        btnMinus.classList.toggle('disabled', currentQty <= 1);
        btnPlus.classList.toggle('disabled', currentQty >= maxQty);
        if (qtyAlert) {
            qtyAlert.classList.toggle('d-none', currentQty < maxQty);
        }
    }

    btnPlus.addEventListener('click', function () {
        // Dừng ngay nếu nút đã bị vô hiệu hóa (chống click nhanh)
        let qty = parseInt(qtyInput.value);
        // Kiểm tra lại giá trị một lần nữa để đảm bảo an toàn
        if (qty < maxQty) {
            qty++;
            qtyInput.value = qty;
            qtyDisplay.textContent = qty;
        }
        // Luôn gọi updateQtyControls để cập nhật trạng thái nút và cảnh báo
        updateQtyControls();
    });

    btnMinus.addEventListener('click', function () {
        if (this.classList.contains('disabled')) {
            return;
        }
        let qty = parseInt(qtyInput.value);
        if (qty > 1) {
            qty--;
            qtyInput.value = qty;
            qtyDisplay.textContent = qty;
            updateQtyControls();
        }
    });

    // Kiểm tra trạng thái ban đầu khi tải trang
    updateQtyControls();
});
</script>

<?php require_once __DIR__ . '/inc/footer.php'; ?>