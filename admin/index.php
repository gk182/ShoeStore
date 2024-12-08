<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");  // Chuyển hướng về trang chủ nếu không phải admin
    exit();
}

include '../config.php';

// Số sản phẩm hiển thị trên mỗi trang
$limit = 5;

// Tính tổng số sản phẩm
$sql_total = "SELECT COUNT(*) AS total FROM products";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_products = $row_total['total'];

// Tính số trang
$total_pages = ceil($total_products / $limit);

// Xác định trang hiện tại
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Xác định chỉ số bắt đầu của sản phẩm trên trang hiện tại
$start_index = ($current_page - 1) * $limit;

// Truy vấn dữ liệu với LIMIT và OFFSET
$sql = "SELECT * FROM products LIMIT $start_index, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../css/styles_admin.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container" style="width: auto; display: flex; justify-content: space-between; align-items: center; background: #333;">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo của trang web">
        </div>
        <nav>
            <ul>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </div>
    <div class="admin-container">
        <h1>Quản Lý Giày</h1>
        <a href="create.php">Thêm loại mới</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Tên Giày</th>
                <th>Giá</th>
                <th>Ảnh </th>
                <th>Cài đặt</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><img src="../<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"></td>
                    <td class="actions">
                        <a href="update.php?id=<?php echo $row['id']; ?>">Chỉnh sửa</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Hiển thị các liên kết chuyển trang -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="index.php?page=<?php echo $i; ?>" <?php if ($i == $current_page)
                       echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
<?php include '../footer.php'; ?>

</html>

<?php
$conn->close();
?>
