<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Trị - Basic Shop</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            background-color: #212934;
            color: white;
        }
        .sidebar a {
            color: #cfd6e1;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #59ab6e;
            color: white;
        }
        .content-wrapper { padding: 20px; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Navigation -->
        <div class="sidebar flex-column" style="width: 250px;">
            <h4 class="text-center py-4 border-bottom border-secondary mb-0">
                <i class="fas fa-user-shield me-2"></i> Bảng điều khiển ADMIN
            </h4>
            <nav class="nav flex-column mt-3">
                <a href="<?= BASE_URL ?>index.php?page=admin&action=dashboard" class="<?= ($_GET['action'] ?? 'dashboard') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt me-2"></i> Tổng quan (Dashboard)
                </a>
                <a href="<?= BASE_URL ?>index.php?page=admin&action=orders" class="<?= ($_GET['action'] ?? '') === 'orders' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart me-2"></i> Quản lý Đơn hàng
                </a>
                <a href="<?= BASE_URL ?>index.php?page=admin&action=products" class="<?= ($_GET['action'] ?? '') === 'products' ? 'active' : '' ?>">
                    <i class="fas fa-box me-2"></i> Quản lý Sản phẩm
                </a>
                <a href="<?= BASE_URL ?>index.php?page=admin&action=customers" class="<?= ($_GET['action'] ?? '') === 'customers' ? 'active' : '' ?>">
                    <i class="fas fa-users me-2"></i> Quản lý Khách hàng
                </a>
                <a href="<?= BASE_URL ?>index.php?page=admin&action=vouchers" class="<?= in_array(($_GET['action'] ?? ''), ['vouchers', 'add_voucher', 'edit_voucher']) ? 'active' : '' ?>">
                    <i class="fas fa-ticket-alt me-2"></i> Quản lý Voucher
                </a>
                <a href="<?= BASE_URL ?>index.php?page=admin&action=reviews" class="<?= in_array(($_GET['action'] ?? ''), ['reviews']) ? 'active' : '' ?>">
                    <i class="fas fa-comments me-2"></i> Quản lý Đánh giá
                </a>
                <a href="<?= BASE_URL ?>index.php?page=home" class="mt-5 text-warning">
                    <i class="fas fa-sign-out-alt me-2"></i> Trở về Trang chủ
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content-wrapper flex-grow-1">
            <?php 
                // Render View con được truyền từ Controller vào đây
                if (isset($viewPath) && file_exists($viewPath)) {
                    include $viewPath;
                }
            ?>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>