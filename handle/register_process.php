<?php
session_start();
include '../functions/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Băm mật khẩu

    try {
        $sql = "INSERT INTO customers (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'customer')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $password, $phone]);
        
        $_SESSION['message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
        header("Location: ../views/login.php");

    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $_SESSION['error'] = "Email này đã được sử dụng.";
        } else {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }
        header("Location: ../views/register.php");
    }
    exit;
}
?>