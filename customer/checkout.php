<?php
include '../includes/db.php';
include '../includes/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'];

// Kiểm tra nếu giỏ hàng trống
$sql = "SELECT cd.cart_detail_id, p.product_id, p.product_name, p.price, cd.quantity, s.size, s.size_id
        FROM cart_detail cd
        JOIN product p ON cd.product_id = p.product_id
        JOIN size s ON cd.size_id = s.size_id
        WHERE cd.cart_id IN (SELECT cart_id FROM cart WHERE user_id = $user_id)";
$cart_result = $conn->query($sql);

if ($cart_result->num_rows == 0) {
    echo "<p>Giỏ hàng trống, không thể thanh toán.</p>";
    exit;
}

// Kiểm tra nếu form thanh toán được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $fullname = $_POST['fullname'];

    // Tính tổng giá trị đơn hàng
    $total_price = 0;
    while ($cart_row = $cart_result->fetch_assoc()) {
        $total_price += $cart_row['quantity'] * $cart_row['price'];
    }

    // Tạo đơn hàng
    $conn->begin_transaction();
    try {
        // Thêm đơn hàng vào bảng `order`
        $sql_order = "INSERT INTO `order` (user_id, total_price, address, phone, status, fullname) 
                      VALUES ($user_id, $total_price, '$address', '$phone', 'Chưa xác thực', '$fullname')";
        $conn->query($sql_order);
        $order_id = $conn->insert_id; // Lấy ID đơn hàng mới

        // Thêm chi tiết đơn hàng vào bảng `order_detail`
        $cart_result->data_seek(0); // Reset con trỏ kết quả
        while ($cart_row = $cart_result->fetch_assoc()) {
            $product_id = $cart_row['product_id'];
            $size_id = $cart_row['size_id'];
            $quantity = $cart_row['quantity'];
            $price = $cart_row['price'];

            // Thêm vào bảng `order_detail`
            $sql_order_detail = "INSERT INTO `order_detail` (order_id, product_id, size_id, quantity, price) 
                                 VALUES ($order_id, $product_id, $size_id, $quantity, $price)";
            $conn->query($sql_order_detail);
        }

        // Xóa sản phẩm khỏi giỏ hàng
        $conn->query("DELETE FROM cart_detail WHERE cart_id IN (SELECT cart_id FROM cart WHERE user_id = $user_id)");
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");

        $conn->commit(); // Commit transaction

        // Chuyển hướng người dùng đến trang `order_success.php`
        header("Location: order_success?status=success");
        exit(); // Dừng việc thực thi thêm mã
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction nếu có lỗi
        echo "<p>Đặt hàng thất bại. Vui lòng thử lại sau.</p>";
    }
}
?>

<div class="container mt-4">
    <h1>Thanh toán</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="fullname" class="form-label">Họ và tên</label>
            <input type="text" name="fullname" id="fullname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Xác nhận</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>