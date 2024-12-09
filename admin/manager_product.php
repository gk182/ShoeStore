<?php

include '../includes/db.php';
include 'header_admin.php';

// Số sản phẩm hiển thị trên mỗi trang
$limit = 5;

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Tính tổng số sản phẩm với điều kiện tìm kiếm
$sql_total = "SELECT COUNT(*) AS total FROM product WHERE product_name LIKE ?";
$stmt_total = $conn->prepare($sql_total);
$search_param = "%" . $search . "%";
$stmt_total->bind_param("s", $search_param);
$stmt_total->execute();
$result_total = $stmt_total->get_result();

if (!$result_total) {
    die("Lỗi truy vấn: " . $conn->error);
}

$row_total = $result_total->fetch_assoc();
$total_products = $row_total['total'];

// Tính số trang
$total_pages = ceil($total_products / $limit);

// Xác định trang hiện tại
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Xác định chỉ số bắt đầu của sản phẩm trên trang hiện tại
$start_index = ($current_page - 1) * $limit;

// Truy vấn dữ liệu với LIMIT, OFFSET và điều kiện tìm kiếm
$sql = "SELECT product_id, product_name, price, image_url, description FROM product WHERE product_name LIKE ? LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $search_param, $start_index, $limit);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Lỗi truy vấn SQL: " . $conn->error);
}
?>

<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Quản Lý Giày</h1>

        <!-- Button to add new product -->
        <div class="mb-3 text-end">
            <a href="create" class="btn btn-primary">Thêm loại mới</a>
        </div>

        <!-- Search Form -->
        <form class="mb-3" action="" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Tìm sản phẩm..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </div>
        </form>

        <!-- Product Table -->
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên Giày</th>
                    <th>Giá</th>
                    <th>Mô Tả</th>
                    <th>Ảnh</th>
                    <th>Cài đặt</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><img src="./assets/images/<?php echo $row['image_url']; ?>"
                                alt="<?php echo $row['product_name']; ?>" width="200" height="200"></td>
                        <td class="actions">
                            <!-- Edit button with outline -->
                            <a href="update?id=<?php echo $row['product_id']; ?>"
                                class="btn btn-outline-primary btn-sm">Chỉnh sửa</a>
                            <!-- Delete button with outline-danger -->
                            <a href="delete?id=<?php echo $row['product_id']; ?>"
                                class="btn btn-outline-danger btn-sm delete">Xóa</a>
                            <a href="inventory?product_id=<?php echo $row['product_id']; ?>"
                                class="btn btn-outline-secondary btn-sm">Tồn kho</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $current_page)
                        echo 'active'; ?>">
                        <a class="page-link" href="manager_product?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>

<?php include '../includes/footer.php'; ?>
