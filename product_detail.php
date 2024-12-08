<?php
include 'includes/db.php';
include 'includes/header.php';

$product_id = $_GET['id'];
$sql = "SELECT * FROM product WHERE product_id = $product_id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="assets/images/<?= $product['image_url']; ?>" class="img-fluid"
                alt="<?= $product['product_name']; ?>">
        </div>
        <div class="col-md-6">
            <h1><?= $product['product_name']; ?></h1>
            <p><?= $product['description']; ?></p>
            <p><strong><?= number_format($product['price'], 0, ',', '.'); ?> VNĐ</strong></p>
            <form action="addcart" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                <div class="mb-3">
                    <label for="size" class="form-label">Chọn size</label>
                    <select name="size" id="size" class="form-select" required>
                        <!-- Lấy size từ bảng size -->
                        <?php
                        $size_sql = "SELECT * FROM size WHERE product_id = $product_id";
                        $size_result = $conn->query($size_sql);
                        while ($size = $size_result->fetch_assoc()):
                            ?>
                            <option value="<?= $size['size_id']; ?>"><?= $size['size']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Số lượng</label>
                    <div class="input-group input-group-sm w-25">
                        <button type="button" class="btn btn-secondary btn-sm" id="decrease-quantity">-</button>
                        <input type="number" name="quantity" id="quantity"
                            class="form-control text-center form-control-sm" value="1" min="1" required>
                        <button type="button" class="btn btn-secondary btn-sm" id="increase-quantity">+</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Lấy các phần tử DOM
    const decreaseBtn = document.getElementById('decrease-quantity');
    const increaseBtn = document.getElementById('increase-quantity');
    const quantityInput = document.getElementById('quantity');

    // Thêm sự kiện cho nút Giảm
    decreaseBtn.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    // Thêm sự kiện cho nút Tăng
    increaseBtn.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    });
</script>


<?php include 'includes/footer.php'; ?>