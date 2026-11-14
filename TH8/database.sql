-- Câu 1: Tạo cơ sở dữ liệu và bảng products (TH8 - Thực hành PHP và Vue.js)
-- Sinh viên: Phạm Trung Hiếu - MSSV: 23001876

CREATE DATABASE IF NOT EXISTS `th8_products`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `th8_products`;

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `price` INT NOT NULL CHECK (`price` > 0),
    `stock` INT NOT NULL CHECK (`stock` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm 3 dòng dữ liệu mẫu theo đề bài
INSERT INTO `products` (`name`, `price`, `stock`) VALUES
('Bút bi', 5000, 20),
('Vở ô ly', 12000, 15),
('Thước kẻ', 8000, 0);
