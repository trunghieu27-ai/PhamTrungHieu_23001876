-- Script tạo database và bảng cho Hệ thống Quản lý Sản phẩm (TH4)
-- Dự án: Shopping Cart - Product Management System
-- Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)

CREATE DATABASE IF NOT EXISTS `shopping_cart` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `shopping_cart`;

-- Tạo bảng products
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `quantity` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu (tối thiểu 5 sản phẩm theo đặc tả)
INSERT INTO `products` (`id`, `name`, `price`, `quantity`) VALUES
(1, 'Laptop Dell', 15000000.00, 10),
(2, 'Chuột Logitech', 500000.00, 25),
(3, 'Bàn phím cơ', 1200000.00, 15),
(4, 'Tai nghe Bluetooth', 850000.00, 20),
(5, 'Màn hình Samsung', 4500000.00, 8)
ON DUPLICATE KEY UPDATE 
    `name` = VALUES(`name`), 
    `price` = VALUES(`price`), 
    `quantity` = VALUES(`quantity`);

-- Tạo bảng users (chức năng Đăng nhập - Đăng xuất)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm tài khoản mẫu (admin / mật khẩu: 123456 đã được mã hóa bằng password_hash)
INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$KR/9r.Y/6JoWg6MHYRgPAOyhUiolGkJq/F43d4x6nRpO79Eq.flEy')
ON DUPLICATE KEY UPDATE 
    `password` = VALUES(`password`);

