<?php
// Xử lý form đăng nhập
session_start();

// Kết nối CSDL
include '../functions/db.php';

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = 'Vui lòng nhập email và mật khẩu.';
    header('Location: ../views/login.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM customers WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        // Chuyển hướng theo role
        if ($user['role'] === 'admin') {
            header('Location: ../views/admin/dashboard.php');
        } else {
            header('Location: ../views/user/new_booking.php');
        }
        exit;
    } else {
        $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác.';
        header('Location: ../views/login.php');
        exit;
    }

} catch (PDOException $e) {
    // Ghi log lỗi (nếu cần) và redirect về trang login với thông báo chung
    $_SESSION['error'] = 'Lỗi hệ thống, vui lòng thử lại sau.';
    header('Location: ../views/login.php');
    exit;
}

?>
