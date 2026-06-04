<!-- modal tìm kiếm -->
<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= BASE_URL ?>index.php?page=shop" method="GET" class="modal-content modal-body border-0 p-0">
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="q" placeholder="Tìm kiếm sản phẩm...">
                <button type="submit" class="input-group-text bg-success text-light">
                    <i class="fa fa-fw fa-search text-white"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- banner -->
<section class="bg-success py-5">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-md-8 text-white">
                <h1>Về chúng tôi</h1>
                <p class="mb-4">
                    Basic Shop ra đời năm 2026 từ niềm đam mê thời trang và mong muốn mang đến
                    những sản phẩm chất lượng cao với mức giá phải chăng cho mọi người Việt Nam.
                </p>
                <div class="d-flex gap-4 flex-wrap">
                    <div>
                        <div class="h3 fw-bold mb-0">50K+</div>
                        <small>Khách hàng</small>
                    </div>
                    <div>
                        <div class="h3 fw-bold mb-0">10K+</div>
                        <small>Sản phẩm</small>
                    </div>
                    <div>
                        <div class="h3 fw-bold mb-0">98%</div>
                        <small>Hài lòng</small>
                    </div>
                    <div>
                        <div class="h3 fw-bold mb-0">34</div>
                        <small>Tỉnh thành</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center mt-4 mt-md-0">
                <img src="<?= BASE_URL ?>assets/img/about-hero.svg" class="img-fluid" alt="Zay Shop">
            </div>
        </div>
    </div>
</section>

<!-- dịch vụ -->
<section class="container py-5">
    <div class="row text-center pt-5 pb-3">
        <div class="col-lg-6 m-auto">
            <h1 class="h1">Dịch vụ của chúng tôi</h1>
            <p>Chúng tôi cam kết mang đến trải nghiệm mua sắm tốt nhất với các dịch vụ tiện ích và chuyên nghiệp.</p>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center">
                    <i class="fa fa-truck fa-lg"></i>
                </div>
                <h2 class="h5 mt-4 text-center">Giao hàng toàn quốc</h2>
                <p class="text-center text-muted small px-3 mt-2">
                    Giao hàng nhanh 24–48 giờ đến 63 tỉnh thành trên cả nước.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h2 class="h5 mt-4 text-center">Đổi trả 30 ngày</h2>
                <p class="text-center text-muted small px-3 mt-2">
                    Đổi trả miễn phí trong vòng 30 ngày, không cần lý do.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center">
                    <i class="fa fa-percent"></i>
                </div>
                <h2 class="h5 mt-4 text-center">Ưu đãi thành viên</h2>
                <p class="text-center text-muted small px-3 mt-2">
                    Hạng thành viên Silver, Gold, Diamond với voucher giảm giá độc quyền.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-success text-center">
                    <i class="fa fa-headset"></i>
                </div>
                <h2 class="h5 mt-4 text-center">Hỗ trợ 24/7</h2>
                <p class="text-center text-muted small px-3 mt-2">
                    Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- đội ngũ -->
<section class="bg-light py-5">
    <div class="container my-4">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Đội ngũ của chúng tôi</h1>
                <p>Những con người tận tâm của Basic Shop.</p>
            </div>
        </div>
        <div class="row justify-content-center g-4">

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                         style="width:68px;height:68px;background:#3d8a52;font-size:22px;">NV</div>
                    <h5 class="fw-normal mb-1">Phạm Hữu Phú</h5>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                         style="width:68px;height:68px;background:#2d6b9e;font-size:22px;">TT</div>
                    <h5 class="fw-normal mb-1">Hà Huy Hoàn</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- thương hiệu đối tác -->
<section class="bg-light py-5">
    <div class="container my-4">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Thương hiệu đối tác</h1>
                <p>Basic Shop hợp tác với các thương hiệu thời trang uy tín trong và ngoài nước.</p>
            </div>
            <div class="col-lg-9 m-auto tempaltemo-carousel">
                <div class="row d-flex flex-row">

                    <div class="col-1 align-self-center">
                        <a class="h1" href="#templatemo-slide-brand" role="button" data-bs-slide="prev">
                            <i class="text-light fas fa-chevron-left"></i>
                        </a>
                    </div>

                    <div class="col">
                        <div class="carousel slide carousel-multi-item pt-2 pt-md-0"
                             id="templatemo-slide-brand" data-bs-ride="carousel">
                            <div class="carousel-inner product-links-wap" role="listbox">

                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_01.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_02.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_03.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_04.png" alt="Brand Logo"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="row">
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_01.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_02.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_03.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_04.png" alt="Brand Logo"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="row">
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_01.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_02.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_03.png" alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img" src="<?= BASE_URL ?>assets/img/brand_04.png" alt="Brand Logo"></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-1 align-self-center">
                        <a class="h1" href="#templatemo-slide-brand" role="button" data-bs-slide="next">
                            <i class="text-light fas fa-chevron-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- cta -->
<section class="bg-success py-5">
    <div class="container text-center text-white py-3">
        <h2 class="fw-normal mb-3">Sẵn sàng trải nghiệm Basic Shop?</h2>
        <p class="mb-4" style="opacity:.85;">
            Đăng ký ngay hôm nay để nhận ưu đãi 20% cho đơn hàng đầu tiên.
        </p>
        <a href="<?= BASE_URL ?>index.php?page=register"
           class="btn btn-light text-success fw-normal px-5 py-2">
            Tạo tài khoản miễn phí
        </a>
    </div>
</section>