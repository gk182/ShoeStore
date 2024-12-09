<?php
include 'header_admin.php';
include '../includes/db.php';

// Kiểm tra nếu có id trong URL
$shoe = null; // Initialize $shoe to null
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Truy vấn để lấy thông tin sản phẩm theo id
    $sql = "SELECT * FROM product WHERE product_id=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing the SQL query: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $shoe = $result->fetch_assoc();
}

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';

    $update_fields = [];
    $params = [];
    $types = "";

    // Kiểm tra từng trường dữ liệu
    if (!empty($name)) {
        $update_fields[] = "product_name=?";
        $params[] = $name;
        $types .= "s";
    }

    if (!empty($price)) {
        $update_fields[] = "price=?";
        $params[] = $price;
        $types .= "s";
    }

    if (!empty($description)) {
        $update_fields[] = "description=?";
        $params[] = $description;
        $types .= "s";
    }

    $new_image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../assets/images/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $timestamp = time();
        $new_filename = "giay_" . $timestamp . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        // Kiểm tra kích thước file
        if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            echo "Sorry, your file is too large.";
            exit();
        }

        // Kiểm tra định dạng file ảnh hợp lệ
        $allowed_formats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_formats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }

        // Di chuyển file ảnh
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Xóa ảnh cũ nếu có
            $old_image_path = "../" . $shoe['image_url']; // Get the current image path
            if (file_exists($old_image_path)) {
                unlink($old_image_path); // Delete the old image if it exists
            }
            $new_image_path = $new_filename;
            $update_fields[] = "image_url=?";
            $params[] = $new_image_path;
            $types .= "s";
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    // Thêm id vào tham số cuối
    if (!empty($update_fields)) {
        $params[] = $id;
        $types .= "i";
        $sql = "UPDATE product SET " . implode(", ", $update_fields) . " WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Product updated successfully.";
            header("Location: manager_product");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "No updates were made.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shoe</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Shoe</h2>
        <form action="update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($shoe['product_id']); ?>">
            
            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($shoe['product_name']); ?>">
            </div>

            <!-- Price Field -->
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="text" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($shoe['price']); ?>">
            </div>

            <!-- Description Field -->
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($shoe['description']); ?></textarea>
            </div>

            <!-- Current Image -->
            <div class="mb-3">
                <label for="current-image" class="form-label">Current Image:</label><br>
                <img src="./assets/images/<?php echo htmlspecialchars($shoe['image_url']); ?>" alt="Current Image" width="150">
            </div>

            <!-- New Image Upload -->
            <div class="mb-3">
                <label for="image" class="form-label">New Image (Optional):</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Shoe</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../includes/footer.php'; ?>
