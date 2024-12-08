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

    <!-- Liên kết CSS của bạn -->
    <link href="./assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Thanh điều hướng, logo, tìm kiếm, đăng nhập, đăng ký có thể nằm ở đây -->
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
                    <!-- Tìm kiếm -->
                    <form class="d-flex" action="search.php" method="GET">
                        <input class="form-control me-2" type="search" name="query" placeholder="Tìm kiếm sản phẩm"
                            aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Tìm</button>
                    </form>

                    <li class="nav-item">
                        <a class="nav-link" href="home">Sản phẩm</a>
                    </li>

                    <!-- Kiểm tra nếu người dùng đã đăng nhập -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Nếu người dùng có role là customer -->
                        <?php if ($_SESSION['role'] == 'customer'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="cart">Giỏ hàng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="order_history">Lịch sử đơn hàng</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout">Đăng xuất</a>
                        </li>
                    <?php else: ?>
                        <!-- Nếu người dùng chưa đăng nhập -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Đăng nhập</a>
                        </li>
                    <?php endif; ?>
                </ul>


            </div>
        </div>
    </nav>

    <!-- Nội dung chính của trang -->
    <!-- Bạn có thể thêm các phần content khác tại đây -->
</body>

</html>