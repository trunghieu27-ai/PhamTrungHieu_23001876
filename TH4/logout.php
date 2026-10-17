<?php
/**
 * Xử lý Đăng xuất - Shopping Cart Management System
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Xóa thông tin phiên đăng nhập bằng session
unset($_SESSION['user']);
session_destroy();

// Khởi tạo phiên mới để mang thông báo về trang đăng nhập
session_start();
$_SESSION['success_msg'] = 'Bạn đã đăng xuất khỏi hệ thống thành công.';

// 2. Chuyển người dùng về trang login.php sau khi đăng xuất
header('Location: login.php');
exit;
