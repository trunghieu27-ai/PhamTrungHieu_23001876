<?php
/**
 * Danh sách sản phẩm - Shopping Cart Management System
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/common/auth.php';
requireLogin();

require_once __DIR__ . '/model/product.php';

$pageTitle = 'Danh sách sản phẩm';

// Lấy danh sách sản phẩm từ CSDL
$products = getAllProducts();

include __DIR__ . '/view/header.php';
?>

<div class="card">
    <div class="page-header">
        <h2 class="page-title">Danh sách sản phẩm</h2>
        <a href="product_add.php" class="btn btn-success">
            ➕ Thêm sản phẩm
        </a>
    </div>

    <!-- Hiển thị thông báo (Flash Message) nếu có -->
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success">
            ✅ <?php echo htmlspecialchars($_SESSION['success_msg']); ?>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger">
            ❌ <?php echo htmlspecialchars($_SESSION['error_msg']); ?>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 2.5rem; color: #64748b;">
            <p style="font-size: 1.1rem; margin-bottom: 1rem;">Hiện tại chưa có sản phẩm nào trong hệ thống.</p>
            <a href="product_add.php" class="btn btn-primary">Thêm sản phẩm đầu tiên</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th style="width: 180px;" class="text-right">Giá</th>
                        <th style="width: 140px;" class="text-center">Số lượng</th>
                        <th style="width: 170px;" class="text-center">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $prod): ?>
                        <tr>
                            <td style="font-weight: 600; color: #1e293b;">
                                <?php echo htmlspecialchars($prod['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="text-right price-text">
                                <?php echo number_format($prod['price'], 2, ',', '.'); ?> đ
                            </td>
                            <td class="text-center">
                                <?php if ($prod['quantity'] > 10): ?>
                                    <span class="badge badge-in-stock"><?php echo (int)$prod['quantity']; ?> cái</span>
                                <?php elseif ($prod['quantity'] > 0): ?>
                                    <span class="badge badge-low-stock"><?php echo (int)$prod['quantity']; ?> cái</span>
                                <?php else: ?>
                                    <span class="badge badge-out-stock">Hết hàng</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="product_edit.php?id=<?php echo (int)$prod['id']; ?>" class="btn btn-warning btn-sm" title="Chỉnh sửa sản phẩm">
                                    ✏️ Sửa
                                </a>
                                <a href="product_delete.php?id=<?php echo (int)$prod['id']; ?>" class="btn btn-danger btn-sm" title="Xóa sản phẩm">
                                    🗑️ Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem; color: #64748b; font-size: 0.9rem;">
            Tổng cộng: <strong><?php echo count($products); ?></strong> sản phẩm trong danh mục.
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/view/footer.php'; ?>
