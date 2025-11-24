<?php
include '../../functions/db.php';
include '../../functions/auth_check.php';

$user_id = $_SESSION['user_id'];
$message = '';

// Lấy danh sách thú cưng CỦA NGƯỜI DÙNG NÀY
$pets_stmt = $pdo->prepare("SELECT * FROM pets WHERE customer_id = ?");
$pets_stmt->execute([$user_id]);
$pets = $pets_stmt->fetchAll();

// Lấy danh sách dịch vụ
$services_stmt = $pdo->query("SELECT * FROM services");
$services = $services_stmt->fetchAll();

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem khách đã có thú cưng chưa
    if (empty($pets)) {
        $message = "Bạn cần <a href='my_pets.php'>thêm thú cưng</a> trước khi đặt lịch.";
    } else {
        $pet_id = $_POST['pet_id'];
        $service_id = $_POST['service_id'];
        $booking_date = $_POST['booking_date'];
        $notes = $_POST['notes'];

        try {
            $sql = "INSERT INTO bookings (customer_id, pet_id, service_id, booking_date, notes) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $pet_id, $service_id, $booking_date, $notes]);
            $message = "Đặt lịch thành công! Chúng tôi sẽ sớm liên hệ xác nhận.";
        } catch (PDOException $e) {
            $message = "Lỗi đặt lịch: " . $e->getMessage();
        }
    }
}

include '../partials/header.php';
?>

<h2>Đặt lịch hẹn mới</h2>

<?php if ($message): ?>
    <div class="message <?php echo (strpos($message, 'thành công') !== false) ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if (empty($pets)): ?>
    <div class="message error">
        Bạn chưa có thú cưng nào trong hệ thống.
        Vui lòng **liên hệ Admin (qua SĐT/Email)** để được hỗ trợ thêm thú cưng vào tài khoản của bạn trước khi đặt lịch.
    </div>
<?php else: ?>
    <div class="form-container">
        <form action="new_booking.php" method="POST">
            <div class="form-group">
                <label>Chọn thú cưng:</label>
                <select name="pet_id" required>
                    <option value="">-- Chọn --</option>
                    <?php foreach ($pets as $pet): ?>
                        <option value="<?php echo $pet['id']; ?>">
                            <?php echo htmlspecialchars($pet['name']); ?> (<?php echo htmlspecialchars($pet['breed']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Chọn dịch vụ:</label>
                <select name="service_id" required>
                    <option value="">-- Chọn --</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>">
                            <?php echo htmlspecialchars($service['service_name']); ?> (<?php echo number_format($service['price'], 0, ',', '.'); ?> VND)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Ngày giờ hẹn:</label>
                <input type="datetime-local" name="booking_date" required>
            </div>

            <div class="form-group">
                <label>Ghi chú (nếu có):</label>
                <textarea name="notes"></textarea>
            </div>

            <button type="submit">Xác nhận đặt lịch</button>
        </form>
    </div>
<?php endif; ?>

<?php
include '../partials/footer.php';
?>