<?php
// /views/partials/header.php
// Session phải được bắt đầu ở file gốc (ví dụ: login.php, index.php) TRƯỚC KHI include file này
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Chăm sóc Thú cưng</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/BTL/css/style.css">
</head>
<body>
    <header>
        <div class="container-header">
            <h1 class="logo"><a href="/BTL/index.php">PetCare</a></h1>
            <nav>
                <ul>
                    <li><a href="/BTL/views/services.php">Dịch vụ</a></li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <li><a href="/BTL/views/admin/dashboard.php">Quản lý Lịch hẹn</a></li>
                            <li><a href="/BTL/views/admin/manage_services.php">Quản lý Dịch vụ</a></li>
                            <li><a href="/BTL/views/admin/manage_pets.php">Quản lý Thú cưng</a></li>
                        <?php else: ?>
                            <li><a href="/BTL/views/user/new_booking.php">Đặt lịch</a></li>
                            <li><a href="/BTL/views/user/my_bookings.php">Lịch hẹn</a></li>
                        <?php endif; ?>

                        <!-- Đổi mật khẩu (cho mọi user đã đăng nhập) -->
                        <li><a href="/BTL/views/user/change_password.php">Đổi mật khẩu</a></li>

                        <li class="user-greeting">Chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</li>
                        
                        <li><a href="/BTL/handle/logout_process.php" class="btn-logout">Đăng xuất</a></li>
                    
                    <?php else: ?>
                        <li><a href="/BTL/views/login.php">Đăng nhập</a></li>
                        <li><a href="/BTL/views/register.php" class="btn-register">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>