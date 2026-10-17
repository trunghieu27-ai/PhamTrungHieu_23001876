<?php
/**
 * Tiện ích và Middleware Kiểm soát Xác thực người dùng (Authentication & Access Control)
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Kiểm tra xem người dùng hiện tại đã đăng nhập hay chưa
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * Bắt buộc người dùng phải đăng nhập mới được truy cập trang.
 * Nếu chưa đăng nhập, tự động lưu thông báo và chuyển hướng về trang login.php.
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error_msg'] = 'Vui lòng đăng nhập để tiếp tục truy cập hệ thống.';
        header('Location: login.php');
        exit;
    }
}

/**
 * Chuyển hướng người dùng đã đăng nhập sang product_list.php
 * (Dùng cho các trang như login.php, register.php khi đã có phiên đăng nhập)
 */
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: product_list.php');
        exit;
    }
}

/**
 * Lấy thông tin tài khoản người dùng đang đăng nhập
 * @return array|null
 */
function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Lấy tên tài khoản của người dùng đang đăng nhập
 * @return string
 */
function getCurrentUsername() {
    return isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';
}
