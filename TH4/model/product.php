<?php
/**
 * Model xử lý dữ liệu cho bảng products
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/../common/dbConnect.php';

/**
 * Lấy danh sách tất cả các sản phẩm từ database
 * @return array Danh sách các sản phẩm (mảng kết hợp)
 */
function getAllProducts() {
    $conn = getDbConnection();
    try {
        $stmt = $conn->query("SELECT * FROM products ORDER BY id ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Lỗi trong getAllProducts: " . $e->getMessage());
        return [];
    }
}

/**
 * Lấy thông tin chi tiết của một sản phẩm dựa trên ID
 * @param int $id Mã sản phẩm
 * @return array|false Thông tin sản phẩm hoặc false nếu không tìm thấy
 */
function getProductById($id) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Lỗi trong getProductById: " . $e->getMessage());
        return false;
    }
}

/**
 * Thêm một sản phẩm mới vào database
 * @param string $name Tên sản phẩm
 * @param float $price Giá sản phẩm
 * @param int $quantity Số lượng sản phẩm
 * @return bool True nếu thêm thành công, False nếu thất bại
 */
function addProduct($name, $price, $quantity) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare("INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([
            trim($name),
            (float)$price,
            (int)$quantity
        ]);
    } catch (PDOException $e) {
        error_log("Lỗi trong addProduct: " . $e->getMessage());
        return false;
    }
}

/**
 * Cập nhật thông tin của sản phẩm
 * @param int $id Mã sản phẩm
 * @param string $name Tên sản phẩm
 * @param float $price Giá sản phẩm
 * @param int $quantity Số lượng sản phẩm
 * @return bool True nếu cập nhật thành công, False nếu thất bại
 */
function updateProduct($id, $name, $price, $quantity) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ? WHERE id = ?");
        return $stmt->execute([
            trim($name),
            (float)$price,
            (int)$quantity,
            (int)$id
        ]);
    } catch (PDOException $e) {
        error_log("Lỗi trong updateProduct: " . $e->getMessage());
        return false;
    }
}

/**
 * Xóa một sản phẩm khỏi database dựa trên ID
 * @param int $id Mã sản phẩm
 * @return bool True nếu xóa thành công, False nếu thất bại
 */
function deleteProduct($id) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([(int)$id]);
    } catch (PDOException $e) {
        error_log("Lỗi trong deleteProduct: " . $e->getMessage());
        return false;
    }
}
