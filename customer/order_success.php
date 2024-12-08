<?php
include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Đặt hàng thành công!</strong> Cảm ơn bạn đã mua sắm. Chúng tôi sẽ xử lý đơn hàng của bạn ngay lập tức.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <p>Bạn sẽ được chuyển hướng về trang chủ trong vài giây.</p>
</div>

<script>
    setTimeout(function() {
        window.location.href = 'home'; // Chuyển hướng về trang chủ
    }, 5000); // Sau 5 giây
</script>

<?php
include '../includes/footer.php';
?>
