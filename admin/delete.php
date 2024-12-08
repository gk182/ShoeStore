<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy đường dẫn ảnh từ cơ sở dữ liệu
    $sql_select_image = "SELECT image FROM products WHERE id=$id";
    $result_select_image = $conn->query($sql_select_image);
    if ($result_select_image->num_rows > 0) {
        $row = $result_select_image->fetch_assoc();
        $image_path = $row['image'];
        
        // Xóa dữ liệu sản phẩm từ cơ sở dữ liệu
        $sql_delete_product = "DELETE FROM products WHERE id=$id";
        if ($conn->query($sql_delete_product) === TRUE) {
            // Xóa tệp hình ảnh từ thư mục lưu trữ
            if (file_exists($image_path)) {
                unlink($image_path); // Xóa file ảnh
            }
            header("Location: admin.php");
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    } else {
        echo "No image found for product ID: " . $id;
    }
}

$conn->close();
?>
