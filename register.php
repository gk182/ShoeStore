<?php
include './includes/db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Kiểm tra email đã tồn tại chưa
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "Email đã được sử dụng!";
    } else {
        // Thêm người dùng mới, role mặc định là 'customer'
        $sql = "INSERT INTO user (username, email, password, fullname, role) VALUES ('$username', '$email', '$password', '$fullname', 'customer')";
        if ($conn->query($sql)) {
            $user_id = $conn->insert_id;
            $sql_cart = "INSERT INTO cart (user_id) VALUES ($user_id)";
            if ($conn->query($sql_cart)) {
                // Chuyển hướng đến trang đăng nhập hoặc trang khác
                header('Location: login.php');
                exit;
            } else {
                $error = "Đã có lỗi khi tạo giỏ hàng!";
            }
            header('Location: login.php');
            exit;
        } else {
            $error = "Đăng ký thất bại. Vui lòng thử lại!";
        }
    }
}

?>

<?php include('./includes/header.php'); ?>
<div class="container mt-5">
    <h2>Đăng ký tài khoản</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <div class="mb-3">
            <label for="username" class="form-label">Tên người dùng</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Họ và tên</label>
            <input type="text" name="fullname" id="fullname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng ký</button>
    </form>
    <div class="mt-3">
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</div>

<?php include('./includes/footer.php'); ?>