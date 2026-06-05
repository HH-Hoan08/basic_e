<?php
$action = isset($_GET['page']) && $_GET['page'] == 'register' ? 'register' : 'login';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="assets/css/auth.css">

<div class="auth-wrap">
    <div class="auth-card">

        <!-- Left visual panel -->
        <div class="auth-visual">
            <div id="authCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
                <div class="carousel-inner h-100">
                    <div class="carousel-item active h-100">
                        <img src="assets/img/feature_prod_01.jpg" alt="Product 1">
                    </div>
                    <div class="carousel-item h-100">
                        <img src="assets/img/feature_prod_02.jpg" alt="Product 2">
                    </div>
                    <div class="carousel-item h-100">
                        <img src="assets/img/feature_prod_03.jpg" alt="Product 3">
                    </div>
                </div>
            </div>

            <div class="visual-content">
                <div class="visual-badge">
                    <span></span> Basic Shop
                </div>
                <div class="visual-headline">Mua sắm<br>thật dễ dàng.</div>
                <p class="visual-sub">Hàng nghìn sản phẩm chất lượng, giao hàng nhanh chóng đến tay bạn.</p>
                <div class="visual-dots">
                    <span class="active" id="dot-0"></span>
                    <span id="dot-1"></span>
                    <span id="dot-2"></span>
                </div>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="auth-form-panel">
            <div class="auth-inner">

                <!-- Login form -->
                <div id="login-form" class="<?= $action == 'login' ? '' : 'd-none' ?>">
                    <div class="form-head">
                        <div class="brand-logo">
                            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="32" height="32" rx="8" fill="#15803d"/>
                                <path d="M8 22l4-8 4 5 3-4 5 7H8z" fill="white" opacity=".9"/>
                                <circle cx="22" cy="11" r="3" fill="#22c55e"/>
                            </svg>
                            Basic Shop
                        </div>
                        <h2 class="form-title">Chào mừng trở lại!</h2>
                        <p class="form-subtitle">Đăng nhập để tiếp tục mua sắm</p>
                    </div>

                    <?php if (!empty($error) && $action == 'login'): ?>
                        <div class="alert alert-danger py-2 px-3 text-center" style="font-size: 14px;"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if (!empty($success) && $action == 'login'): ?>
                        <div class="alert alert-success py-2 px-3 text-center" style="font-size: 14px;"><?= $success ?></div>
                    <?php endif; ?>

                    <form action="app/controller/index.php?page=login" method="post">
                        <div class="field-group">
                            <label>Tên đăng nhập hoặc Email</label>
                            <div class="field-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <input type="text" name="username" placeholder="Nhập tên đăng nhập..." required>
                            </div>
                        </div>
                        <div class="field-group">
                            <label>Mật khẩu</label>
                            <div class="field-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
                            </div>
                        </div>
                        <button type="submit" class="btn-auth">Đăng nhập →</button>
                    </form>

                    <div class="auth-divider">hoặc</div>
                    <div class="auth-switch">
                        Chưa có tài khoản?
                        <a href="#" onclick="toggleForm('register', event)">Đăng ký ngay</a>
                    </div>
                </div>

                <!-- Register form -->
                <div id="register-form" class="<?= $action == 'register' ? '' : 'd-none' ?>">
                    <div class="form-head">
                        <div class="brand-logo">
                            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="32" height="32" rx="8" fill="#15803d"/>
                                <path d="M8 22l4-8 4 5 3-4 5 7H8z" fill="white" opacity=".9"/>
                                <circle cx="22" cy="11" r="3" fill="#22c55e"/>
                            </svg>
                            Basic Shop
                        </div>
                        <h2 class="form-title">Tạo tài khoản mới</h2>
                        <p class="form-subtitle">Tham gia hàng ngàn khách hàng hài lòng</p>
                    </div>

                    <?php if (!empty($error) && $action == 'register'): ?>
                        <div class="alert alert-danger py-2 px-3 text-center" style="font-size: 14px;"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="app/controller/index.php?page=register" method="post">
                        <div class="field-group">
                            <label>Tên đầy đủ</label>
                            <div class="field-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <input type="text" name="fullname" placeholder="Nhập họ tên của bạn..." required>
                            </div>
                        </div>
                        <div class="field-group">
                            <label>Email</label>
                            <div class="field-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <input type="email" name="email" placeholder="Nhập địa chỉ email..." required>
                            </div>
                        </div>
                        <div class="field-group">
                            <label>Tên đăng nhập</label>
                            <div class="field-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <input type="text" name="username" placeholder="Tạo tên đăng nhập..." required>
                            </div>
                        </div>
                        <div class="field-group">
                            <label>Mật khẩu</label>
                            <div class="field-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <input type="password" name="password" placeholder="Tạo mật khẩu..." required>
                            </div>
                        </div>
                        <button type="submit" class="btn-auth">Tạo tài khoản →</button>
                    </form>

                    <div class="auth-divider">hoặc</div>
                    <div class="auth-switch">
                        Đã có tài khoản?
                        <a href="#" onclick="toggleForm('login', event)">Đăng nhập</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?php include '../controller/auth_controller.php'; ?>