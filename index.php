<?php
include 'includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    
    if ($role === 'staff') {
        include 'admin/header_admin.php';
    }
    else {
        include 'includes/header.php';
    }
}
else{
    include 'includes/header.php';
}
include 'includes/product.php';
include 'includes/footer.php';
?>

