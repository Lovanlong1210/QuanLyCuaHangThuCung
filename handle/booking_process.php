<?php
session_start();
include '../functions/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $pet_id = $_POST['pet_id'];
    $service_id = $_POST['service_id'];
    $booking_date = $_POST['booking_date'];
    $notes = $_POST['notes'];

    try {
        $sql = "INSERT INTO bookings (customer_id, pet_id, service_id, booking_date, notes) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $pet_id, $service_id, $booking_date, $notes]);
        
        $_SESSION['message'] = "Đặt lịch thành công! Vui lòng chờ xác nhận.";
        header("Location: ../views/user/my_bookings.php"); // Chuyển đến trang xem lịch sử

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi đặt lịch: " . $e->getMessage();
        header("Location: ../views/user/new_booking.php");
    }
    exit;
}
?>