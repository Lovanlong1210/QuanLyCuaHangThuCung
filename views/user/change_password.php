<?php
include '../../functions/db.php';
include '../../functions/auth_check.php';
include '../../functions/csrf.php';

$message = '';
$is_error = false;
if (isset($_SESSION['message'])) { $message = $_SESSION['message']; unset($_SESSION['message']); }
if (isset($_SESSION['error'])) { $message = $_SESSION['error']; $is_error = true; unset($_SESSION['error']); }

$csrf = generate_csrf_token();

include '../partials/header.php';
?>

<main class="container">
    <h2>Đổi mật khẩu</h2>

    <?php if ($message): ?>
        <div class="message <?php echo ($is_error) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="../../handle/change_password_process.php" method="POST">
        <label>Mật khẩu hiện tại</label>
        <input type="password" name="current_password" required>

        <label>Mật khẩu mới</label>
        <input type="password" name="new_password" required>

        <label>Xác nhận mật khẩu mới</label>
        <input type="password" name="confirm_password" required>

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">

        <button type="submit">Cập nhật mật khẩu</button>
    </form>

</main>

<?php include '../partials/footer.php'; ?>
<?php
