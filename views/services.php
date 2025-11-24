<?php
session_start();
// ĐƯỜNG DẪN MỚI ĐẾN CSDL
include '../functions/db.php'; 

// Lấy danh sách dịch vụ
$stmt = $pdo->query("SELECT * FROM services ORDER BY price ASC");
$services = $stmt->fetchAll();

// ĐƯỜNG DẪN MỚI
include 'partials/header.php';
?>

<main class="container">
    <h2>Các dịch vụ của chúng tôi</h2>
    <p>Dưới đây là các dịch vụ chúng tôi cung cấp để chăm sóc thú cưng của bạn.</p>

    <table>
        <thead>
            <tr>
                <th>Tên dịch vụ</th>
                <th>Mô tả</th>
                <th>Giá (VND)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($service['description']); ?></td>
                    <td><?php echo number_format($service['price'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
// ĐƯỜNG DẪN MỚI
include 'partials/footer.php';
?>