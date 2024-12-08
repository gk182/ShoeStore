<?php
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy cart_id từ cơ sở dữ liệu
$sql_cart = "SELECT cart_id FROM cart WHERE user_id = $user_id";
$result_cart = $conn->query($sql_cart);

if ($result_cart->num_rows > 0) {
    $cart_id = $result_cart->fetch_assoc()['cart_id'];
    
    // Lấy product_id từ form
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        
        // Xóa sản phẩm khỏi giỏ hàng
        $sql_remove = "DELETE FROM cart_detail WHERE cart_id = $cart_id AND product_id = $product_id";
        
        if ($conn->query($sql_remove) === TRUE) {
            // Chuyển hướng về giỏ hàng sau khi xóa thành công
            header("Location: cart");
            exit();
        } else {
            echo "Lỗi khi xóa sản phẩm: " . $conn->error;
        }
    } else {
        echo "Không tìm thấy sản phẩm trong giỏ hàng!";
    }
} else {
    echo "Giỏ hàng không tồn tại!";
}
?>
