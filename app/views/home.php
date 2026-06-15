    <!-- Bắt đầu Banner -->
    <div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="<?= BASE_URL ?>assets/img/banner_img_01.jpg" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left align-self-center">
                                <h1 class="h1 text-success"><b>Basic</b> eCommerce</h1>
                                <h3 class="h2">Mẫu eCommerce nhỏ gọn và hoàn hảo</h3>
                                <p>
                                    Basic Shop là một mẫu eCommerce HTML5 CSS với phiên bản mới nhất của Bootstrap 5 (beta 1).
                                    Mẫu này được cung cấp miễn phí 100% bởi trang web <a rel="sponsored" class="text-success" href="https://templatemo.com" target="_blank">TemplateMo</a>.
                                    Nguồn ảnh từ <a rel="sponsored" class="text-success" href="https://stories.freepik.com/" target="_blank">Freepik Stories</a>,
                                    <a rel="sponsored" class="text-success" href="https://unsplash.com/" target="_blank">Unsplash</a> và
                                    <a rel="sponsored" class="text-success" href="https://icons8.com/" target="_blank">Icons 8</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="<?= BASE_URL ?>assets/img/banner_img_02.jpg" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h1 class="h1">Proident occaecat</h1>
                                <h3 class="h2">Aliquip ex ea commodo consequat</h3>
                                <p>
                                    Bạn được phép sử dụng mẫu Basic CSS này cho các trang web thương mại của mình.
                                    Bạn <strong>không được phép</strong> phân phối lại tệp ZIP của mẫu trên bất kỳ loại trang web bộ sưu tập mẫu nào.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="<?= BASE_URL ?>assets/img/banner_img_03.jpg" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h1 class="h1">Repr in voluptate</h1>
                                <h3 class="h2">Ullamco laboris nisi ut </h3>
                                <p>
                                    Chúng tôi mang đến cho bạn các mẫu CSS miễn phí 100% cho trang web của bạn.
                                    Nếu bạn muốn hỗ trợ TemplateMo, vui lòng đóng góp một khoản nhỏ qua PayPal hoặc nói với bạn bè về trang web của chúng tôi. Cảm ơn bạn.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
        </a>
        <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
        </a>
    </div>
    <!-- Kết thúc Banner -->


    <!-- Bắt đầu Thương hiệu -->
    <section class="container pb-5" id="categories-of-month">
        <div class="row text-center pt-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Thương hiệu nổi bật</h1>
                <p>
                    Khám phá các bộ sưu tập độc quyền từ những thương hiệu hàng đầu mà chúng tôi hợp tác.
                </p>
            </div>
        </div>
        <div class="row">
            <?php
            // Dữ liệu $brands được truyền từ HomeController
            if (!empty($brands)):
                foreach ($brands as $brand):
                    ?>
                    <div class="col-12 col-md-4 p-5 mt-3">
                        <a href="index.php?page=shop&brand_id=<?= htmlspecialchars($brand->getId()) ?>">
                            <!-- Thêm class 'brand-img' để có hiệu ứng hover và style để đảm bảo logo hiển thị đẹp -->
                            <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($brand->getLogo()) ?>" class="img-fluid brand-img" alt="<?= htmlspecialchars($brand->getName()) ?>" style="height: 150px; object-fit: contain; width: 100%;">
                        </a>
                        <h2 class="h5 text-center mt-3 mb-3"><?= htmlspecialchars($brand->getName()) ?></h2>
                        <p class="text-center"><a href="index.php?page=shop&brand_id=<?= htmlspecialchars($brand->getId()) ?>" class="btn btn-success">Xem sản phẩm</a></p>
                    </div>
                <?php endforeach;
            else: ?>
                <div class="col-12 text-center"><p>Chưa có thương hiệu nào để hiển thị.</p></div>
            <?php endif; ?>
        </div>
    </section>
    <!-- Kết thúc Thương hiệu -->


    <!-- Bắt đầu Sản phẩm nổi bật -->
    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Sản phẩm nổi bật</h1>
                    <p>
                        Đại diện cho sự sung sướng trong việc không có gì đau đớn. Ngoại trừ những người có tội không nhận ra.
                    </p>
                </div>
            </div>
            <div class="row">
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card h-100">
                                <a href="index.php?page=shop-single&slug=<?= htmlspecialchars($product->getSlug()) ?>">
                                    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($product->getImage()) ?>" class="card-img-top" alt="<?= htmlspecialchars($product->getName()) ?>">
                                </a>
                                <div class="card-body">
                                    <ul class="list-unstyled d-flex justify-content-between">
                                        <li>
                                            <?php
                                            $rating = $product->getRatingRounded();
                                            for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa fa-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </li>
                                        <li class="text-muted text-right"><?= $product->formatPrice($product->getDisplayPrice()) ?></li>
                                    </ul>
                                    <a href="index.php?page=shop-single&slug=<?= htmlspecialchars($product->getSlug()) ?>" class="h2 text-decoration-none text-dark"><?= htmlspecialchars($product->getName()) ?></a>
                                    <p class="card-text">
                                        <?php
                                        // Rút gọn mô tả để hiển thị
                                        $description = htmlspecialchars($product->getDescription());
                                        if (mb_strlen($description) > 100) {
                                            echo mb_substr($description, 0, 100) . '...';
                                        } else {
                                            echo $description;
                                        }
                                        ?>
                                    </p>
                                    <p class="text-muted">Đánh giá (<?= $product->getReviewCount() ?>)</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>Chưa có sản phẩm nổi bật nào để hiển thị.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- Kết thúc Sản phẩm nổi bật -->
