<?php
include '../../functions/db.php';
include '../../functions/auth_check.php';

$user_id = $_SESSION['user_id'];

// Lấy lịch sử đặt lịch của người dùng, JOIN để lấy tên thú cưng và dịch vụ
$sql = "SELECT 
            b.id,
            b.booking_date,
            b.status,
            p.name AS pet_name,
            s.service_name,
            s.price
        FROM bookings AS b
        JOIN pets AS p ON b.pet_id = p.id
        JOIN services AS s ON b.service_id = s.id
        WHERE b.customer_id = ?
        ORDER BY b.booking_date DESC";
        
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();

include '../partials/header.php';
?>

<h2>Lịch hẹn của tôi</h2>

<?php if (empty($bookings)): ?>
    <p>Bạn chưa có lịch hẹn nào.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Ngày hẹn</th>
                <th>Thú cưng</th>
                <th>Dịch vụ</th>
                <th>Giá</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($booking['booking_date']))); ?></td>
                    <td><?php echo htmlspecialchars($booking['pet_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                    <td><?php echo number_format($booking['price'], 0, ',', '.'); ?> VND</td>
                    <td>
                        <?php 
                            if ($booking['status'] == 'Pending') echo 'Chờ xác nhận';
                            elseif ($booking['status'] == 'Confirmed') echo 'Đã xác nhận';
                            elseif ($booking['status'] == 'Completed') echo 'Đã hoàn thành';
                            elseif ($booking['status'] == 'Cancelled') echo 'Đã hủy';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
include '../partials/footer.php';
?>