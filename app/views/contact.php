<?php 
$pageTitle = 'Liên Hệ — Basic Shop'; 

// Xử lý gửi email liên hệ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['contact_error'] = "Vui lòng điền đầy đủ các trường bắt buộc.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_error'] = "Địa chỉ email không hợp lệ.";
    } else {
        require_once __DIR__ . '/../services/MailService.php';
        $mailService = new MailService();
        $adminEmail  = 'basicadmin4@gmail.com'; 

        $emailSubject = "Liên hệ mới: " . htmlspecialchars($subject);
        $emailBody = "
            <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #28a745;'>Có một liên hệ mới từ khách hàng</h2>
                <p><strong>Họ tên:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Chủ đề:</strong> " . htmlspecialchars($subject) . "</p>
                <p><strong>Lời nhắn:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
            </div>
        ";

        if ($mailService->sendHtmlEmail($adminEmail, $emailSubject, $emailBody)) {
            $_SESSION['contact_success'] = "Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.";
            if (!headers_sent()) {
                header("Location: " . BASE_URL . "index.php?page=contact");
            } else {
                echo "<script>window.location.href='" . BASE_URL . "index.php?page=contact';</script>";
            }
            exit();
        } else {
            $_SESSION['contact_error'] = "Đã xảy ra lỗi khi gửi email. Vui lòng thử lại sau.";
        }
    }
}
?>
<?php require_once __DIR__ . '/inc/header.php'; ?>

<!-- banner -->
<div class="container-fluid bg-light py-5">
    <div class="col-md-6 m-auto text-center">
        <h1 class="h1">Liên hệ chúng tôi</h1>
        <p>
            Bạn có câu hỏi hay cần hỗ trợ? Đội ngũ Basic Shop luôn sẵn sàng
            lắng nghe và phản hồi trong thời gian sớm nhất.
        </p>
    </div>
</div>

<!-- map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
      crossorigin=""/>

<div id="mapid" style="width:100%; height:300px;"></div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
<script>
    // khởi tạo bản đồ và đặt view về vị trí của shop
    var mymap = L.map('mapid').setView([10.8234, 106.6831], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(mymap);

    L.marker([10.8234, 106.6831]).addTo(mymap)
        .bindPopup("<b>Basic Shop</b><br>91 Nguyễn Văn Nghi, Gò Vấp, TP.HCM").openPopup();

    mymap.scrollWheelZoom.disable();
    mymap.touchZoom.disable();
</script>

<!-- form liên hệ -->
<div class="container py-5">
    <div class="row py-5">

        <?php if (!empty($_SESSION['contact_success'])): ?>
            <div class="col-md-9 m-auto mb-3">
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i class="fa fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['contact_success']) ?>
                </div>
            </div>
            <?php unset($_SESSION['contact_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['contact_error'])): ?>
            <div class="col-md-9 m-auto mb-3">
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="fa fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_SESSION['contact_error']) ?>
                </div>
            </div>
            <?php unset($_SESSION['contact_error']); ?>
        <?php endif; ?>

        <form class="col-md-9 m-auto" method="POST"
              action="<?= BASE_URL ?>index.php?page=contact"
              novalidate>

            <div class="row">
                <div class="form-group col-md-6 mb-3">
                    <label for="name">Họ và tên <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control mt-1"
                        id="name"
                        name="name"
                        placeholder="Nguyễn Văn A"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                        required>
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input
                        type="email"
                        class="form-control mt-1"
                        id="email"
                        name="email"
                        placeholder="example@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required>
                </div>
            </div>

            <div class="mb-3">
                <label for="subject">Chủ đề <span class="text-danger">*</span></label>
                <select class="form-select mt-1" id="subject" name="subject" required>
                    <option value="" disabled <?= empty($_POST['subject']) ? 'selected' : '' ?>>
                        -- Chọn chủ đề --
                    </option>
                    <option value="order"   <?= ($_POST['subject'] ?? '') === 'order'   ? 'selected' : '' ?>>Đơn hàng</option>
                    <option value="product" <?= ($_POST['subject'] ?? '') === 'product' ? 'selected' : '' ?>>Sản phẩm</option>
                    <option value="return"  <?= ($_POST['subject'] ?? '') === 'return'  ? 'selected' : '' ?>>Đổi / Trả hàng</option>
                    <option value="voucher" <?= ($_POST['subject'] ?? '') === 'voucher' ? 'selected' : '' ?>>Voucher & Khuyến mãi</option>
                    <option value="account" <?= ($_POST['subject'] ?? '') === 'account' ? 'selected' : '' ?>>Tài khoản</option>
                    <option value="other"   <?= ($_POST['subject'] ?? '') === 'other'   ? 'selected' : '' ?>>Khác</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="message">Tin nhắn <span class="text-danger">*</span></label>
                <textarea
                    class="form-control mt-1"
                    id="message"
                    name="message"
                    placeholder="Nhập nội dung bạn muốn gửi..."
                    rows="8"
                    required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>

            <div class="row">
                <div class="col text-end mt-2">
                    <button type="submit" name="submit_contact" class="btn btn-success btn-lg px-3">
                        <i class="fa fa-paper-plane me-2"></i>Gửi đi
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
