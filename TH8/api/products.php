<?php
/**
 * API Quản lý Sản phẩm (CRUD + Phân trang Backend)
 * Đường dẫn: api/products.php
 * Thực hành PHP và Vue.js - TH8
 * Sinh viên: Phạm Trung Hiếu - MSSV: 23001876
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/db.php';

/**
 * Đếm số ký tự Unicode UTF-8 chính xác kể cả khi máy chưa bật mbstring
 */
function getUtf8Length(string $str): int {
    if (function_exists('mb_strlen')) {
        return mb_strlen($str, 'UTF-8');
    }
    $count = preg_match_all('/./us', $str);
    return $count === false ? strlen($str) : $count;
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

    if (!isset($input['name']) || !is_string($input['name'])) {
        return [
            'valid'   => false,
            'message' => 'Tên sản phẩm bắt buộc nhập và phải là chuỗi ký tự.'
        ];
    }

    $name    = trim($input['name']);
    $nameLen = getUtf8Length($name);

    if ($nameLen < 1 || $nameLen > 100) {
        return [
            'valid'   => false,
            'message' => 'Tên sản phẩm sau khi bỏ khoảng trắng hai đầu phải có từ 1 đến 100 ký tự.'
        ];
    }

    $price = array_key_exists('price', $input) ? validateIntInRange($input['price'], 1) : null;
    if ($price === null) {
        return [
            'valid'   => false,
            'message' => 'Đơn giá phải là số nguyên lớn hơn 0.'
        ];
    }

    $stock = array_key_exists('stock', $input) ? validateIntInRange($input['stock'], 0) : null;
    if ($stock === null) {
        return [
            'valid'   => false,
            'message' => 'Số lượng tồn phải là số nguyên không âm (từ 0 trở lên).'
        ];
    }

    return [
        'valid' => true,
        'data'  => [
            'name'  => $name,
            'price' => $price,
            'stock' => $stock
        ]
    ];
}

$pdo = getPdoConnection();

try {
    // 1. GET api/products.php?page=1&limit=5 -> Trả về danh sách sản phẩm có phân trang
    if ($method === 'GET') {
        // Kiểm tra tham số page (mặc định 1 nếu không truyền; nếu có truyền phải là số nguyên dương)
        if (array_key_exists('page', $_GET)) {
            $page = validateIntInRange($_GET['page'], 1);
            if ($page === null) {
                sendJsonResponse(false, [
                    'message' => 'Tham số page phải là số nguyên dương.'
                ], 400);
            }
        } else {
            $page = 1;
        }

        // Kiểm tra tham số limit (mặc định 5 nếu không truyền; nếu có truyền phải bằng 5)
        if (array_key_exists('limit', $_GET)) {
            $limit = validateIntInRange($_GET['limit'], 1);
            if ($limit === null || $limit !== 5) {
                sendJsonResponse(false, [
                    'message' => 'Tham số limit không hợp lệ (limit phải bằng 5).'
                ], 400);
            }
        } else {
            $limit = 5;
        }

        // Đếm tổng số sản phẩm
        $total = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $totalPages = max(1, (int)ceil($total / $limit));

        // Nếu page vượt quá trang cuối -> trả về trang cuối cùng và page thực tế
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $limit;

        // Sắp xếp theo id giảm dần và dùng LIMIT, OFFSET bằng PDO prepared statement
        $stmt = $pdo->prepare('SELECT id, name, price, stock, image FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['id']    = (int)$row['id'];
            $row['name']  = (string)$row['name'];
            $row['price'] = (int)$row['price'];
            $row['stock'] = (int)$row['stock'];
            $row['image'] = ($row['image'] !== null && $row['image'] !== '') ? (string)$row['image'] : null;
        }

        sendJsonResponse(true, [
            'data'       => $rows,
            'pagination' => [
                'page'        => $page,
                'limit'       => $limit,
                'total'       => $total,
                'total_pages' => $totalPages
            ]
        ]);
    }

    // 2. POST api/products.php -> Thêm sản phẩm mới (trả về mã sản phẩm mới để Vue upload ảnh)
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

        $newId = (int)$pdo->lastInsertId();

        sendJsonResponse(true, [
            'message' => 'Thêm sản phẩm mới thành công.',
            'id'      => $newId,
            'data'    => [
                'id'    => $newId,
                'name'  => $data['name'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'image' => null
            ]
        ], 201);
    }

    // 3. PUT api/products.php?id=1 -> Cập nhật thông tin sản phẩm theo id (giữ nguyên cột image hiện tại)
    if ($method === 'PUT') {
        $id = validateIntInRange($_GET['id'] ?? null, 1);
        if ($id === null) {
            sendJsonResponse(false, ['message' => 'Mã sản phẩm (id) trên URL không hợp lệ.'], 400);
        }

        // Kiểm tra sản phẩm có tồn tại không bằng PDO prepared statement
        $checkStmt = $pdo->prepare('SELECT id, image FROM products WHERE id = :id');
        $checkStmt->execute([':id' => $id]);
        $existing = $checkStmt->fetch();
        if (!$existing) {
            sendJsonResponse(false, ['message' => "Không tìm thấy sản phẩm có mã #{$id} để cập nhật."], 404);
        }

        // Kiểm tra dữ liệu JSON gửi lên
        $rawBody    = file_get_contents('php://input');
        $input      = json_decode($rawBody, true);
        $validation = validateProductInput($input);
        if (!$validation['valid']) {
            sendJsonResponse(false, ['message' => $validation['message']], 400);
        }

        $data = $validation['data'];
        $updateStmt = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $updateStmt->execute([
            ':name'  => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':id'    => $id
        ]);

        sendJsonResponse(true, [
            'message' => "Cập nhật sản phẩm #{$id} thành công.",
            'id'      => $id
        ]);
    }

    // 4. DELETE api/products.php?id=1 -> Xóa sản phẩm theo id trên URL
    if ($method === 'DELETE') {
        $id = validateIntInRange($_GET['id'] ?? null, 1);
        if ($id === null) {
            sendJsonResponse(false, ['message' => 'Mã sản phẩm (id) trên URL không hợp lệ.'], 400);
        }

        // Kiểm tra sản phẩm có tồn tại không bằng PDO prepared statement
        $checkStmt = $pdo->prepare('SELECT id, image FROM products WHERE id = :id');
        $checkStmt->execute([':id' => $id]);
        $existing = $checkStmt->fetch();
        if (!$existing) {
            sendJsonResponse(false, ['message' => "Không tìm thấy sản phẩm có mã #{$id} để xóa."], 404);
        }

        $deleteStmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $deleteStmt->execute([':id' => $id]);

        // Dọn dẹp file ảnh trong thư mục uploads nếu có
        if (!empty($existing['image'])) {
            $oldFilePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $existing['image']);
            $uploadsDir  = realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads');
            $realOldFile = realpath($oldFilePath);
            if ($uploadsDir && $realOldFile && strpos($realOldFile, $uploadsDir) === 0 && is_file($realOldFile)) {
                @unlink($realOldFile);
            }
        }

        sendJsonResponse(true, ['message' => "Đã xóa sản phẩm #{$id} thành công."]);
    }

    // Phương thức không được hỗ trợ
    sendJsonResponse(false, ['message' => 'Phương thức HTTP không được hỗ trợ.'], 405);
} catch (PDOException $e) {
    sendJsonResponse(false, ['message' => 'Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage()], 500);
}
