<?php
include '../includes/db.php';
include '../includes/header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'];

// Lấy lịch sử đơn hàng
$sql = "SELECT * FROM `order` WHERE user_id = $user_id";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <h1>Lịch sử đơn hàng</h1>
    <?php if ($result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id']; ?></td>
                        <td><?= $row['order_date']; ?></td>
                        <td><?= number_format($row['total_price'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?= $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
