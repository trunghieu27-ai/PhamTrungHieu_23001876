<?php
/**
 * Câu 1: API Upload ảnh sản phẩm (POST api/upload.php)
 * Nhận multipart/form-data gồm file ảnh (image) và mã sản phẩm (product_id hoặc id)
 * Thực hành PHP và Vue.js - TH8
 * Sinh viên: Phạm Trung Hiếu - MSSV: 23001876
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/db.php';

if ($method !== 'POST') {
    sendJsonResponse(false, ['message' => 'Chỉ hỗ trợ phương thức POST cho API upload ảnh.'], 405);
}

// 1. Kiểm tra mã sản phẩm (chấp nhận product_id hoặc id từ POST)
$rawProductId = $_POST['product_id'] ?? $_POST['id'] ?? null;
$productId    = validateIntInRange($rawProductId, 1);

if ($productId === null) {
    sendJsonResponse(false, ['message' => 'Mã sản phẩm không hợp lệ.'], 400);
}

// 2. Kiểm tra file gửi lên
if (!isset($_FILES['image'])) {
    sendJsonResponse(false, ['message' => 'Vui lòng chọn file ảnh để tải lên.'], 400);
}

$file = $_FILES['image'];

if (!isset($file['error']) || is_array($file['error'])) {
    sendJsonResponse(false, ['message' => 'Dữ liệu file tải lên không hợp lệ.'], 400);
}

// Kiểm tra mã lỗi upload của PHP
switch ($file['error']) {
    case UPLOAD_ERR_OK:
        break;
    case UPLOAD_ERR_NO_FILE:
        sendJsonResponse(false, ['message' => 'Không có file ảnh nào được gửi lên.'], 400);
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
        sendJsonResponse(false, ['message' => 'Dung lượng ảnh vượt quá giới hạn tối đa 2 MB.'], 400);
    default:
        sendJsonResponse(false, ['message' => 'Đã xảy ra lỗi khi tải file lên máy chủ.'], 400);
}

// 3. Kiểm tra dung lượng tối đa 2 MB (2 * 1024 * 1024 bytes)
$maxSize = 2 * 1024 * 1024;
$tmpPath = $file['tmp_name'];

if (!is_uploaded_file($tmpPath)) {
    sendJsonResponse(false, ['message' => 'File tải lên không hợp lệ.'], 400);
}

$actualSize = filesize($tmpPath);
if ($actualSize === false || $actualSize <= 0) {
    sendJsonResponse(false, ['message' => 'File ảnh rỗng hoặc không đọc được.'], 400);
}

if ($file['size'] > $maxSize || $actualSize > $maxSize) {
    sendJsonResponse(false, ['message' => 'Dung lượng ảnh vượt quá giới hạn tối đa 2 MB.'], 400);
}

// 4. Kiểm tra phần mở rộng file gốc
$originalExt = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png'];
if (!in_array($originalExt, $allowedExtensions, true)) {
    sendJsonResponse(false, ['message' => 'Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG.'], 400);
}

// 5. Kiểm tra loại ảnh thực tế (MIME type thực tế + cấu trúc ảnh)
$realMime = null;
if (function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo !== false) {
        $realMime = finfo_file($finfo, $tmpPath);
        finfo_close($finfo);
    }
}

$imageInfo = @getimagesize($tmpPath);
if ($imageInfo === false || empty($imageInfo[2])) {
    sendJsonResponse(false, ['message' => 'File tải lên không phải là ảnh hợp lệ (chỉ chấp nhận JPG, JPEG, PNG).'], 400);
}

$imageType = $imageInfo[2];
$allowedMimeMap = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png'
];

// Đối chiếu cả IMAGETYPE và finfo MIME thực tế
if ($imageType === IMAGETYPE_JPEG && ($realMime === null || $realMime === 'image/jpeg')) {
    $safeExt = ($originalExt === 'jpeg') ? 'jpeg' : 'jpg';
} elseif ($imageType === IMAGETYPE_PNG && ($realMime === null || $realMime === 'image/png')) {
    $safeExt = 'png';
} else {
    sendJsonResponse(false, ['message' => 'Loại ảnh thực tế không hợp lệ. Chỉ chấp nhận JPG, JPEG hoặc PNG.'], 400);
}

// 6. Kiểm tra sản phẩm có tồn tại trong CSDL không (PDO prepared statement)
$pdo = getPdoConnection();

try {
    $checkStmt = $pdo->prepare('SELECT id, image FROM products WHERE id = :id');
    $checkStmt->execute([':id' => $productId]);
    $product = $checkStmt->fetch();

    if (!$product) {
        sendJsonResponse(false, ['message' => "Không tìm thấy sản phẩm có mã #{$productId}."], 404);
    }

    // 7. Đảm bảo thư mục uploads tồn tại và tạo tên file duy nhất
    $uploadsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads';
    if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true)) {
        sendJsonResponse(false, ['message' => 'Không thể tạo thư mục lưu ảnh uploads.'], 500);
    }

    $uniqueToken = bin2hex(random_bytes(8));
    $fileName    = "product_{$productId}_{$uniqueToken}.{$safeExt}";
    $destPath    = $uploadsDir . DIRECTORY_SEPARATOR . $fileName;
    $relativeUrl = 'uploads/' . $fileName;

    if (!move_uploaded_file($tmpPath, $destPath)) {
        sendJsonResponse(false, ['message' => 'Không thể lưu file ảnh vào thư mục uploads.'], 500);
    }

    // 8. Cập nhật đường dẫn ảnh vào cột image bằng PDO prepared statement
    try {
        $updateStmt = $pdo->prepare('UPDATE products SET image = :image WHERE id = :id');
        $updateStmt->execute([
            ':image' => $relativeUrl,
            ':id'    => $productId
        ]);
    } catch (PDOException $dbErr) {
        // Nếu lưu DB lỗi thì xóa file vừa tạo để giữ nguyên trạng thái cũ
        @unlink($destPath);
        throw $dbErr;
    }

    // Xóa ảnh cũ trên đĩa sau khi đã cập nhật ảnh mới thành công
    if (!empty($product['image']) && $product['image'] !== $relativeUrl) {
        $oldFilePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $product['image']);
        $realUploads = realpath($uploadsDir);
        $realOldFile = realpath($oldFilePath);
        if ($realUploads && $realOldFile && strpos($realOldFile, $realUploads) === 0 && is_file($realOldFile)) {
            @unlink($realOldFile);
        }
    }

    sendJsonResponse(true, [
        'message' => 'Upload ảnh sản phẩm thành công.',
        'image'   => $relativeUrl,
        'id'      => $productId
    ]);
} catch (PDOException $e) {
    sendJsonResponse(false, ['message' => 'Lỗi cơ sở dữ liệu khi cập nhật ảnh: ' . $e->getMessage()], 500);
} catch (Throwable $e) {
    sendJsonResponse(false, ['message' => 'Lỗi hệ thống khi xử lý ảnh: ' . $e->getMessage()], 500);
}
