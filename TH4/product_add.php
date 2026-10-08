<?php
/**
 * Thêm sản phẩm mới - Shopping Cart Management System
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/model/product.php';

$pageTitle = 'Thêm sản phẩm mới';

$errors = [];
$name = '';
$price = '';
$quantity = '';

// Xử lý khi người dùng submit form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';

    // 1. Kiểm tra Tên sản phẩm
    if ($name === '') {
        $errors[] = 'Tên sản phẩm không được để trống.';
    }

    // 2. Kiểm tra Giá sản phẩm
    if ($price === '' || !is_numeric($price) || (float)$price <= 0) {
        $errors[] = 'Giá sản phẩm phải lớn hơn 0.';
    }

    // 3. Kiểm tra Số lượng sản phẩm
    if ($quantity === '' || !is_numeric($quantity) || (int)$quantity < 0 || (int)$quantity != $quantity) {
        $errors[] = 'Số lượng phải lớn hơn hoặc bằng 0.';
    }

    // Nếu không có lỗi, tiến hành lưu vào cơ sở dữ liệu
    if (empty($errors)) {
        $success = addProduct($name, (float)$price, (int)$quantity);

        if ($success) {
            $_SESSION['success_msg'] = 'Thêm sản phẩm thành công.';
            header('Location: product_list.php');
            exit;
        } else {
            $errors[] = 'Có lỗi xảy ra khi lưu sản phẩm vào cơ sở dữ liệu.';
        }
    }
}

include __DIR__ . '/view/header.php';
?>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="page-header">
        <h2 class="page-title">Thêm sản phẩm mới</h2>
        <a href="product_list.php" class="btn btn-secondary btn-sm">⬅️ Quay lại danh sách</a>
    </div>

    <!-- Hiển thị lỗi kiểm tra dữ liệu nếu có -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Vui lòng kiểm tra lại các thông tin sau:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="product_add.php" method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Tên sản phẩm <span style="color: #ef4444;">*</span></label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?php echo htmlspecialchars($name); ?>" 
                   placeholder="Nhập tên sản phẩm (vd: Laptop Dell, Bàn phím cơ...)" required>
            <div class="form-hint">Tên sản phẩm không được để trống.</div>
        </div>

        <div class="form-group">
            <label for="price" class="form-label">Giá sản phẩm (VNĐ) <span style="color: #ef4444;">*</span></label>
            <input type="number" step="0.01" min="0.01" id="price" name="price" class="form-control" 
                   value="<?php echo htmlspecialchars($price); ?>" 
                   placeholder="Nhập giá bán (vd: 15000000)" required>
            <div class="form-hint">Giá sản phẩm phải lớn hơn 0.</div>
        </div>

        <div class="form-group">
            <label for="quantity" class="form-label">Số lượng tồn kho <span style="color: #ef4444;">*</span></label>
            <input type="number" min="0" step="1" id="quantity" name="quantity" class="form-control" 
                   value="<?php echo htmlspecialchars($quantity); ?>" 
                   placeholder="Nhập số lượng (vd: 10)" required>
            <div class="form-hint">Số lượng phải lớn hơn hoặc bằng 0.</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                💾 Thêm sản phẩm
            </button>
            <a href="product_list.php" class="btn btn-secondary">
                Hủy bỏ
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/view/footer.php'; ?>
