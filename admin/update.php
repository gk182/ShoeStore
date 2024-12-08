<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id=$id";
    $result = $conn->query($sql);
    $shoe = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Xử lý upload ảnh mới
    $target_dir = "../images/";
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
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
        // Xóa tệp ảnh cũ (nếu có)
        if (file_exists("../" . $shoe['image'])) {
            unlink("../" . $shoe['image']);
        }

        // Lưu tên tệp ảnh mới trong cơ sở dữ liệu
        $new_image_path = "images/" . $new_filename;
        $sql = "UPDATE products SET name='$name', price='$price', image='$new_image_path' WHERE id=$id";
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
    <title>Edit Shoe</title>
    <link rel="stylesheet" href="../css/styles_admin.css">
</head>
<body>
    <div class="admin-container">
        <h2>Edit Shoe</h2>
        <form action="update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $shoe['id']; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $shoe['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" value="<?php echo $shoe['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="current-image">Current Image:</label><br>
                <img src="../<?php echo $shoe['image']; ?>" alt="Current Image" width="150">
            </div>
            <div class="form-group">
                <label for="image">New Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit">Update Shoe</button>
        </form>
    </div>
</body>
</html>


<?php
$conn->close();
?>
