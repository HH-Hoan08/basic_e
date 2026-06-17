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
                <button class="nav-link active text-success fw-bold" id="info-tab"
                        data-bs-toggle="tab" data-bs-target="#info"
                        type="button" role="tab" aria-selected="true">
                    <i class="fa fa-user me-1"></i> Thông tin cá nhân
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-success fw-bold" id="orders-tab"
                        data-bs-toggle="tab" data-bs-target="#orders"
                        type="button" role="tab" aria-selected="false">
                    <i class="fa fa-box me-1"></i> Đơn hàng của tôi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-success fw-bold" id="reviews-tab"
                        data-bs-toggle="tab" data-bs-target="#reviews"
                        type="button" role="tab" aria-selected="false">
                    <i class="fa fa-star me-1"></i> Đánh giá của tôi
                    <?php if (!empty($userReviews)): ?>
                        <span class="badge bg-success ms-1"><?= count($userReviews) ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-success fw-bold" id="emails-tab"
                        data-bs-toggle="tab" data-bs-target="#emails"
                        type="button" role="tab" aria-selected="false">
                    <i class="fa fa-envelope me-1"></i> Email từ Admin
                    <?php if (!empty($userEmails)): ?>
                        <span class="badge bg-danger ms-1" id="email-badge"><?= count($userEmails) ?></span>
                    <?php endif; ?>
                </button>
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

                                    <div class="mb-3">
                                        <label class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($currentUserInfo['address'] ?? '') ?>" placeholder="Nhập địa chỉ liên hệ của bạn...">
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
                                                <?php elseif ($currentStatus === 'shipping' || $currentStatus === 'confirmed'): ?>
                                                    <form action="index.php?page=profile&action=receive_order" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Bạn xác nhận đã nhận được hàng?');">
                                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Đã nhận hàng</button>
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
            <!-- TAB 3: ĐÁNH GIÁ CỦA TÔI -->
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <h5 class="mb-4">Đánh giá của tôi</h5>

                <?php if (empty($userReviews)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-star fa-3x mb-3 d-block"></i>
                        <p>Bạn chưa có đánh giá nào.</p>
                        <a href="<?= BASE_URL ?>index.php?page=shop" class="btn btn-success">
                            Mua sắm ngay
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($userReviews as $rv): ?>
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">

                                            <!-- Ảnh sản phẩm -->
                                            <div class="col-auto">
                                                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($rv['product_image'] ?? 'no-image.jpg') ?>"
                                                     style="width:70px;height:70px;object-fit:cover;border-radius:8px;"
                                                     alt="<?= htmlspecialchars($rv['product_name']) ?>"
                                                     onerror="this.src='<?= BASE_URL ?>assets/img/no-image.jpg'">
                                            </div>

                                            <!-- Thông tin đánh giá -->
                                            <div class="col">
                                                <a href="<?= BASE_URL ?>index.php?page=shop-single&slug=<?= htmlspecialchars($rv['product_slug']) ?>"
                                                   class="fw-bold text-dark text-decoration-none">
                                                    <?= htmlspecialchars($rv['product_name']) ?>
                                                </a>

                                                <!-- Sao đánh giá -->
                                                <div class="my-1">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fa fa-star <?= $i <= (int)$rv['rating'] ? 'text-warning' : 'text-muted' ?>"
                                                           style="font-size:13px;"></i>
                                                    <?php endfor; ?>
                                                    <small class="text-muted ms-1"><?= (int)$rv['rating'] ?>/5</small>
                                                </div>

                                                <!-- Nội dung bình luận -->
                                                <p class="mb-1 text-muted small">
                                                    <?= !empty($rv['comment'])
                                                        ? htmlspecialchars($rv['comment'])
                                                        : '<em>Không có nhận xét</em>' ?>
                                                </p>

                                                <small class="text-muted">
                                                    <i class="fa fa-clock me-1"></i>
                                                    <?= date('d/m/Y H:i', strtotime($rv['created_at'])) ?>
                                                </small>
                                            </div>

                                            <!-- Trạng thái hiển thị -->
                                            <div class="col-auto text-end">
                                                <?php if ($rv['is_visible']): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-eye me-1"></i> Đang hiển thị
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fa fa-eye-slash me-1"></i> Đã ẩn
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <!-- TAB 4: EMAILS TỪ ADMIN -->
            <div class="tab-pane fade" id="emails" role="tabpanel" aria-labelledby="emails-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Email từ Admin</h5>
                    <?php if (!empty($userEmails)): ?>
                        <small class="text-muted"><?= count($userEmails) ?> email</small>
                    <?php endif; ?>
                </div>

                <?php if (empty($userEmails)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-envelope-open fa-3x mb-3 d-block"></i>
                        <p>Chưa có email nào từ Admin.</p>
                    </div>
                <?php else: ?>
                    <div class="accordion" id="emailAccordion">
                        <?php foreach ($userEmails as $idx => $em): ?>
                            <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden"
                                 id="email-item-<?= $em['id'] ?>">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $idx > 0 ? 'collapsed' : '' ?> fw-normal"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#email-body-<?= $em['id'] ?>"
                                            onclick="markAsRead(<?= $em['id'] ?>)">

                                        <div class="d-flex align-items-center gap-3 w-100">
                                            <!-- Dot chưa đọc -->
                                            <span class="unread-dot rounded-circle bg-primary flex-shrink-0"
                                                  id="dot-<?= $em['id'] ?>"
                                                  style="width:8px;height:8px;display:inline-block;"></span>

                                            <!-- Chủ đề -->
                                            <span class="fw-bold flex-grow-1" id="subject-<?= $em['id'] ?>">
                                                <?= htmlspecialchars($em['subject']) ?>
                                            </span>

                                            <!-- Tag loại người nhận -->
                                            <span class="badge <?= $em['recipient_type'] === 'all' ? 'bg-primary' : 'bg-warning text-dark' ?> me-2 flex-shrink-0">
                                                <?php
                                                $typeLabel = match($em['recipient_type']) {
                                                    'all'     => 'Tất cả',
                                                    'gold'    => 'Gold',
                                                    'diamond' => 'Diamond',
                                                    'silver'  => 'Silver',
                                                    default   => $em['recipient_type'],
                                                };
                                                echo $typeLabel;
                                                ?>
                                            </span>

                                            <!-- Ngày gửi -->
                                            <small class="text-muted flex-shrink-0">
                                                <i class="fa fa-clock me-1"></i>
                                                    <?= date('d/m/Y H:i', strtotime($em['sent_at'])) ?>
                                            </small>
                                        </div>
                                    </button>
                                </h2>

                                <div id="email-body-<?= $em['id'] ?>"
                                    class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>"
                                    data-bs-parent="#emailAccordion">
                                    <div class="accordion-body bg-white">

                                        <!-- Header email -->
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                            <div>
                                                <small class="text-muted d-block">
                                                    <i class="fa fa-user-shield me-1 text-success"></i>
                                                    <strong>Từ:</strong> <?= htmlspecialchars($em['admin_name'] ?? 'Admin') ?>
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="fa fa-users me-1 text-success"></i>
                                                    <strong>Gửi đến:</strong>
                                                    <?= $typeLabel ?>
                                                </small>
                                            </div>
                                            <small class="text-muted">
                                                <?= date('H:i — d/m/Y', strtotime($em['sent_at'])) ?>
                                            </small>
                                        </div>

                                        <!-- Nội dung email -->
                                        <div class="email-body" style="line-height:1.75;white-space:pre-wrap;">
                                            <?= nl2br(htmlspecialchars($em['body'])) ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Script xử lý email đã đọc (lưu localStorage) -->
            <script>
            // Danh sách ID email đã đọc (lưu local)
            const READ_KEY = 'zay_read_emails';

            function getReadEmails() {
                return JSON.parse(localStorage.getItem(READ_KEY) || '[]');
            }

            function markAsRead(id) {
                const read = getReadEmails();
                if (!read.includes(id)) {
                    read.push(id);
                    localStorage.setItem(READ_KEY, JSON.stringify(read));
                }
                // Ẩn dot chưa đọc
                const dot = document.getElementById('dot-' + id);
                if (dot) dot.style.display = 'none';
                // Bỏ bold chủ đề
                const subj = document.getElementById('subject-' + id);
                if (subj) subj.classList.remove('fw-bold');
                // Cập nhật badge
                updateBadge();
            }

            function updateBadge() {
                const read  = getReadEmails();
                const dots  = document.querySelectorAll('.unread-dot');
                let unread  = 0;
                dots.forEach(d => { if (d.style.display !== 'none') unread++; });
                const badge = document.getElementById('email-badge');
                if (badge) {
                    if (unread > 0) {
                        badge.textContent = unread;
                        badge.style.display = '';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }

            // Khi load trang: áp dụng trạng thái đã đọc từ localStorage
            document.addEventListener('DOMContentLoaded', function () {
                const read = getReadEmails();
                read.forEach(id => {
                    const dot  = document.getElementById('dot-'     + id);
                    const subj = document.getElementById('subject-' + id);
                    if (dot)  dot.style.display  = 'none';
                    if (subj) subj.classList.remove('fw-bold');
                });
                // Email đầu tiên auto mở → đánh dấu đã đọc
                <?php if (!empty($userEmails)): ?>
                markAsRead(<?= (int)$userEmails[0]['id'] ?>);
                <?php endif; ?>
                updateBadge();
            });
            </script>
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

    // Tự động mở tab dựa trên URL hash (ví dụ: #orders)
    document.addEventListener("DOMContentLoaded", function() {
        let hash = window.location.hash;
        if (hash) {
            let targetTab = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (targetTab) {
                let tab = new bootstrap.Tab(targetTab);
                tab.show();
            }
        }
    });
</script>