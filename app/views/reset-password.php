<?php require_once __DIR__ . '/inc/header.php'; ?>

<div class="container py-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4 text-center">Đặt lại mật khẩu</h2>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>index.php?page=reset-password" method="POST">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                        
                        <div class="mb-3">
                            <label for="token" class="form-label">Mã OTP</label>
                            <input type="text" class="form-control" id="token" name="token" value="<?= htmlspecialchars($token ?? '') ?>" required placeholder="Nhập mã 6 số từ email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Đặt lại mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>