<?php
// /admin/index.php
include '../../functions/db.php';
include '../../functions/admin_check.php'; // Yêu cầu quyền admin

$message = '';

// Xử lý cập nhật trạng thái (Đây mới là code đúng)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $booking_id]);
        $message = "Cập nhật trạng thái thành công!";
    } catch (PDOException $e) {
        $message = "Lỗi: " . $e->getMessage();
    }
}

// Lấy TẤT CẢ lịch hẹn, sắp xếp theo lịch hẹn mới nhất
$sql = "SELECT 
            b.*,
            c.name AS customer_name,
            p.name AS pet_name,
            s.service_name
        FROM bookings AS b
        JOIN customers AS c ON b.customer_id = c.id
        JOIN pets AS p ON b.pet_id = p.id
        JOIN services AS s ON b.service_id = s.id
        ORDER BY b.booking_date DESC";
        
$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll();

// Phải thêm <main> vì chúng ta đã sửa header/footer
include '../partials/header.php';
?>

<main class="container">

<h2>Quản lý Lịch hẹn (Admin)</h2>

<?php if ($message): ?>
    <div class="message success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (empty($bookings)): ?>
    <p>Chưa có lịch hẹn nào trong hệ thống.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Thú cưng</th>
                <th>Dịch vụ</th>
                <th>Ngày hẹn</th>
                <th>Trạng thái</th>
                <th>Cập nhật</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['pet_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($booking['booking_date']))); ?></td>
                    <td><b><?php echo htmlspecialchars($booking['status']); ?></b></td>
                    <td>
                        <form action="index.php" method="POST" style="flex-direction: row; align-items: center;">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if($booking['status'] == 'Pending') echo 'selected'; ?>>Chờ xác nhận</option>
                                <option value="Confirmed" <?php if($booking['status'] == 'Confirmed') echo 'selected'; ?>>Đã xác nhận</option>
                                <option value="Completed" <?php if($booking['status'] == 'Completed') echo 'selected'; ?>>Đã hoàn thành</option>
                                <option value="Cancelled" <?php if($booking['status'] == 'Cancelled') echo 'selected'; ?>>Hủy bỏ</option>
                            </select>
                            <button type="submit" name="update_status" style="margin-left: 10px; margin-top: 0;">Lưu</button>
                        </form>
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