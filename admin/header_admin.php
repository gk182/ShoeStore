<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Bán Giày</title>

    <!-- Liên kết Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Liên kết Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Liên kết CSS của bạn -->
    <link href="./assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Thanh điều hướng -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Shoestore</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="home">Trang chủ</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="inventory">Quản lí tồn kho</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="list_order">Quản lí đơn hàng</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="manager_product">Quản lí sản phẩm</a>
                    </li>

                </ul>

                <!-- Tìm kiếm với Icon -->
                <form class="d-flex ms-auto" action="search" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="query" placeholder="Tìm kiếm sản phẩm"
                            aria-label="Search">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Kiểm tra nếu người dùng đã đăng nhập -->
                <ul class="navbar-nav ms-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Icon giỏ hàng -->
                        <?php if ($_SESSION['role'] == 'customer'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Dropdown menu cho lịch sử đơn hàng và đăng xuất -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <?php if ($_SESSION['role'] == 'customer'): ?>
                                    <li><a class="dropdown-item" href="order_history">Lịch sử đơn hàng</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="logout">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Hiển thị icon đăng nhập nếu chưa đăng nhập -->
                        <li class="nav-item">
                            <a class="nav-link" href="login">
                                <i class="fas fa-user"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>



