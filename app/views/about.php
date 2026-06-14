<!-- Start Content Page -->
<section class="bg-success py-5">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-md-8 text-white">
                <h1>Về Chúng Tôi</h1>
                <p>
                    Basic Shop là một dự án eCommerce được xây dựng để mang lại trải nghiệm mua sắm đơn giản và hiệu quả. Chúng tôi tin rằng công nghệ có thể giúp mọi người tiếp cận sản phẩm chất lượng một cách dễ dàng nhất.
                </p>
            </div>
            <div class="col-md-4">
                <img src="assets/img/about-hero.svg" alt="About Hero">
            </div>
        </div>
    </div>
</section>
<!-- Close Banner -->

<!-- Start Section -->
<section class="container py-5">
    <div class="row text-center pt-5 pb-3">
        <div class="col-lg-6 m-auto">
            <h1 class="h1">Dịch vụ của chúng tôi</h1>
            <p>
                Chúng tôi cung cấp các giải pháp toàn diện từ bán lẻ, quản lý đơn hàng đến chăm sóc khách hàng.
            </p>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center"><i class="fa fa-truck fa-lg"></i></div>
                <h2 class="h5 mt-4 text-center">Giao hàng</h2>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center"><i class="fas fa-exchange-alt"></i></div>
                <h2 class="h5 mt-4 text-center">Đổi & Trả hàng</h2>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center"><i class="fa fa-percent"></i></div>
                <h2 class="h5 mt-4 text-center">Khuyến mãi</h2>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center"><i class="fa fa-user"></i></div>
                <h2 class="h5 mt-4 text-center">Dịch vụ 24/7</h2>
            </div>
        </div>
    </div>
</section>
<!-- End Section -->

<!-- Start Brands -->
<section class="bg-light py-5">
    <div class="container my-4">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Các thương hiệu đối tác</h1>
                <p>
                    Chúng tôi tự hào hợp tác với các thương hiệu hàng đầu trong ngành thời trang.
                </p>
            </div>
            <div class="col-lg-9 m-auto tempaltemo-carousel">
                 <?php if (!empty($brands)): ?>
                    <div class="row d-flex flex-row">
                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="prev">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </div>
                        <!--End Controls-->

                        <!--Carousel Wrapper-->
                        <div class="col">
                            <div class="carousel slide carousel-multi-item pt-2 pt-md-0" id="multi-item-example" data-bs-ride="carousel">
                                <!--Slides-->
                                <div class="carousel-inner product-links-wap" role="listbox">
                                    <?php foreach (array_chunk($brands, 4) as $i => $brandChunk): ?>
                                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                            <div class="row">
                                                <?php foreach ($brandChunk as $brand): ?>
                                                    <div class="col-3 p-md-5">
                                                        <a href="<?= htmlspecialchars($brand->getWebsite() ?? '#') ?>" target="_blank" title="<?= htmlspecialchars($brand->getName()) ?>"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($brand->getLogo() ?? 'no-image.jpg') ?>" alt="<?= htmlspecialchars($brand->getName()) ?>"></a>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <!--End Slides-->
                            </div>
                        </div>
                        <!--End Carousel Wrapper-->

                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <!--End Controls-->
                    </div>
                <?php else: ?>
                    <p class="text-muted">Chưa có thông tin thương hiệu để hiển thị.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!--End Brands-->