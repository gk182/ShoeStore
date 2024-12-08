<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Xử lý upload ảnh
    $target_dir = "../images/"; // Thư mục lưu trữ ảnh
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    // Tạo tên tệp mới dựa trên thời gian hiện tại
    $timestamp = time();
    $new_filename = "giay_" . $timestamp . "." . $imageFileType;
    $target_file = $target_dir . $new_filename;

    // Kiểm tra kích thước file (giới hạn 5MB)
    if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
        echo "Sorry, your file is too large.";
        exit();
    }

    // Cho phép chỉ các định dạng ảnh được phép
    $allowed_formats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit();
    }

    // Di chuyển và lưu trữ file vào thư mục
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Lưu đường dẫn tương đối của hình ảnh vào cơ sở dữ liệu
        $relative_path = "images/" . $new_filename;
        $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$relative_path')";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Shoe</title>
    <link rel="stylesheet" href="../css/styles_admin.css">
</head>
<body>
    <div class="admin-container">
        <h2>Add New Shoe</h2>
        <form action="create.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit">Add Shoe</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
