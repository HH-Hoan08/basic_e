<?php 
if (!$isLoggedIn) {
    header("Location: " . BASE_URL . "index.php?page=login");
    exit();
}
?>
<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Thông tin tài khoản</h2>
                
                <!-- Thông báo lỗi & thành công -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= $success ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['msg'])): ?>
                    <?php if ($_GET['msg'] == 'avatar_success'): ?>
                        <div class="alert alert-success">Cập nhật ảnh đại diện thành công!</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Nav tabs: Chuyển đổi qua lại giữa Hồ sơ và Giỏ hàng -->
        <ul class="nav nav-tabs mb-4 border-success" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-success fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Thông tin cá nhân</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-success fw-bold" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="false">Quản lý Đơn hàng</button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            
            <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <div class="row">
                    <!-- Cột Trái: Đổi Ảnh Đại Diện -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <h5 class="card-title border-bottom pb-3 mb-3">Ảnh đại diện</h5>
                                
                                <?php 
                                    $avatarSrc = (!empty($currentUserInfo['image']) && $currentUserInfo['image'] !== 'default.png')
                                        ? BASE_URL . "assets/img/avatars/" . $currentUserInfo['image']
                                        : BASE_URL . "assets/img/category_img_03.jpg"; // Dùng ảnh tạm nếu chưa có
                                ?>
                                <img src="<?= htmlspecialchars($avatarSrc) ?>" alt="Avatar" class="rounded-circle img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                
                                <p class="text-muted"><?= htmlspecialchars($currentUserInfo['username']) ?></p>
                                
                                <form action="index.php?page=profile" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <input class="form-control form-control-sm" type="file" name="avatar" accept="image/*" required>
                                    </div>
                                    <button type="submit" name="btn_update_avatar" class="btn btn-success w-100">Cập nhật ảnh</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Cột Phải: Form thông tin và mật khẩu -->
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <h5 class="card-title border-bottom pb-3 mb-3">Thông tin cá nhân</h5>
                                <form action="index.php?page=profile" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Tên đầy đủ</label>
                                        <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($currentUserInfo['fullname'] ?? '') ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" id="email_input" name="email" value="<?= htmlspecialchars($currentUserInfo['email'] ?? '') ?>" required>
                                            <button type="button" class="btn btn-outline-success" id="btn_send_code" onclick="showVerificationCode()">Gửi mã</button>
                                        </div>
                                        <small class="text-muted" id="email_hint">Nếu đổi email, bạn cần xác nhận mã gửi về email mới.</small>
                                    </div>
                                    
                                    <div class="mb-3 d-none" id="verification_block">
                                        <label class="form-label text-success">Mã xác nhận <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control border-success" name="verify_code" placeholder="Nhập mã 6 số (Thử nghiệm: 123456)">
                                    </div>

                                    <button type="submit" name="btn_update_info" class="btn btn-success">Lưu thông tin</button>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title border-bottom pb-3 mb-3">Đổi mật khẩu</h5>
                                <form action="index.php?page=profile" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Mật khẩu hiện tại</label>
                                        <input type="password" class="form-control" name="old_password" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mật khẩu mới</label>
                                            <input type="password" class="form-control" name="new_password" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" name="confirm_password" required>
                                        </div>
                                    </div>
                                    <button type="submit" name="btn_update_password" class="btn btn-dark">Đổi mật khẩu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: QUẢN LÝ ĐƠN HÀNG (GIỎ HÀNG) -->
            <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-3 mb-3">Lịch sử mua hàng của bạn</h5>
                        
                        <?php if (empty($userOrders)): ?>
                            <div class="alert alert-info text-center py-4">
                                Bạn chưa có đơn hàng nào. <br>
                                <a href="index.php?page=shop" class="btn btn-success mt-3">Tiếp tục mua sắm</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userOrders as $order): ?>
                                        <tr>
                                            <td><strong>#<?= htmlspecialchars($order['id'] ?? 'N/A') ?></strong></td>
                                            <td><?= htmlspecialchars($order['ordered_at'] ?? 'N/A') ?></td>
                                            <td><strong class="text-danger"><?= number_format($order['total_price'] ?? 0, 0, ',', '.') ?>đ</strong></td>
                                            <td>
                                                <?php 
                                                    $statusText = [
                                                        'pending' => 'Chờ xác nhận',
                                                        'confirmed' => 'Đã xác nhận',
                                                        'shipping' => 'Đang giao',
                                                        'delivered' => 'Thành công',
                                                        'cancelled' => 'Đã hủy'
                                                    ];
                                                    $statusBadges = [
                                                        'pending' => 'bg-warning text-dark',
                                                        'confirmed' => 'bg-info text-dark',
                                                        'shipping' => 'bg-primary',
                                                        'delivered' => 'bg-success',
                                                        'cancelled' => 'bg-danger'
                                                    ];
                                                    $currentStatus = $order['status'] ?? 'pending';
                                                ?>
                                                <span class="badge <?= $statusBadges[$currentStatus] ?? 'bg-secondary' ?>">
                                                    <?= htmlspecialchars($statusText[$currentStatus] ?? ucfirst($currentStatus)) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=profile&action=view_order&order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-success"><i class="fa fa-eye"></i> Xem chi tiết</a>
                                                <?php if ($currentStatus === 'pending'): ?>
                                                    <form action="index.php?page=profile&action=cancel_order" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hủy đơn</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Đoạn script quản lý UI phần Mã xác nhận Email
    const originalEmail = "<?= htmlspecialchars($currentUserInfo['email'] ?? '') ?>";
    const emailInput = document.getElementById('email_input');
    const verifyBlock = document.getElementById('verification_block');
    const btnSendCode = document.getElementById('btn_send_code');
    const emailHint = document.getElementById('email_hint');

    // Lắng nghe sự kiện người dùng gõ vào ô email
    emailInput.addEventListener('input', function() {
        if (this.value.trim() !== originalEmail && this.value.trim() !== "") {
            btnSendCode.disabled = false;
            emailHint.innerHTML = "Nhấn 'Gửi mã' để lấy mã xác nhận trước khi lưu.";
            emailHint.classList.add("text-warning");
        } else {
            btnSendCode.disabled = true;
            verifyBlock.classList.add('d-none');
            emailHint.innerHTML = "Nếu đổi email, bạn cần xác nhận mã gửi về email mới.";
            emailHint.classList.remove("text-warning");
        }
    });

    // Khi mới load, disable nút nếu chưa có gì thay đổi
    if(emailInput.value === originalEmail) {
        btnSendCode.disabled = true;
    }

    // Khi nhấn gửi mã
    function showVerificationCode() {
        if (!emailInput.value) {
            alert("Vui lòng nhập Email!");
            return;
        }
        // Hiển thị ô nhập mã
        verifyBlock.classList.remove('d-none');
        btnSendCode.innerHTML = "Đã gửi!";
        btnSendCode.disabled = true;
        emailHint.innerHTML = "Đã mô phỏng gửi mail. (Nhập mã 123456 để vượt qua logic)";
    }
</script>