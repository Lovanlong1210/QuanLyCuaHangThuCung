<?php
// /functions/admin_check.php

// Kiểm tra xem session đã bắt đầu chưa, nếu chưa thì bắt đầu
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    // Nếu không phải admin, đá về trang đăng nhập (Dùng đường dẫn tuyệt đối cho an toàn)
    header("Location: /BTL/views/login.php");
    exit;
}
?>