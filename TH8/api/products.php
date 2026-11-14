<?php
/**
 * Câu 2: Xây dựng API bằng PHP (api/products.php)
 * Thực hành PHP và Vue.js - TH8
 * Sinh viên: Phạm Trung Hiếu - MSSV: 23001876
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host     = 'localhost';
$dbname   = 'th8_products';
$username = 'root';
$password = '';
$charset  = 'utf8mb4';

/**
 * Hàm trả về phản hồi JSON thống nhất
 */
function sendJsonResponse(bool $success, array $extra = [], int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode(array_merge(['success' => $success], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Kiểm tra giá trị có phải là số nguyên hợp lệ hay không
 */
function isValidInteger($value): bool {
    if (is_bool($value) || is_array($value) || is_object($value) || $value === null || $value === '') {
        return false;
    }
    if (is_int($value)) {
        return true;
    }
    if (is_float($value)) {
        return floor($value) === $value;
    }
    if (is_string($value)) {
        return preg_match('/^-?\d+$/', trim($value)) === 1;
    }
    return false;
}

/**
 * Kiểm tra dữ liệu đầu vào khi thêm hoặc sửa sản phẩm
 */
function validateProductInput(?array $input): array {
    if (!is_array($input)) {
        return [
            'valid'   => false,
            'message' => 'Dữ liệu gửi lên không đúng định dạng JSON.'
        ];
    }

    $rawName  = isset($input['name']) && is_string($input['name']) ? $input['name'] : '';
    $name     = trim($rawName);
    $nameLen  = function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') : strlen($name);

    if ($nameLen < 1 || $nameLen > 100) {
        return [
            'valid'   => false,
            'message' => 'Tên sản phẩm sau khi bỏ khoảng trắng hai đầu phải có từ 1 đến 100 ký tự.'
        ];
    }

    if (!array_key_exists('price', $input) || !isValidInteger($input['price']) || (int)$input['price'] <= 0) {
        return [
            'valid'   => false,
            'message' => 'Đơn giá phải là số nguyên lớn hơn 0.'
        ];
    }

    if (!array_key_exists('stock', $input) || !isValidInteger($input['stock']) || (int)$input['stock'] < 0) {
        return [
            'valid'   => false,
            'message' => 'Số lượng tồn phải là số nguyên không âm (từ 0 trở lên).'
        ];
    }

    return [
        'valid' => true,
        'data'  => [
            'name'  => $name,
            'price' => (int)$input['price'],
            'stock' => (int)$input['stock']
        ]
    ];
}

// Kết nối MySQL bằng PDO (tự động khởi tạo CSDL & bảng mẫu nếu chưa có)
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

    // Kiểm tra bảng products đã tồn tại chưa, nếu chưa thì tạo bảng và thêm 3 dòng mẫu
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'products'")->fetch();
    if (!$tableCheck) {
        $pdo->exec("
            CREATE TABLE `products` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `price` INT NOT NULL,
                `stock` INT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $pdo->exec("
            INSERT INTO `products` (`name`, `price`, `stock`) VALUES
            ('Bút bi', 5000, 20),
            ('Vở ô ly', 12000, 15),
            ('Thước kẻ', 8000, 0)
        ");
    }
} catch (PDOException $e) {
    sendJsonResponse(false, [
        'message' => 'Không thể kết nối cơ sở dữ liệu MySQL: ' . $e->getMessage()
    ], 500);
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    // 1. GET api/products.php -> Trả về danh sách sản phẩm
    if ($method === 'GET') {
        $stmt = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id ASC');
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['id']    = (int)$row['id'];
            $row['price'] = (int)$row['price'];
            $row['stock'] = (int)$row['stock'];
        }

        sendJsonResponse(true, ['data' => $rows]);
    }

    // 2. POST api/products.php -> Thêm sản phẩm mới
    if ($method === 'POST') {
        $rawBody = file_get_contents('php://input');
        $input   = json_decode($rawBody, true);

        $validation = validateProductInput($input);
        if (!$validation['valid']) {
            sendJsonResponse(false, ['message' => $validation['message']], 400);
        }

        $data = $validation['data'];

        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $stmt->execute([
            ':name'  => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock']
        ]);

        sendJsonResponse(true, [
            'message' => 'Thêm sản phẩm mới thành công.',
            'id'      => (int)$pdo->lastInsertId()
        ], 201);
    }

    // 3. PUT api/products.php?id=1 -> Cập nhật sản phẩm theo id trên URL
    if ($method === 'PUT') {
        $idParam = $_GET['id'] ?? null;
        if (!isValidInteger($idParam) || (int)$idParam <= 0) {
            sendJsonResponse(false, ['message' => 'Mã sản phẩm (id) trên URL không hợp lệ.'], 400);
        }
        $id = (int)$idParam;

        // Kiểm tra dữ liệu JSON gửi lên
        $rawBody    = file_get_contents('php://input');
        $input      = json_decode($rawBody, true);
        $validation = validateProductInput($input);
        if (!$validation['valid']) {
            sendJsonResponse(false, ['message' => $validation['message']], 400);
        }

        // Kiểm tra sản phẩm có tồn tại không bằng PDO prepared statement
        $checkStmt = $pdo->prepare('SELECT id FROM products WHERE id = :id');
        $checkStmt->execute([':id' => $id]);
        if (!$checkStmt->fetch()) {
            sendJsonResponse(false, ['message' => "Không tìm thấy sản phẩm có mã #{$id} để cập nhật."], 404);
        }

        $data = $validation['data'];
        $updateStmt = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $updateStmt->execute([
            ':name'  => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':id'    => $id
        ]);

        sendJsonResponse(true, ['message' => "Cập nhật sản phẩm #{$id} thành công."]);
    }

    // 4. DELETE api/products.php?id=1 -> Xóa sản phẩm theo id trên URL
    if ($method === 'DELETE') {
        $idParam = $_GET['id'] ?? null;
        if (!isValidInteger($idParam) || (int)$idParam <= 0) {
            sendJsonResponse(false, ['message' => 'Mã sản phẩm (id) trên URL không hợp lệ.'], 400);
        }
        $id = (int)$idParam;

        // Kiểm tra sản phẩm có tồn tại không bằng PDO prepared statement
        $checkStmt = $pdo->prepare('SELECT id FROM products WHERE id = :id');
        $checkStmt->execute([':id' => $id]);
        if (!$checkStmt->fetch()) {
            sendJsonResponse(false, ['message' => "Không tìm thấy sản phẩm có mã #{$id} để xóa."], 404);
        }

        $deleteStmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $deleteStmt->execute([':id' => $id]);

        sendJsonResponse(true, ['message' => "Đã xóa sản phẩm #{$id} thành công."]);
    }

    // Phương thức không được hỗ trợ
    sendJsonResponse(false, ['message' => 'Phương thức HTTP không được hỗ trợ.'], 405);
} catch (PDOException $e) {
    sendJsonResponse(false, ['message' => 'Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage()], 500);
}
