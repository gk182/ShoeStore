<?php


// Fix the path to include the db.php file
include '../includes/db.php'; // Ensure this path is correct

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy đường dẫn ảnh từ cơ sở dữ liệu (corrected column name)
    $sql_select_image = "SELECT image_url FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($sql_select_image); // Use prepared statements to avoid SQL injection
    if ($stmt) {
        $stmt->bind_param("i", $id); // Bind the id parameter
        $stmt->execute();
        $result_select_image = $stmt->get_result(); // Get the result of the query

        if ($result_select_image && $result_select_image->num_rows > 0) {
            $row = $result_select_image->fetch_assoc();
            $image_path = $row['image_url']; // Correct column name

            // Xóa dữ liệu sản phẩm từ cơ sở dữ liệu
            $sql_delete_product = "DELETE FROM product WHERE product_id = ?";
            $delete_stmt = $conn->prepare($sql_delete_product);
            if ($delete_stmt) {
                $delete_stmt->bind_param("i", $id);
                if ($delete_stmt->execute()) {
                    // Xóa tệp hình ảnh từ thư mục lưu trữ
                    if (file_exists($image_path)) {
                        unlink($image_path); // Xóa file ảnh
                    }
                    header("Location: manager_product");
                } else {
                    echo "Error deleting product: " . $delete_stmt->error;
                }
            } else {
                echo "Error preparing delete statement: " . $conn->error;
            }
        } else {
            echo "No image found for product ID: " . $id;
        }
    } else {
        echo "Error preparing select statement: " . $conn->error;
    }
}

$conn->close();
?>
