<?php
/**
 * File kết nối Cơ sở dữ liệu MySQL cho Hệ thống Quản lý Sản phẩm
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

$host     = 'localhost';
$dbname   = 'shopping_cart';
$username = 'root';
$password = '';
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Ghi nhận lỗi và hiển thị thông báo an toàn
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

/**
 * Hàm lấy kết nối CSDL khi cần sử dụng trong phạm vi hàm (Scope)
 * @return PDO
 */
function getDbConnection() {
    global $conn;
    return $conn;
}
