<?php
include './includes/db.php';
include './includes/header.php';

// Số lượng sản phẩm hiển thị mỗi trang
$items_per_page = 9;

// Xác định trang hiện tại, nếu không có sẽ mặc định là 1
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Tính OFFSET
$offset = ($current_page - 1) * $items_per_page;

// Lấy từ khóa tìm kiếm từ form
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Tìm kiếm sản phẩm
if (!empty($query)) {
    $sql = "SELECT * FROM product WHERE product_name LIKE ? OR description LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$query%";
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $items_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Đếm tổng số kết quả
    $total_sql = "SELECT COUNT(*) AS total FROM product WHERE product_name LIKE ? OR description LIKE ?";
    $total_stmt = $conn->prepare($total_sql);
    $total_stmt->bind_param("ss", $searchTerm, $searchTerm);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_items = $total_row['total'];
    $total_pages = ceil($total_items / $items_per_page);
} else {
    $sql = "SELECT * FROM product LIMIT $items_per_page OFFSET $offset";
    $result = $conn->query($sql);
}
?>

<div class="container mt-4">
    <?php if (!empty($query)): ?>
        <h1>Kết quả tìm kiếm cho: "<?= htmlspecialchars($query); ?>"</h1>
    <?php endif; ?>
    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="assets/images/<?= htmlspecialchars($row['image_url']); ?>" class="card-img-top"
                            alt="<?= htmlspecialchars($row['product_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['product_name']); ?></h5>
                            <p class="card-text"><strong><?= number_format($row['price'], 0, ',', '.'); ?> VNĐ</strong></p>
                            <a href="product_detail.php?id=<?= $row['product_id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp với từ khóa "<?= htmlspecialchars($query); ?>"</p>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?query=<?= urlencode($query); ?>&page=1" aria-label="First">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?query=<?= urlencode($query); ?>&page=<?= $current_page - 1 ?>"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Các nút trang -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?query=<?= urlencode($query); ?>&page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?query=<?= urlencode($query); ?>&page=<?= $current_page + 1 ?>"
                            aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?query=<?= urlencode($query); ?>&page=<?= $total_pages ?>" aria-label="Last">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php include './includes/footer.php'; ?>