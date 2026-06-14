<?php require_once __DIR__ . '/inc/header.php'; ?>

<div class="container py-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="h4 mb-1 text-center">Quên mật khẩu</h2>
                    <p class="text-muted text-center mb-4">Nhập email của bạn để nhận hướng dẫn.</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>index.php?page=forgot-password" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Địa chỉ email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Gửi hướng dẫn</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>index.php?page=login" class="text-decoration-none">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>