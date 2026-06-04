<?php
    // thêm tham số truy vấn khi chuyển hướng của trang index.php để giữ nguyên các tham số như ?page=shop
    $query = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: app/controller/index.php' . $query);
?>