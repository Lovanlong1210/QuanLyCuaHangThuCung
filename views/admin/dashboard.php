<?php
// THÊM 3 DÒNG NÀY VÀO ĐẦU FILE
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// /views/admin/dashboard.php
// (Đã xóa dòng session_start())
 
include '../../functions/db.php';
include '../../functions/admin_check.php';

// No search/filter: fetch all bookings (server-side pagination still applies)
$params = [];
$where = "";

// Lấy TẤT CẢ lịch hẹn (có thể có WHERE động)
// Pagination
$perPage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Count total records for pagination
$countSql = "SELECT COUNT(*) FROM bookings AS b
    JOIN customers AS c ON b.customer_id = c.id
    JOIN pets AS p ON b.pet_id = p.id
    JOIN services AS s ON b.service_id = s.id
    WHERE 1=1 " . $where;
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = (int)ceil($total / $perPage);

$sql = "SELECT b.*, c.name AS customer_name, p.name AS pet_name, s.service_name
    FROM bookings AS b
    JOIN customers AS c ON b.customer_id = c.id
    JOIN pets AS p ON b.pet_id = p.id
    JOIN services AS s ON b.service_id = s.id
    WHERE 1=1 " . $where . " ORDER BY b.booking_date DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

// Đi lùi 1 cấp (..) để ra khỏi thư mục 'admin', rồi vào 'partials'
include '../partials/header.php';
?>

<main class="container">
    <h2>Quản lý Lịch hẹn (Admin Dashboard)</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- No filter UI -->

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
                            <form action="../../handle/admin_booking_process.php" method="POST" style="flex-direction: row; align-items: center;">
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
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div style="margin-top:12px; display:flex; gap:8px; align-items:center;">
                <?php if ($page > 1): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page-1])); ?>">&laquo; Trước</a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <?php if ($p == $page): ?>
                        <strong style="padding:6px 10px; background:#eee; border-radius:4px"><?php echo $p; ?></strong>
                    <?php else: ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $p])); ?>" style="padding:6px 10px;"><?php echo $p; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page+1])); ?>">Tiếp &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php
include '../partials/footer.php';
?>