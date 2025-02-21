<?php
session_start();

// Xóa session của người dùng
unset($_SESSION["user"]);
session_destroy(); // Hủy session

// Đảm bảo rằng không có lỗi trước khi chuyển hướng
header("Location: index.php?logout=success");
exit;
?>