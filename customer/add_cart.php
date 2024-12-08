<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $size_id = isset($_POST['size']) ? intval($_POST['size']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // ID người dùng đăng nhập

    // Kiểm tra dữ liệu hợp lệ
    if ($product_id <= 0 || $size_id <= 0 || $quantity <= 0 || $user_id <= 0) {
        header('Location: error.php?msg=Invalid data');
        exit();
    }

    // Kiểm tra nếu người dùng đã có giỏ hàng
    $cart_id = null;
    $cart_sql = "SELECT cart_id FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($cart_sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();
    if ($cart_result->num_rows > 0) {
        $cart = $cart_result->fetch_assoc();
        $cart_id = $cart['cart_id'];
    } else {
        // Tạo giỏ hàng mới nếu chưa tồn tại
        $insert_cart_sql = "INSERT INTO cart (user_id) VALUES (?)";
        $stmt = $conn->prepare($insert_cart_sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $cart_id = $stmt->insert_id;
    }
    $stmt->close();

    // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
    $cart_detail_sql = "SELECT cart_detail_id, quantity FROM cart_detail WHERE cart_id = ? AND product_id = ? AND size_id = ?";
    $stmt = $conn->prepare($cart_detail_sql);
    $stmt->bind_param('iii', $cart_id, $product_id, $size_id);
    $stmt->execute();
    $cart_detail_result = $stmt->get_result();
    if ($cart_detail_result->num_rows > 0) {
        // Nếu sản phẩm đã tồn tại, cập nhật số lượng
        $cart_detail = $cart_detail_result->fetch_assoc();
        $new_quantity = $cart_detail['quantity'] + $quantity;
        $update_cart_detail_sql = "UPDATE cart_detail SET quantity = ? WHERE cart_detail_id = ?";
        $stmt = $conn->prepare($update_cart_detail_sql);
        $stmt->bind_param('ii', $new_quantity, $cart_detail['cart_detail_id']);
        $stmt->execute();
    } else {
        // Nếu chưa tồn tại, thêm mới sản phẩm vào giỏ hàng
        $insert_cart_detail_sql = "INSERT INTO cart_detail (cart_id, product_id, size_id, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_cart_detail_sql);
        $stmt->bind_param('iiii', $cart_id, $product_id, $size_id, $quantity);
        $stmt->execute();
    }
    $stmt->close();

    // Chuyển hướng người dùng đến trang giỏ hàng
    header('Location: cart');
    exit();
}
?>
