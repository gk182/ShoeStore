<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('./includes/db.php');

// Kiểm tra nếu người dùng đã đăng nhập rồi, chuyển hướng đến trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra email trong cơ sở dữ liệu
    $sql = "SELECT * FROM user WHERE email = '$username' or username = '$username' ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công, lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Chuyển hướng người dùng đến trang chủ hoặc trang nhân viên
            if ($user['role'] == 'staff') {
                header('Location: staff');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error_message = "Mật khẩu không chính xác!";
        }
    } else {
        $error_message = "Email hoặc Username không tồn tại!";
    }
}

?>

<?php include('./includes/header.php'); ?>

<div class="container mt-5">
    <h2>Đăng nhập</h2>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <!-- Form đăng nhập -->
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập hoặc Email</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </form>

    <div class="mt-3">
        <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
    </div>
</div>

<!-- Liên kết Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


<?php include('./includes/footer.php'); ?>
