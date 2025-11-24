<?php
session_start();
include '../functions/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../views/login.php"); exit;
}

// --- XỬ LÝ XÓA ---
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $_SESSION['message'] = "Xóa dịch vụ thành công!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi xóa: " . $e->getMessage();
    }
    header("Location: ../views/admin/manage_services.php");
    exit;
}

// --- XỬ LÝ THÊM / SỬA ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['service_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $id = $_POST['service_id'];

    try {
        if (empty($id)) {
            // Thêm mới
            $sql = "INSERT INTO services (service_name, description, price) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $desc, $price]);
            $_SESSION['message'] = "Thêm dịch vụ thành công!";
        } else {
            // Cập nhật
            $sql = "UPDATE services SET service_name = ?, description = ?, price = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $desc, $price, $id]);
            $_SESSION['message'] = "Cập nhật dịch vụ thành công!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi: " . $e->getMessage();
    }
    header("Location: ../views/admin/manage_services.php");
    exit;
}
?>