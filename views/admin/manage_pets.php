<?php
// /admin/manage_pets.php
include '../../functions/db.php';
include '../../functions/admin_check.php';

$message = '';
$is_error = false;
$edit_pet = null; // Biến để lưu thông tin pet cần sửa
$is_editing = false; // Biến cờ để biết đang sửa hay thêm mới

// Nếu có thông báo từ các process redirect (đặt trong session), lấy ra
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    $message = $_SESSION['error'];
    $is_error = true;
    unset($_SESSION['error']);
}

// --- Lấy danh sách TẤT CẢ khách hàng (để làm dropdown) ---
$customers_stmt = $pdo->query("SELECT id, name, email FROM customers WHERE role = 'customer' ORDER BY name");
$customers = $customers_stmt->fetchAll();

// === BẮT ĐẦU PHẦN LOGIC XỬ LÝ ===

// NOTE: Delete is handled by `handle/admin_pet_process.php` to centralize checks

// --- HÀNH ĐỘNG 1 & 2: XỬ LÝ THÊM (CREATE) HOẶC SỬA (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id']; 
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $medical_notes = $_POST['medical_notes'];
    $pet_id_to_edit = $_POST['pet_id']; // Lấy ID thú cưng (nếu đang sửa)

    try {
        if (empty($pet_id_to_edit)) {
            // --- HÀNH ĐỘNG 1: THÊM MỚI ---
            $sql = "INSERT INTO pets (customer_id, name, species, breed, age, medical_notes) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id, $name, $species, $breed, $age, $medical_notes]); 
            $message = "Thêm thú cưng mới thành công!";
        } else {
            // --- HÀNH ĐỘNG 2: CẬP NHẬT ---
            $sql = "UPDATE pets SET customer_id = ?, name = ?, species = ?, breed = ?, age = ?, medical_notes = ? 
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id, $name, $species, $breed, $age, $medical_notes, $pet_id_to_edit]);
            $message = "Cập nhật thông tin thú cưng thành công!";
        }
    } catch (PDOException $e) {
        $message = "Lỗi: " . $e->getMessage();
    }
}

// --- HÀNH ĐỘNG SỬA (PHỤ TRỢ): LẤY THÔNG TIN ĐỂ ĐƯA VÀO FORM ---
if (isset($_GET['edit'])) {
    $pet_id_to_edit = $_GET['edit'];
    $is_editing = true; // Bật cờ "đang sửa"

    $sql = "SELECT * FROM pets WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pet_id_to_edit]);
    $edit_pet = $stmt->fetch(); // Lấy thông tin pet đưa vào biến $edit_pet
}

// === KẾT THÚC PHẦN LOGIC ===


// --- Lấy danh sách TẤT CẢ thú cưng (Luôn chạy) ---
$sql = "SELECT p.*, c.name AS customer_name 
        FROM pets AS p
        JOIN customers AS c ON p.customer_id = c.id
        ORDER BY p.id DESC";
$stmt = $pdo->query($sql);
$pets = $stmt->fetchAll();

// Phải thêm <main>
include '../partials/header.php';
?>

<main class="container">

<h2>Quản lý Thú cưng (Admin)</h2>

<?php if ($message): ?>
    <div class="message <?php echo ($is_error) ? 'error' : 'success'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<h3><?php echo $is_editing ? 'Sửa thông tin Thú cưng' : 'Thêm thú cưng mới'; ?></h3>
<form action="manage_pets.php" method="POST">
    
    <input type="hidden" name="pet_id" value="<?php echo $edit_pet['id'] ?? ''; ?>">
    
    <label>Chọn khách hàng (Chủ nuôi):</label>
    <select name="customer_id" required>
        <option value="">-- Chọn khách hàng --</option>
        <?php foreach ($customers as $customer): ?>
            <option value="<?php echo $customer['id']; ?>" 
                <?php 
                // Nếu đang sửa, tự động chọn đúng khách hàng
                if ($is_editing && $edit_pet['customer_id'] == $customer['id']) echo 'selected'; 
                ?>
            >
                <?php echo htmlspecialchars($customer['name']); ?> (<?php echo htmlspecialchars($customer['email']); ?>)
            </option>
        <?php endforeach; ?>
    </select>
    
    <label>Tên thú cưng:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($edit_pet['name'] ?? ''); ?>" required>

    <label>Loài:</label>
    <select name="species" required>
        <option value="">-- Vui lòng chọn --</option>
        <?php $species_options = ['Chó', 'Mèo', 'Chim', 'Cá', 'Hamster', 'Khác']; ?>
        <?php foreach ($species_options as $option): ?>
            <option value="<?php echo $option; ?>"
                <?php 
                // Nếu đang sửa, tự động chọn đúng loài
                if ($is_editing && $edit_pet['species'] == $option) echo 'selected'; 
                ?>
            >
                <?php echo $option; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Giống (Poodle, Mèo ta...):</label>
    <input type="text" name="breed" placeholder="Ví dụ: Poodle, Mèo ta, Vẹt..." value="<?php echo htmlspecialchars($edit_pet['breed'] ?? ''); ?>" required>

    <label>Tuổi:</label>
    <input type="number" name="age" value="<?php echo htmlspecialchars($edit_pet['age'] ?? ''); ?>">

    <label>Ghi chú sức khỏe (dị ứng, v.v...):</label>
    <textarea name="medical_notes"><?php echo htmlspecialchars($edit_pet['medical_notes'] ?? ''); ?></textarea>

    <button type="submit" name="add_pet">
        <?php echo $is_editing ? 'Cập nhật' : 'Thêm mới'; ?>
    </button>
    
    <?php if ($is_editing): ?>
        <a href="manage_pets.php" style="margin-left: 10px; display: inline-block; margin-top: 10px;">Hủy Sửa</a>
    <?php endif; ?>
</form>

<hr style="margin: 30px 0;">

<h3>Danh sách tất cả thú cưng</h3>
<?php if (empty($pets)): ?>
    <p>Chưa có thú cưng nào trong hệ thống.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Tên thú cưng</th>
                <th>Chủ nuôi (Khách hàng)</th>
                <th>Loài</th>
                <th>Giống</th>
                <th>Tuổi</th>
                <th>Ghi chú sức khỏe</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pets as $pet): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pet['name']); ?></td>
                    <td><b><?php echo htmlspecialchars($pet['customer_name']); ?></b></td>
                    <td><?php echo htmlspecialchars($pet['species']); ?></td>
                    <td><?php echo htmlspecialchars($pet['breed']); ?></td>
                    <td><?php echo htmlspecialchars($pet['age']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($pet['medical_notes'] ?? '')); ?></td>
                    <td>
                          <a class="btn btn-edit" href="manage_pets.php?edit=<?php echo $pet['id']; ?>">Sửa</a>
                          <a class="btn btn-delete" href="../../handle/admin_pet_process.php?delete=<?php echo $pet['id']; ?>" 
                              onclick="return confirm('Bạn có chắc chắn muốn xóa thú cưng này?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</main>

<?php
include '../partials/footer.php';
?>