<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'header_admin.php';
include '../includes/db.php'; // Đảm bảo đường dẫn đúng

// Lấy giá trị tìm kiếm từ URL (nếu có)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Số sản phẩm trên mỗi trang
$products_per_page = 12;

// Lấy trang hiện tại từ URL, mặc định là trang 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

// Tổng số sản phẩm (có tìm kiếm hay không)
$query_count = "SELECT COUNT(*) AS total FROM product WHERE product_name LIKE ?";
$stmt_count = $conn->prepare($query_count);
$search_param = '%' . $search . '%';
$stmt_count->bind_param("s", $search_param);
$stmt_count->execute();
$total_result = $stmt_count->get_result();
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];

// Tổng số trang
$total_pages = ceil($total_products / $products_per_page);

// Lấy danh sách sản phẩm có tìm kiếm
$query = "SELECT * FROM product WHERE product_name LIKE ? LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sii", $search_param, $offset, $products_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Lấy thông tin tồn kho khi chọn sản phẩm
$product_name = null;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Lấy tên sản phẩm
    $stmt_product_name = $conn->prepare("SELECT product_name FROM product WHERE product_id = ?");
    $stmt_product_name->bind_param("i", $product_id);
    $stmt_product_name->execute();
    $stmt_product_name->bind_result($product_name);
    $stmt_product_name->fetch();
    $stmt_product_name->close();

    // Lấy tồn kho sản phẩm
    $stmt_inventory = $conn->prepare("SELECT size, quantity FROM size WHERE product_id = ?");
    $stmt_inventory->bind_param("i", $product_id);
    $stmt_inventory->execute();
    $result_inventory = $stmt_inventory->get_result();
}

// Xử lý cập nhật/xóa/thêm tồn kho
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'];

    if ($action == 'update' && isset($_POST['size'], $_POST['quantity'])) {
        $size = $_POST['size'];
        $quantity = $_POST['quantity'];

        $stmt_update = $conn->prepare("UPDATE size SET quantity = ? WHERE product_id = ? AND size = ?");
        $stmt_update->bind_param("iis", $quantity, $product_id, $size);
        $stmt_update->execute();
        $stmt_update->close();
    } elseif ($action == 'delete' && isset($_POST['size'])) {
        $size = $_POST['size'];

        $stmt_delete = $conn->prepare("DELETE FROM size WHERE product_id = ? AND size = ?");
        $stmt_delete->bind_param("is", $product_id, $size);
        $stmt_delete->execute();
        $stmt_delete->close();
    } elseif ($action == 'add' && isset($_POST['new_size'], $_POST['new_quantity'])) {
        $new_size = $_POST['new_size'];
        $new_quantity = $_POST['new_quantity'];

        $stmt_add = $conn->prepare("INSERT INTO size (product_id, size, quantity) VALUES (?, ?, ?)");
        $stmt_add->bind_param("isi", $product_id, $new_size, $new_quantity);
        $stmt_add->execute();
        $stmt_add->close();
    }
    header("Location: inventory?product_id=" . $product_id);
    exit();
}
?>

<body>
    <div class="container mt-4">
        <?php if (!isset($product_id)) { ?>
            <h3 class="text-center">Quản lý tồn kho</h3>
            <form class="mb-3" action="" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Tìm sản phẩm..."
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </div>
            </form>

            <div class="row">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <!-- Hiển thị ảnh sản phẩm -->
                            <img src="./assets/images/<?php echo htmlspecialchars($row['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: 100%; height: auto;"
                                class="card-img-top">

                            <!-- Thông tin sản phẩm -->
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                                <p class="card-text">
                                    Mã sản phẩm: <?php echo htmlspecialchars($row['product_id']); ?><br>
                                    <strong>
                                        <?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ
                                    </strong>
                                </p>
                            </div>

                            <!-- Nút xem chi tiết -->
                            <div class="card-footer">
                                <a href="inventory?product_id=<?php echo $row['product_id']; ?>"
                                    class="btn btn-outline-primary w-100">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        <?php } else { ?>
            <h3 class="text-center">Tồn kho sản phẩm: <?php echo htmlspecialchars($product_name); ?></h3>
            <form action="inventory" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <div class="mb-3">
                    <label for="new_size" class="form-label">Size:</label>
                    <input type="text" name="new_size" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="new_quantity" class="form-label">Số lượng:</label>
                    <input type="number" name="new_quantity" class="form-control" required>
                </div>
                <button type="submit" name="action" value="add" class="btn btn-success">Thêm</button>
            </form>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Cập nhật</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_inventory->fetch_assoc()) { ?>
                        <tr>
                            <form action="inventory" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="hidden" name="size" value="<?php echo $row['size']; ?>">
                                <td><?php echo htmlspecialchars($row['size']); ?></td>
                                <td><input type="number" name="quantity" value="<?php echo $row['quantity']; ?>"
                                        class="form-control"></td>
                                <td><button type="submit" name="action" value="update" class="btn btn-primary btn-sm">Cập
                                        nhật</button></td>
                            </form>
                            <form action="inventory" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="hidden" name="size" value="<?php echo $row['size']; ?>">
                                <td><button type="submit" name="action" value="delete"
                                        class="btn btn-danger btn-sm">Xóa</button></td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <a href="inventory" class="btn btn-secondary">Trở về</a>
            </div>
        <?php } ?>
    </div>
</body>


<?php include '../includes/footer.php'; ?>