<?php
/**
 * Model xử lý dữ liệu cho bảng users (Đăng ký, Đăng nhập, Quản lý tài khoản)
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/../common/dbConnect.php';

/**
 * Tìm thông tin người dùng theo tên đăng nhập
 * @param string $username Tên đăng nhập
 * @return array|false Thông tin user hoặc false nếu không tồn tại
 */
function findUserByUsername($username) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([trim($username)]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Lỗi trong findUserByUsername: " . $e->getMessage());
        return false;
    }
}

/**
 * Kiểm tra xem tên đăng nhập đã tồn tại trong database hay chưa
 * @param string $username Tên đăng nhập cần kiểm tra
 * @return bool True nếu đã tồn tại, False nếu chưa
 */
function checkUsernameExists($username) {
    return (findUserByUsername($username) !== false);
}

/**
 * Tạo mới một tài khoản người dùng
 * @param string $username Tên đăng nhập
 * @param string $hashedPassword Mật khẩu đã được mã hóa bằng password_hash()
 * @return bool True nếu tạo thành công, False nếu thất bại
 */
function createUser($username, $hashedPassword) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([
            trim($username),
            $hashedPassword
        ]);
    } catch (PDOException $e) {
        error_log("Lỗi trong createUser: " . $e->getMessage());
        return false;
    }
}

/**
 * Lấy thông tin người dùng theo ID
 * @param int $id Mã người dùng
 * @return array|false Thông tin user hoặc false nếu không tồn tại
 */
function getUserById($id) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Lỗi trong getUserById: " . $e->getMessage());
        return false;
    }
}
