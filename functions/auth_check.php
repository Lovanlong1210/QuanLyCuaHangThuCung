<?php
// /functions/auth_check.php
// Đảm bảo session đã bắt đầu
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu chưa đăng nhập, chuyển hướng tới trang login
if (!isset($_SESSION['user_id'])) {
    // Xây dựng URL đầy đủ để tránh vấn đề khi site không ở root hoặc đang dùng HTTPS
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $loginPath = '/BTL/views/login.php';
    $loginUrl = $scheme . '://' . $host . $loginPath;

    header("Location: " . $loginUrl);
    exit;
}
?>