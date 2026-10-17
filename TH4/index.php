<?php
/**
 * Trang chủ - Shopping Cart Management System
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/model/product.php';

$pageTitle = 'Trang chủ';
$products = getAllProducts();
$totalProducts = count($products);
$totalQuantity = array_sum(array_column($products, 'quantity'));

include __DIR__ . '/view/header.php';
?>

<div class="card">
    <div class="page-header">
        <h2 class="page-title">Chào mừng đến với Hệ thống Quản lý Sản phẩm</h2>
        <span class="badge badge-in-stock">Hoạt động bình thường</span>
    </div>

    <p style="margin-bottom: 1.5rem; font-size: 1.05rem; color: #475569;">
        Hệ thống được xây dựng theo mô hình phân tách tầng độc lập (Common - Model - View - Page Controllers) 
        cung cấp đầy đủ các thao tác <strong>CRUD (Create - Read - Update - Delete)</strong> cho sản phẩm giỏ hàng.
    </p>

    <!-- Thống kê nhanh -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.875rem; color: #1e40af; font-weight: 600;">Tổng số mặt hàng</div>
            <div style="font-size: 2rem; font-weight: 700; color: #1e3a8a;"><?php echo $totalProducts; ?></div>
        </div>
        <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.875rem; color: #166534; font-weight: 600;">Tổng tồn kho</div>
            <div style="font-size: 2rem; font-weight: 700; color: #14532d;"><?php echo $totalQuantity; ?> <span style="font-size: 1rem; font-weight: normal;">sản phẩm</span></div>
        </div>
        <div style="background: #fefce8; border-left: 4px solid #f59e0b; padding: 1.25rem; border-radius: 6px;">
            <div style="font-size: 0.875rem; color: #854d0e; font-weight: 600;">Cơ sở dữ liệu</div>
            <div style="font-size: 1.25rem; font-weight: 700; color: #713f12; margin-top: 0.5rem;">shopping_cart.products</div>
        </div>
    </div>

    <!-- Phím tắt điều hướng -->
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <?php if ($isLoggedIn): ?>
            <a href="product_list.php" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 1rem;">
                📋 Xem danh sách sản phẩm
            </a>
            <a href="product_add.php" class="btn btn-success" style="padding: 0.75rem 1.5rem; font-size: 1rem;">
                ➕ Thêm sản phẩm mới
            </a>
            <a href="logout.php" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; font-size: 1rem;" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                🚪 Đăng xuất
            </a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 1rem;">
                🔐 Đăng nhập hệ thống
            </a>
            <a href="register.php" class="btn btn-success" style="padding: 0.75rem 1.5rem; font-size: 1rem;">
                📝 Đăng ký tài khoản
            </a>
            <a href="product_list.php" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; font-size: 1rem;">
                📋 Danh sách sản phẩm (Cần đăng nhập)
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem; color: #334155;">✨ Các chức năng chính của hệ thống:</h3>
    <ul style="margin-left: 1.5rem; line-height: 1.8; color: #475569;">
        <li><strong>Xác thực &amp; Bảo mật (Auth):</strong> Đăng ký, Đăng nhập và Đăng xuất với mật khẩu băm an toàn <code>password_hash()</code>, kiểm soát truy cập trang quản trị qua <code>$_SESSION</code>.</li>
        <li><strong>Xem danh sách (Read):</strong> Hiển thị bảng sản phẩm trực quan, phân loại tồn kho, định dạng tiền tệ chuẩn VNĐ.</li>
        <li><strong>Thêm sản phẩm (Create):</strong> Form kiểm tra tính hợp lệ dữ liệu (tên không rỗng, giá &gt; 0, số lượng &ge; 0).</li>
        <li><strong>Chỉnh sửa sản phẩm (Update):</strong> Nạp dữ liệu cũ theo ID và cập nhật an toàn với Prepared Statement.</li>
        <li><strong>Xóa sản phẩm (Delete):</strong> Xác thực sự tồn tại và yêu cầu xác nhận an toàn trước khi xóa để tránh mất mát dữ liệu.</li>
    </ul>
</div>

<?php include __DIR__ . '/view/footer.php'; ?>
