<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php");
    } else {
        $_SESSION['error'] = "Invalid username or password";
        header("Location: index.php");
    }
    exit();
}
?>
