<?php
session_start(); // Bắt đầu session để nhận thông báo lỗi
include 'partials/header.php'; // ĐƯỜNG DẪN MỚI
?>

<main class="container">
    <h2>Đăng nhập</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="../handle/login_process.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Mật khẩu:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Đăng nhập</button>
    </form>
</main>

<?php
include 'partials/footer.php'; // ĐƯỜNG DẪN MỚI
?>