-- Câu 1 & Câu 2: Cơ sở dữ liệu quản lý sản phẩm có cột ảnh (image) và 12 sản phẩm mẫu
-- Thực hành PHP và Vue.js - TH8 (Upload ảnh & Phân trang)
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
    `stock` INT NOT NULL CHECK (`stock` >= 0),
    `image` VARCHAR(255) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12 dòng dữ liệu mẫu để phục vụ phân trang (5 sản phẩm / trang => 3 trang: 5, 5, 2)
INSERT INTO `products` (`name`, `price`, `stock`, `image`) VALUES
('Bút bi', 5000, 20, NULL),
('Vở ô ly', 12000, 15, NULL),
('Thước kẻ', 8000, 0, NULL),
('Bút chì 2B', 4000, 35, NULL),
('Gôm tẩy trắng', 6000, 25, NULL),
('Bút dạ quang', 15000, 18, NULL),
('Sổ tay bìa da A5', 45000, 10, NULL),
('Kéo văn phòng', 22000, 12, NULL),
('Bấm kim số 10', 28000, 9, NULL),
('Bìa hồ sơ nút', 7000, 40, NULL),
('Giấy note 5 màu', 14000, 30, NULL),
('Hộp bút vải', 55000, 7, NULL);
