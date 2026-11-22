<?php
/**
 * Kết nối CSDL PDO và các hàm dùng chung cho API TH8
 * Sinh viên: Phạm Trung Hiếu - MSSV: 23001876
 */

/**
 * Hàm trả về phản hồi JSON thống nhất
 */
function sendJsonResponse(bool $success, array $extra = [], int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode(array_merge(['success' => $success], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Kiểm tra và chuyển đổi số nguyên hợp lệ theo khoảng [min, max] của kiểu INT trong MySQL
 */
function validateIntInRange($value, int $min, int $max = 2147483647): ?int {
    if (is_bool($value) || is_float($value) || is_array($value) || is_object($value) || $value === null || $value === '') {
        return null;
    }

    if (is_string($value)) {
        $value = trim($value);
        if ($value === '' || preg_match('/^-?\d+$/', $value) !== 1) {
            return null;
        }
    }

    $filtered = filter_var($value, FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => $min,
            'max_range' => $max
        ]
    ]);

    return ($filtered === false) ? null : $filtered;
}

/**
 * Tạo kết nối PDO và tự động khởi tạo / nâng cấp bảng products nếu cần
 */
function getPdoConnection(): PDO {
    $host     = 'localhost';
    $dbname   = 'th8_products';
    $username = 'root';
    $password = '';
    $charset  = 'utf8mb4';

    try {
        $pdo = new PDO(
            "mysql:host={$host};charset={$charset}",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$dbname}`");

        // Kiểm tra bảng products đã tồn tại chưa
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'products'")->fetch();
        if (!$tableCheck) {
            $pdo->exec("
                CREATE TABLE `products` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(100) NOT NULL,
                    `price` INT NOT NULL CHECK (`price` > 0),
                    `stock` INT NOT NULL CHECK (`stock` >= 0),
                    `image` VARCHAR(255) NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            $pdo->exec("
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
                ('Hộp bút vải', 55000, 7, NULL)
            ");
        } else {
            // Nếu bảng đã tồn tại từ buổi trước nhưng chưa có cột image -> tự động bổ sung
            $colCheck = $pdo->query("SHOW COLUMNS FROM `products` LIKE 'image'")->fetch();
            if (!$colCheck) {
                $pdo->exec("ALTER TABLE `products` ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL");
            }
        }

        return $pdo;
    } catch (PDOException $e) {
        sendJsonResponse(false, [
            'message' => 'Không thể kết nối cơ sở dữ liệu MySQL: ' . $e->getMessage()
        ], 500);
    }
}
