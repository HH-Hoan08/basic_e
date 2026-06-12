<h2 class="mb-4">Tổng quan Thống kê</h2>

<!-- Khối Thống kê Tổng quan -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-text mb-0"><?= $summary['total_orders'] ?? 0 ?></h3>
                        <p class="card-title mb-0">Tổng Đơn Hàng</p>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card text-white bg-warning text-dark shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-text mb-0"><?= $summary['pending_orders'] ?? 0 ?></h3>
                        <p class="card-title mb-0">Đơn Chờ Xử Lý</p>
                    </div>
                    <i class="fas fa-hourglass-half fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-text mb-0"><?= number_format($summary['total_stock'] ?? 0) ?></h3>
                        <p class="card-title mb-0">Sản Phẩm Tồn Kho</p>
                    </div>
                    <i class="fas fa-warehouse fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Khối Thống kê Doanh Thu -->
<h4 class="mb-3 mt-5">Thống kê Doanh thu (Đơn hàng đã hoàn thành)</h4>
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Doanh thu Tuần này</h5>
                <h3 class="card-text"><?= number_format($revenue['revenue_week'] ?? 0, 0, ',', '.') ?> đ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Doanh thu Tháng này</h5>
                <h3 class="card-text"><?= number_format($revenue['revenue_month'] ?? 0, 0, ',', '.') ?> đ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Doanh thu Năm nay</h5>
                <h3 class="card-text"><?= number_format($revenue['revenue_year'] ?? 0, 0, ',', '.') ?> đ</h3>
            </div>
        </div>
    </div>
</div>

<!-- Khối Sản Phẩm Bán Chạy và Bán Ế -->
<div class="row">
    <!-- Sản phẩm bán chạy -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Top 5 Sản phẩm Bán Chạy Nhất</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Sản phẩm</th><th>Đã bán</th><th>Tồn kho</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($bestSellers as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td class="text-success fw-bold"><?= $item['sold_count'] ?></td>
                            <td><?= $item['stock'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sản phẩm bán ế -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Top 5 Sản phẩm Tồn / Bán Ế Nhất</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Sản phẩm</th><th>Đã bán</th><th>Tồn kho</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($worstSellers as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td class="text-danger fw-bold"><?= $item['sold_count'] ?></td>
                            <td class="text-warning fw-bold"><?= $item['stock'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>