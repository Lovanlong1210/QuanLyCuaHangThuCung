<?php
// /admin/manage_services.php
include '../../functions/db.php';
include '../../functions/admin_check.php'; // Yêu cầu quyền admin

$message = '';
$edit_service = null; // Biến để lưu thông tin dịch vụ cần sửa
$is_editing = false; // Biến cờ

// --- HÀNH ĐỘNG 3: XỬ LÝ XÓA (DELETE) ---
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    try {
        $sql = "DELETE FROM services WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_to_delete]);
        $message = "Xóa dịch vụ thành công!";
    } catch (PDOException $e) {
        $message = "Lỗi khi xóa (Có thể dịch vụ đã được đặt lịch): " . $e->getMessage();
    }
}

// --- HÀNH ĐỘNG 1 & 2: XỬ LÝ THÊM (CREATE) HOẶC SỬA (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $id_to_edit = $_POST['service_id']; // Lấy ID dịch vụ (nếu đang sửa)

    try {
        if (empty($id_to_edit)) {
            // --- HÀNH ĐỘNG 1: THÊM MỚI ---
            $sql = "INSERT INTO services (service_name, description, price) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$service_name, $description, $price]);
            $message = "Thêm dịch vụ mới thành công!";
        } else {
            // --- HÀNH ĐỘNG 2: CẬP NHẬT ---
            $sql = "UPDATE services SET service_name = ?, description = ?, price = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$service_name, $description, $price, $id_to_edit]);
            $message = "Cập nhật dịch vụ thành công!";
        }
    } catch (PDOException $e) {
        $message = "Lỗi: " . $e->getMessage();
    }
}

// --- HÀNH ĐỘNG SỬA (PHỤ TRỢ): LẤY THÔNG TIN ĐỂ ĐƯA VÀO FORM ---
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $is_editing = true; // Bật cờ "đang sửa"

    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_to_edit]);
    $edit_service = $stmt->fetch(); // Lấy thông tin dịch vụ
}

// --- Lấy danh sách TẤT CẢ dịch vụ (Luôn chạy) ---
$sql = "SELECT * FROM services ORDER BY price ASC";
$stmt = $pdo->query($sql);
$services = $stmt->fetchAll();

// Phải thêm <main>
include '../partials/header.php';
?>

<main class="container">

<h2>Quản lý Dịch vụ (Admin)</h2>

<?php if ($message): ?>
    <div class="message <?php echo (strpos($message, 'Lỗi') !== false) ? 'error' : 'success'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<h3><?php echo $is_editing ? 'Sửa dịch vụ' : 'Thêm dịch vụ mới'; ?></h3>
<form action="manage_services.php" method="POST">
    
    <input type="hidden" name="service_id" value="<?php echo $edit_service['id'] ?? ''; ?>">
    
    <label>Tên dịch vụ:</label>
    <input type="text" name="service_name" value="<?php echo htmlspecialchars($edit_service['service_name'] ?? ''); ?>" required>
    
    <label>Mô tả:</label>
    <textarea name="description"><?php echo htmlspecialchars($edit_service['description'] ?? ''); ?></textarea>
    
    <label>Giá (VND):</label>
    <input type="number" name="price" step="1000" value="<?php echo htmlspecialchars($edit_service['price'] ?? ''); ?>" required>
    
    <button type="submit">
        <?php echo $is_editing ? 'Cập nhật' : 'Thêm mới'; ?>
    </button>
    
    <?php if ($is_editing): ?>
        <a href="manage_services.php" style="margin-left: 10px; display: inline-block; margin-top: 10px;">Hủy Sửa</a>
    <?php endif; ?>
</form>

<hr style="margin: 30px 0;">

<h3>Danh sách dịch vụ hiện có</h3>
<table>
    <thead>
        <tr>
            <th>Tên dịch vụ</th>
            <th>Mô tả</th>
            <th>Giá (VND)</th>
            <th>Hành động</th> </tr>
    </thead>
    <tbody>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                <td><?php echo htmlspecialchars($service['description']); ?></td>
                <td><?php echo number_format($service['price'], 0, ',', '.'); ?></td>
                <td>
                    <a href="manage_services.php?edit=<?php echo $service['id']; ?>" style="color: blue;">Sửa</a>
                    |
                    <a href="manage_services.php?delete=<?php echo $service['id']; ?>" 
                       style="color: red;" 
                       onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?');">
                       Xóa
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</main>

<?php
include '../partials/footer.php';
?>