<?php
include '../includes/db.php';
include '../includes/header.php';

// Kiểm tra nếu `order_id` được truyền vào
if (!isset($_GET['order_id'])) {
    echo "<p>Đơn hàng không hợp lệ.</p>";
    exit;
}

$order_id = intval($_GET['order_id']);

// Lấy thông tin đơn hàng
$sql_order = "
    SELECT o.*, o.fullname, o.phone, o.address
    FROM `order` o
    WHERE o.order_id = $order_id
";
$order_result = $conn->query($sql_order);

// Kiểm tra đơn hàng tồn tại
if ($order_result->num_rows == 0) {
    echo "<p>Không tìm thấy đơn hàng.</p>";
    exit;
}

$order = $order_result->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_order_detail = "
    SELECT od.*, p.product_name, p.price AS product_price, p.image_url, s.size
    FROM `order_detail` od
    JOIN `product` p ON od.product_id = p.product_id
    JOIN `size` s ON od.size_id = s.size_id
    WHERE od.order_id = $order_id
";
$order_detail_result = $conn->query($sql_order_detail);
?>

<div class="container mt-4">
    <h1>Chi tiết đơn hàng #<?= $order['order_id']; ?></h1>

    <div class="mb-3">
        <strong>Họ và tên:</strong> <?= $order['fullname']; ?><br>
        <strong>Số điện thoại:</strong> <?= $order['phone']; ?><br>
        <strong>Địa chỉ:</strong> <?= $order['address']; ?><br>
        <strong>Ngày đặt:</strong> <?= $order['order_date']; ?><br>
        <strong>Trạng thái:</strong> <?= ucfirst($order['status']); ?><br>
        <strong>Tổng tiền:</strong> <?= number_format($order['total_price'], 0, ',', '.'); ?> VNĐ
    </div>

    <h2>Danh sách sản phẩm</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Ảnh sản phẩm</th>
                <th>Sản phẩm</th>
                <th>Size</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($detail = $order_detail_result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <!-- Hiển thị ảnh sản phẩm -->
                        <img src="./assets/images/<?= $detail['image_url']; ?>" alt="<?= $detail['product_name']; ?>" style="width: 100px; height: auto;">
                    </td>
                    <td><?= $detail['product_name']; ?></td>
                    <td><?= $detail['size']; ?></td>
                    <td><?= $detail['quantity']; ?></td>
                    <td><?= number_format($detail['product_price'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?= number_format($detail['quantity'] * $detail['product_price'], 0, ',', '.'); ?> VNĐ</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
