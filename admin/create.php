<?php

include 'header_admin.php';
// Kết nối đến cơ sở dữ liệu
include '../includes/db.php';

// Kiểm tra nếu có dữ liệu gửi lên từ form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description']; // Lấy giá trị mô tả từ form
    $image_name = $_POST['image_name']; // Lấy tên ảnh từ form

    // Kiểm tra đầu vào của tên, giá và mô tả
    if (empty($name) || empty($price)) {
        echo "Vui lòng nhập đầy đủ thông tin.";
        exit();
    }

    // Kiểm tra giá có phải là số
    if (!is_numeric($price)) {
        echo "Giá phải là một số.";
        exit();
    }

    // Kiểm tra nếu tên ảnh được nhập vào
    if (empty($image_name)) {
        echo "Vui lòng nhập tên ảnh.";
        exit();
    }

    // Xử lý upload ảnh
    $target_dir = "../assets/images/"; // Thư mục lưu trữ ảnh

    // Kiểm tra nếu thư mục không tồn tại thì tạo mới
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);  // Tạo thư mục với quyền ghi
    }

    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    // Tạo tên tệp mới dựa trên tên người bán
    $new_filename = $image_name . "." . $imageFileType;
    $target_file = $target_dir . $new_filename;

    // Kiểm tra kích thước file (giới hạn 5MB)
    if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
        echo "File quá lớn. Kích thước tối đa là 5MB.";
        exit();
    }

    // Cho phép chỉ các định dạng ảnh được phép
    $allowed_formats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Chỉ cho phép các định dạng ảnh JPG, JPEG, PNG và GIF.";
        exit();
    }

    // Di chuyển và lưu trữ file vào thư mục
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Lưu đường dẫn tương đối của hình ảnh và mô tả vào cơ sở dữ liệu
        $relative_path = "" . $new_filename;

        // Prepared statement để tránh SQL injection
        $stmt = $conn->prepare("INSERT INTO product (product_name, price, image_url, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $price, $relative_path, $description);

        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Lỗi: " . $stmt->error;
        }

        // Đóng prepared statement
        $stmt->close();
    } else {
        echo "Có lỗi khi tải lên file ảnh.";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Giày Mới</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Thêm Giày Mới</h2>
        <form action="create.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Tên Giày:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá:</label>
                <input type="text" id="price" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô Tả:</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="image_name" class="form-label">Tên Ảnh:</label>
                <input type="text" id="image_name" name="image_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh:</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Giày</button>
        </form>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>

<?php include '../includes/footer.php'; ?>
