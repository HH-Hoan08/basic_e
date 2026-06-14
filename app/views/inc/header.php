<!DOCTYPE html>
<html lang="vi">
<head>
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Basic Shop'; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?php echo BASE_URL; ?>">

    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <link rel="stylesheet" href="assets/css/slick-theme.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">

    <?php if (isset($page) && $page === 'about'): ?>
        <link rel="stylesheet" href="assets/css/about.css">
    <?php endif; ?>
    <?php if (isset($page) && $page === 'contact'): ?>
        <link rel="stylesheet" href="assets/css/contact.css">
    <?php endif; ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
</head>

<body>
    <!-- Bắt đầu Thanh điều hướng trên cùng -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:info@company.com">info@congty.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="tel:010-020-0340">010-020-0340</a>
                </div>
                <div>
                    <a class="text-light" href="https://fb.com/templatemo" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://twitter.com/" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin fa-sm fa-fw"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Đóng Thanh điều hướng trên cùng -->

<!-- Bắt đầu Tiêu đề -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="index.php?page=home">
                Basic
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=home">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=about">Về chúng tôi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=shop">Cửa hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=contact">Liên hệ</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <!-- Form tìm kiếm cho di động -->
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <form action="<?= BASE_URL ?>index.php" method="get">
                            <div class="input-group">
                                <input type="hidden" name="page" value="shop">
                                <input type="text" class="form-control" name="q" placeholder="Tìm kiếm...">
                                <button class="input-group-text" type="submit"><i class="fa fa-fw fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <!-- Form tìm kiếm cho desktop -->
                    <form action="<?= BASE_URL ?>index.php" method="get" class="d-none d-lg-flex align-items-center me-2">
                        <input type="hidden" name="page" value="shop">
                        <input class="form-control form-control-sm" type="search" name="q" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" aria-label="Search">
                    </form>

                    <!-- Mini Cart Dropdown -->
                    <div class="dropdown d-inline-block">
                        <a class="nav-icon position-relative text-decoration-none dropdown-toggle" href="#" id="cartDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                            <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark" style="transform: translate(-50%, -50%) !important;">
                                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3 shadow" aria-labelledby="cartDropdown" style="width: 320px;">
                            <li><h6 class="dropdown-header text-dark px-0 py-1">Giỏ hàng của bạn</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
                                <li><div class="text-center text-muted my-3">Giỏ hàng trống</div></li>
                            <?php else: ?>
                                <?php 
                                    $totalPrice = 0;
                                    foreach(array_slice($_SESSION['cart'], 0, 3) as $item): 
                                        $totalPrice += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                                ?>
                                    <li>
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="assets/img/<?php echo htmlspecialchars($item['image'] ?? 'shop_01.jpg'); ?>" alt="..." class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div class="flex-grow-1" style="overflow: hidden;">
                                                <h6 class="mb-0 text-truncate" style="font-size: 0.9rem;" title="<?php echo htmlspecialchars($item['name'] ?? 'Sản phẩm'); ?>"><?php echo htmlspecialchars($item['name'] ?? 'Sản phẩm'); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($item['quantity'] ?? '1'); ?> x <?php echo number_format($item['price'] ?? 0, 0, ',', '.'); ?>đ</small>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                <?php if(count($_SESSION['cart']) > 3): ?>
                                    <li><div class="text-center text-muted small my-1">... và <?php echo count($_SESSION['cart']) - 3; ?> sản phẩm khác</div></li>
                                <?php endif; ?>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="btn btn-success w-100 btn-sm" href="index.php?page=cart">Xem Chi Tiết Giỏ Hàng</a></li>
                        </ul>
                    </div>

                    <div class="dropdown d-inline-block">
                        <a class="nav-icon position-relative text-decoration-none dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if ($isLoggedIn && isset($currentUserInfo) && !empty($currentUserInfo['image']) && $currentUserInfo['image'] !== 'default.png'): ?>
                                <!-- Hiển thị ảnh đại diện nếu có -->
                                <img src="assets/img/avatars/<?= htmlspecialchars($currentUserInfo['image']) ?>" alt="Avatar" class="rounded-circle mr-2" style="width: 30px; height: 30px; object-fit: cover; border: 1px solid #59ab6e;">
                                <span class="text-dark"><strong><?= htmlspecialchars($currentUserInfo['username']) ?></strong></span>
                            <?php else: ?>
                                <!-- Icon mặc định nếu chưa đăng nhập hoặc chưa có ảnh -->
                                <i class="fa fa-fw fa-user text-dark mr-1"></i>
                                <?php if ($isLoggedIn): ?><span class="text-dark"><strong><?= htmlspecialchars($_SESSION['user']) ?></strong></span><?php endif; ?>
                            <?php endif; ?>
                            
                             <!-- hiện thông báo mới của người dùng (cập nhật sau) -->
                            <!-- <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">+99</span> -->
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            
                            <?php if ($isLoggedIn): ?>
                                <?php if (isset($currentUserInfo['role']) && $currentUserInfo['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item text-success fw-bold" href="index.php?page=admin"><i class="fas fa-user-shield me-1"></i> Quản trị Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="index.php?page=profile">Thông tin tài khoản</a></li>
                                <li><a class="dropdown-item" href="index.php?page=orders">Đơn hàng của tôi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?page=logout">Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="index.php?page=login">Đăng nhập</a></li>
                                <li><a class="dropdown-item" href="index.php?page=register">Đăng ký</a></li>
                            <?php endif; ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Đóng Tiêu đề -->
