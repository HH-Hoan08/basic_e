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

<!--
    CSS Fix: Đảm bảo ảnh sản phẩm (cả chính và thumbnail) hiển thị nguyên vẹn,
    không bị phóng to hay cắt xén. Sử dụng object-fit: contain để ảnh vừa vặn
    trong khung chứa của nó.
-->
<style>
    #product-detail, .thumb-img {
        object-fit: contain; /* Hiển thị toàn bộ ảnh, không bị cắt xén */
        background-color: #f8f9fa; /* Thêm màu nền cho phần trống (nếu có) */
    }
</style>

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
                                <?php if ($product->inStock()): ?>
                                    <span class="badge bg-success">Còn <?= $product->getStock() ?> sản phẩm</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Hết hàng</span>
                                <?php endif; ?>
                            </li>
                        </ul>

                        <!-- Specification: hiển thị từng dòng description -->
                        <h6>Specification:</h6>
                        <ul class="list-unstyled pb-3">
                            <?php
                            $lines = array_filter(
                                explode("\n", $product->getDescription() ?? ''),
                                fn($l) => trim($l) !== ''
                            );
                            if (!empty($lines)):
                                foreach ($lines as $line):
                            ?>
                                <li><?= htmlspecialchars(trim($line)) ?></li>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <li>Chưa có thông số kỹ thuật.</li>
                            <?php endif; ?>
                        </ul>

                        <!-- Form chọn size + số lượng + mua -->
                        <form action="<?= BASE_URL ?>index.php?page=cart&action=add" method="POST">
                            <input type="hidden" name="product_id"    value="<?= $product->getId() ?>">
                            <input type="hidden" name="product_size"  id="product-size"     value="">
                            <input type="hidden" name="product_color" id="product-color"    value="">
                            <input type="hidden" name="product_qty"   id="product-quanity"  value="1">

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
                                            <input type="hidden" name="product-quanity"
                                                   id="product-quanity" value="1">
                                        </li>
                                        <li class="list-inline-item">
                                            <span class="btn btn-success" id="btn-minus">-</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <span class="badge bg-secondary" id="var-value">1</span>
                                        </li>
                                        <li class="list-inline-item">
                                            <span class="btn btn-success" id="btn-plus">+</span>
                                        </li>
                                    </ul>
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

<!-- ===== BÌNH LUẬN ===== -->
<?php if (!empty($reviews)): ?>
<section class="bg-light py-4 border-top">
    <div class="container">
        <h5 class="mb-3">Bình luận (<?= count($reviews) ?>)</h5>
        <div class="row">
            <?php foreach ($reviews as $r): ?>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong><?= htmlspecialchars($r->getUserFullname()) ?></strong>
                            <small class="text-muted"><?= $r->getFormattedDate() ?></small>
                        </div>
                        <div class="mb-1">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star <?= $i <= $r->getRating() ? 'text-warning' : 'text-secondary' ?>"
                                   style="font-size:12px;"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="mb-0 text-muted small">
                            <?= htmlspecialchars($r->getComment() ?? '') ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== VIẾT BÌNH LUẬN ===== -->
<section class="py-5">
    <div class="container">
        <h5 class="mb-3">Viết đánh giá của bạn</h5>

        <?php if (isset($reviewMessage)): ?>
            <div class="alert alert-<?= htmlspecialchars($reviewMessage['type']) ?>"><?= htmlspecialchars($reviewMessage['text']) ?></div>
        <?php endif; ?>

        <?php if ($canReview['can']): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= $product->getSlug() ?>" method="POST">
                        <input type="hidden" name="submit_review" value="1">
                        <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                        <input type="hidden" name="slug" value="<?= $product->getSlug() ?>">

                        <div class="mb-3">
                            <label class="form-label">Chấm điểm của bạn:</label>
                            <div class="rating-stars">
                                <input type="radio" name="rating" id="rs5" value="5" required><label for="rs5">★</label>
                                <input type="radio" name="rating" id="rs4" value="4"><label for="rs4">★</label>
                                <input type="radio" name="rating" id="rs3" value="3"><label for="rs3">★</label>
                                <input type="radio" name="rating" id="rs2" value="2"><label for="rs2">★</label>
                                <input type="radio" name="rating" id="rs1" value="1"><label for="rs1">★</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Bình luận của bạn:</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Sản phẩm rất tuyệt vời..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Gửi đánh giá</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fa fa-info-circle me-2"></i>
                <?= htmlspecialchars($canReview['reason']) ?>
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="<?= BASE_URL ?>index.php?page=login" class="alert-link">Đăng nhập ngay</a>.
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
let qty      = 1;
const maxQty = <?= (int)$product->getStock() ?>;

document.getElementById('btn-plus').addEventListener('click', function () {
    if (qty < maxQty) {
        qty++;
        document.getElementById('var-value').textContent      = qty;
        document.getElementById('product-quanity').value      = qty;
    }
});

document.getElementById('btn-minus').addEventListener('click', function () {
    if (qty > 1) {
        qty--;
        document.getElementById('var-value').textContent      = qty;
        document.getElementById('product-quanity').value      = qty;
    }
});
</script>

<?php require_once __DIR__ . '/inc/footer.php'; ?>