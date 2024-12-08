<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function route($uri) {
    // Kiểm tra người dùng đã đăng nhập chưa
    $isLoggedIn = isset($_SESSION['user_id']);
    $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

    switch ($uri) {
        case '/':
            include 'index.php';
            break;
        case '/product':
            include 'product.php';
            break;
        case '/product_detail':
            include 'product_detail.php';
            break;
        case '/cart':
            // Kiểm tra nếu người dùng chưa đăng nhập
            if (!$isLoggedIn) {
                header('Location: /login');
                exit();
            }
            include 'customer/cart.php';
            break;
        case '/order_history':
            // Kiểm tra nếu người dùng chưa đăng nhập
            if (!$isLoggedIn || $userRole != 'customer') {
                header('Location: /login');
                exit();
            }
            include 'customer/order_history.php';
            break;
        case '/checkout':
            // Kiểm tra nếu người dùng chưa đăng nhập
            if (!$isLoggedIn || $userRole != 'customer') {
                header('Location: /login');
                exit();
            }
            include 'customer/checkout.php';
            break;
        case '/login':
            include 'login.php';
            break;
        case '/register':
            include 'register.php';
            break;
        case '/logout':
            include 'logout.php';
            break;
        case '/staff':
            // Kiểm tra nếu người dùng là staff
            if (!$isLoggedIn || $userRole != 'staff') {
                header('Location: /login');
                exit();
            }
            include 'staff/dashboard.php';
            break;
        default:
            http_response_code(404);
            echo "Page not found!";
            break;
    }
}

// Lấy URI
$uri = strtok($_SERVER['REQUEST_URI'], '?');
route($uri);
?>
