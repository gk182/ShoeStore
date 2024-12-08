<?php
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu từ form
$cart_id = $_POST['cart_id'] ?? null;
$product_id = $_POST['product_id'] ?? null;
$size_id = $_POST['size_id'] ?? null;

// Kiểm tra dữ liệu hợp lệ
if (!$cart_id || !$product_id || !$size_id) {
    echo "Dữ liệu không hợp lệ!";
    exit();
}

// Kiểm tra hành động tăng hoặc giảm số lượng
if (isset($_POST['increase'])) {
    // Tăng số lượng
    $sql_update = "UPDATE cart_detail 
                   SET quantity = quantity + 1 
                   WHERE cart_id = ? AND product_id = ? AND size_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("iii", $cart_id, $product_id, $size_id);
} elseif (isset($_POST['decrease'])) {
    // Giảm số lượng
    $sql_check_quantity = "SELECT quantity FROM cart_detail 
                           WHERE cart_id = ? AND product_id = ? AND size_id = ?";
    $stmt_check = $conn->prepare($sql_check_quantity);
    $stmt_check->bind_param("iii", $cart_id, $product_id, $size_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['quantity'] > 1) {
            // Giảm số lượng nếu lớn hơn 1
            $sql_update = "UPDATE cart_detail 
                           SET quantity = quantity - 1 
                           WHERE cart_id = ? AND product_id = ? AND size_id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("iii", $cart_id, $product_id, $size_id);
        } else {
            // Xóa sản phẩm nếu số lượng là 1 và giảm tiếp
            $sql_delete = "DELETE FROM cart_detail 
                           WHERE cart_id = ? AND product_id = ? AND size_id = ?";
            $stmt = $conn->prepare($sql_delete);
            $stmt->bind_param("iii", $cart_id, $product_id, $size_id);
        }
    } else {
        echo "Không tìm thấy sản phẩm trong giỏ hàng!";
        exit();
    }
} else {
    echo "Không xác định được hành động!";
    exit();
}

// Thực thi truy vấn
if ($stmt->execute()) {
    // Quay lại trang giỏ hàng sau khi cập nhật
    header("Location: cart");
    exit();
} else {
    echo "Lỗi: " . $conn->error;
    exit();
}

$stmt->close();
$conn->close();
?>
