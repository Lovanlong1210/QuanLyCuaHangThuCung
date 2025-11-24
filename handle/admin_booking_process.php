<?php
session_start();
include '../functions/db.php';

// Kiểm tra quyền Admin (có thể include admin_check.php nếu file đó nằm trong functions)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $booking_id]);
        
        $_SESSION['message'] = "Cập nhật trạng thái thành công!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi: " . $e->getMessage();
    }
    
    // Quay lại Dashboard Admin
    header("Location: ../views/admin/dashboard.php");
    exit;
}
?>