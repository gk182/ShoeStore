<?php
// Import các tệp cần thiết
include '../includes/db.php';
include 'header_admin.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: home");
    exit();
}
// Xử lý thay đổi trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Chỉ cho phép trạng thái hợp lệ
    $allowed_statuses = ['Chưa xác thực', 'Đã xác thực', 'Đang giao', 'Đã giao thành công'];

    if (in_array($status, $allowed_statuses)) {
        $sql_update = "UPDATE `order` SET `status` = ? WHERE `order_id` = ?";
        if ($stmt = $conn->prepare($sql_update)) {
            $stmt->bind_param('si', $status, $order_id);
            if (!$stmt->execute()) {
                echo "Lỗi khi cập nhật trạng thái: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Lỗi khi chuẩn bị truy vấn: " . $conn->error;
        }
    } else {
        echo "Trạng thái không hợp lệ!";
    }
}

// Xử lý xóa đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = intval($_POST['order_id']);

    $sql_delete = "DELETE FROM `order` WHERE `order_id` = ?";
    if ($stmt = $conn->prepare($sql_delete)) {
        $stmt->bind_param('i', $order_id);
        if (!$stmt->execute()) {
            echo "Lỗi khi xóa đơn hàng: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi khi chuẩn bị truy vấn: " . $conn->error;
    }
}

// Truy vấn danh sách tất cả đơn hàng
$sql_orders = "
    SELECT order_id, user_id, fullname, phone, address, order_date, total_price, status
    FROM `order`
";
if ($stmt = $conn->prepare($sql_orders)) {
    $stmt->execute();
    $result_orders = $stmt->get_result();

    $orders = [];
    if ($result_orders->num_rows > 0) {
        while ($row = $result_orders->fetch_assoc()) {
            $orders[] = $row;
        }
    } else {
        $orders = null;
    }

    $stmt->close();
} else {
    echo "Lỗi khi chuẩn bị truy vấn: " . $conn->error;
}

// Hàm lấy thông tin chi tiết đơn hàng
function getOrderDetails($order_id, $conn) {
    $sql_details = "
        SELECT od.quantity, od.price, p.product_name, s.size
        FROM order_detail od
        JOIN product p ON od.product_id = p.product_id
        JOIN size s ON od.size_id = s.size_id
        WHERE od.order_id = ?
    ";

    $details = [];
    if ($stmt = $conn->prepare($sql_details)) {
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }

        $stmt->close();
    }

    return $details;
}
?>

<div class="container mt-4">
    <div class="text-center mb-4">
        <h1>Quản lí đơn hàng</h1>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt hàng</th>
                        <th>Tổng giá trị</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['order_id']) ?></td>
                                <td><?= htmlspecialchars($order['fullname']) ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                        <select name="status" onchange="this.form.submit()" class="form-select">
                                            <option <?= $order['status'] == 'Chưa xác thực' ? 'selected' : '' ?>>Chưa xác thực</option>
                                            <option <?= $order['status'] == 'Đã xác thực' ? 'selected' : '' ?>>Đã xác thực</option>
                                            <option <?= $order['status'] == 'Đang giao' ? 'selected' : '' ?>>Đang giao</option>
                                            <option <?= $order['status'] == 'Đã giao thành công' ? 'selected' : '' ?>>Đã giao thành công</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?= htmlspecialchars($order['order_date']) ?></td>
                                <td><?= htmlspecialchars($order['total_price']) ?></td>

                                <td>
                                    <div style="display: flex; justify-content: space-around; align-items: center; gap: 10px;">
                                        <!-- Xem thông tin chi tiết -->
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal<?= htmlspecialchars($order['order_id']) ?>">
                                            Xem chi tiết
                                        </button>

                                        <!-- Xóa đơn hàng -->
                                        <form method="POST" action="" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                            <button type="submit" name="delete_order" class="btn btn-danger">Xóa</button>
                                        </form>
                                    </div>

                                   <!-- Modal chi tiết đơn hàng -->
                                   <div class="modal fade" id="detailsModal<?= htmlspecialchars($order['order_id']) ?>" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailsModalLabel">Chi tiết đơn hàng - Mã đơn: <?= htmlspecialchars($order['order_id']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6><strong>Khách hàng:</strong> <?= htmlspecialchars($order['fullname']) ?></h6>
                                                    <h6><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></h6>
                                                    <h6><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></h6>
                                                    <h6><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></h6>
                                                    <h6><strong>Ngày đặt hàng:</strong> <?= htmlspecialchars($order['order_date']) ?></h6>
                                                    <h6><strong>Tổng giá trị:</strong> <?= htmlspecialchars($order['total_price']) ?> VND</h6>
                                                    <hr>
                                                    <h5>Danh sách sản phẩm</h5>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Tên sản phẩm</th>
                                                                <th>Kích thước</th>
                                                                <th>Số lượng</th>
                                                                <th>Giá</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $details = getOrderDetails($order['order_id'], $conn); ?>
                                                            <?php foreach ($details as $detail): ?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($detail['product_name']) ?></td>
                                                                    <td><?= htmlspecialchars($detail['size']) ?></td>
                                                                    <td><?= htmlspecialchars($detail['quantity']) ?></td>
                                                                    <td><?= htmlspecialchars($detail['price']) ?> VND</td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
