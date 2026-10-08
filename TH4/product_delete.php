<?php
/**
 * Xóa sản phẩm - Shopping Cart Management System
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/model/product.php';

$pageTitle = 'Xóa sản phẩm';

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

// 1. Kiểm tra sản phẩm có tồn tại hay không bằng getProductById($id)
$product = getProductById($id);

if (!$product) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['error_msg'] = 'Sản phẩm không tồn tại.';
    header('Location: product_list.php');
    exit;
}

// 2. Nếu người dùng đã bấm nút [Xác nhận Xóa] (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $success = deleteProduct($id);

    if ($success) {
        $_SESSION['success_msg'] = 'Xóa sản phẩm thành công.';
    } else {
        $_SESSION['error_msg'] = 'Có lỗi xảy ra khi xóa sản phẩm khỏi cơ sở dữ liệu.';
    }

    header('Location: product_list.php');
    exit;
}

// 3. Hiển thị màn hình xác nhận trước khi xóa (GET request)
include __DIR__ . '/view/header.php';
?>

<div class="card confirm-box">
    <div class="icon-warning">⚠️</div>
    
    <h2 style="font-size: 1.4rem; color: #b91c1c; margin-bottom: 0.5rem;">
        Xác nhận xóa sản phẩm
    </h2>
    
    <p style="color: #64748b; margin-bottom: 1.25rem;">
        Bạn có chắc chắn muốn xóa sản phẩm này khỏi hệ thống không?
    </p>

    <!-- Thẻ chi tiết sản phẩm chuẩn bị xóa -->
    <div class="product-preview-card">
        <p><strong>Tên sản phẩm:</strong> <?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Giá niêm yết:</strong> <span class="price-text"><?php echo number_format($product['price'], 2, ',', '.'); ?> đ</span></p>
        <p><strong>Số lượng tồn:</strong> <?php echo (int)$product['quantity']; ?> sản phẩm</p>
    </div>

    <form action="product_delete.php" method="POST" style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
        <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
        <input type="hidden" name="confirm_delete" value="1">

        <a href="product_list.php" class="btn btn-secondary" style="padding: 0.6rem 1.5rem;">
            ✖️ Hủy
        </a>

        <button type="submit" class="btn btn-danger" style="padding: 0.6rem 1.5rem;">
            🗑️ Xóa sản phẩm
        </button>
    </form>
</div>

<?php include __DIR__ . '/view/footer.php'; ?>
