<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shoestore';

// Kết nối cơ sở dữ liệu
$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra lỗi
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
