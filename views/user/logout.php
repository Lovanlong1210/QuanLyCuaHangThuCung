<?php
// /logout.php
session_start();
session_unset();
session_destroy();
// Chuyển hướng về trang đăng nhập (đường dẫn đúng nằm trong /views)
header("Location: /BTL/views/login.php");
exit;
?>