<?php
session_start();
include '../functions/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../views/login.php"); exit;
}

// --- XỬ LÝ XÓA ---
if (isset($_GET['delete'])) {
    $pet_id_to_delete = $_GET['delete'];

    try {
        // Kiểm tra xem có lịch hẹn tham chiếu tới thú cưng này không
        $check = $pdo->prepare("SELECT COUNT(*) AS cnt FROM bookings WHERE pet_id = ?");
        $check->execute([$pet_id_to_delete]);
        $row = $check->fetch();

        if ($row && $row['cnt'] > 0) {
            // Không xóa nếu vẫn có bookings liên quan
            $_SESSION['error'] = "Không thể xóa: thú cưng này đang có lịch hẹn liên quan. Vui lòng xóa hoặc chuyển các lịch hẹn trước khi xóa thú cưng.";
        } else {
            // An toàn để xóa
            $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
            $stmt->execute([$pet_id_to_delete]);
            $_SESSION['message'] = "Xóa thú cưng thành công!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi xóa: " . $e->getMessage();
    }
    header("Location: ../views/admin/manage_pets.php");
    exit;
}

// --- XỬ LÝ THÊM / SỬA ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $notes = $_POST['medical_notes'];
    $pet_id = $_POST['pet_id'];

    try {
        if (empty($pet_id)) {
            // Thêm mới
            $sql = "INSERT INTO pets (customer_id, name, species, breed, age, medical_notes) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id, $name, $species, $breed, $age, $notes]);
            $_SESSION['message'] = "Thêm thú cưng thành công!";
        } else {
            // Cập nhật
            $sql = "UPDATE pets SET customer_id=?, name=?, species=?, breed=?, age=?, medical_notes=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id, $name, $species, $breed, $age, $notes, $pet_id]);
            $_SESSION['message'] = "Cập nhật thú cưng thành công!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi: " . $e->getMessage();
    }
    header("Location: ../views/admin/manage_pets.php");
    exit;
}
?>