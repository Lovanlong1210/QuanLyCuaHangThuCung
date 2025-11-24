<?php
session_start();
include '../functions/db.php';
include '../functions/csrf.php';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/user/change_password.php');
    exit;
}

// Require login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$token = $_POST['csrf_token'] ?? null;

// Verify CSRF
if (!verify_csrf_token($token)) {
    $_SESSION['error'] = 'Yêu cầu không hợp lệ (CSRF). Vui lòng thử lại.';
    header('Location: ../views/user/change_password.php');
    exit;
}

// Simple rate limiter for password change attempts (per session)
$maxAttempts = 5;
$window = 600; // 10 minutes
if (!isset($_SESSION['pw_change_attempts'])) {
    $_SESSION['pw_change_attempts'] = 0;
    $_SESSION['pw_change_time'] = time();
}
if (time() - ($_SESSION['pw_change_time'] ?? 0) > $window) {
    // reset window
    $_SESSION['pw_change_attempts'] = 0;
    $_SESSION['pw_change_time'] = time();
}
if ($_SESSION['pw_change_attempts'] >= $maxAttempts) {
    $_SESSION['error'] = 'Bạn đã thử quá nhiều lần. Vui lòng thử lại sau vài phút.';
    header('Location: ../views/user/change_password.php');
    exit;
}

// Validation
if ($new === '' || $confirm === '' || $current === '') {
    $_SESSION['error'] = 'Vui lòng điền đủ các trường.';
    $_SESSION['pw_change_attempts']++;
    header('Location: ../views/user/change_password.php');
    exit;
}
if ($new !== $confirm) {
    $_SESSION['error'] = 'Mật khẩu mới và xác nhận không khớp.';
    $_SESSION['pw_change_attempts']++;
    header('Location: ../views/user/change_password.php');
    exit;
}

// Stronger password policy: >=8 chars, uppercase, lowercase, digit
if (strlen($new) < 8 || !preg_match('/[A-Z]/', $new) || !preg_match('/[a-z]/', $new) || !preg_match('/[0-9]/', $new)) {
    $_SESSION['error'] = 'Mật khẩu mới phải ít nhất 8 ký tự và chứa chữ hoa, chữ thường và số.';
    $_SESSION['pw_change_attempts']++;
    header('Location: ../views/user/change_password.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT password FROM customers WHERE id = ?');
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();

    if (!$row || !password_verify($current, $row['password'])) {
        $_SESSION['error'] = 'Mật khẩu hiện tại không đúng.';
        $_SESSION['pw_change_attempts']++;
        header('Location: ../views/user/change_password.php');
        exit;
    }

    $new_hash = password_hash($new, PASSWORD_DEFAULT);
    $up = $pdo->prepare('UPDATE customers SET password = ? WHERE id = ?');
    $up->execute([$new_hash, $user_id]);

    // reset attempts on success
    $_SESSION['pw_change_attempts'] = 0;
    $_SESSION['message'] = 'Đổi mật khẩu thành công.';
    header('Location: ../views/user/change_password.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'Lỗi hệ thống, vui lòng thử lại.';
    header('Location: ../views/user/change_password.php');
    exit;
}

?>
