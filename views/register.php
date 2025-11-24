<?php
session_start(); // Bắt đầu session để nhận thông báo lỗi
include 'partials/header.php'; // ĐƯỜNG DẪN MỚI
?>

<main class="container">
    <h2>Đăng ký tài khoản</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="../handle/register_process.php" method="POST">
        <label>Họ tên:</label>
        <input type="text" name="name" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Số điện thoại:</label>
        <input type="text" name="phone">
        
        <label>Mật khẩu:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Đăng ký</button>
    </form>
</main>

<?php
include 'partials/footer.php'; // ĐƯỜNG DẪN MỚI
?>