<?php
/**
 * Trang Đăng nhập hệ thống - Shopping Cart Management System
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/common/auth.php';
require_once __DIR__ . '/model/user.php';

// Nếu người dùng đã đăng nhập thì chuyển thẳng vào trang danh sách sản phẩm
redirectIfLoggedIn();

$pageTitle = 'Đăng nhập';
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        // 1. Kiểm tra thông tin đăng nhập với dữ liệu trong bảng users
        $user = findUserByUsername($username);

        // 2. Sử dụng password_verify() để kiểm tra mật khẩu
        if ($user && password_verify($password, $user['password'])) {
            // 3. Nếu đăng nhập thành công, lưu thông tin cần thiết vào $_SESSION
            $_SESSION['user'] = [
                'id'       => $user['id'],
                'username' => $user['username']
            ];
            $_SESSION['success_msg'] = 'Đăng nhập thành công! Chào mừng ' . htmlspecialchars($user['username']) . ' trở lại.';

            // Chuyển hướng đến product_list.php
            header('Location: product_list.php');
            exit;
        } else {
            // 4. Nếu thông tin không chính xác, hiển thị thông báo lỗi phù hợp
            $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
        }
    }
}

include __DIR__ . '/view/header.php';
?>

<div class="card" style="max-width: 480px; margin: 2rem auto;">
    <div class="page-header" style="justify-content: center; text-align: center; margin-bottom: 1.5rem;">
        <h2 class="page-title" style="font-size: 1.6rem;">🔐 Đăng nhập hệ thống</h2>
    </div>

    <!-- Hiển thị thông báo thành công (Flash message từ Đăng ký hoặc Đăng xuất) -->
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success">
            ✅ <?php echo htmlspecialchars($_SESSION['success_msg']); ?>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <!-- Hiển thị thông báo nhắc đăng nhập (Flash message từ kiểm soát truy cập) -->
    <?php if (isset($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger">
            ⚠️ <?php echo htmlspecialchars($_SESSION['error_msg']); ?>
        </div>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>

    <!-- Hiển thị lỗi đăng nhập sai -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            ❌ <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" novalidate>
        <div class="form-group">
            <label for="username" class="form-label">Tên đăng nhập:</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                class="form-control" 
                placeholder="Nhập tên đăng nhập (ví dụ: admin)"
                value="<?php echo htmlspecialchars($username); ?>" 
                required 
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-control" 
                placeholder="Nhập mật khẩu"
                required
            >
        </div>

        <div style="margin-top: 1.75rem;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 1rem; font-weight: 600;">
                🚀 Đăng nhập
            </button>
        </div>

        <!-- Hộp gợi ý tài khoản mẫu theo đề thực hành -->
        <div style="background: #f1f5f9; border-radius: var(--radius-md); padding: 0.85rem 1rem; margin-top: 1.25rem; font-size: 0.85rem; color: #475569;">
            💡 <strong>Tài khoản mẫu:</strong><br>
            • Tên đăng nhập: <code>admin</code><br>
            • Mật khẩu: <code>123456</code>
        </div>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color); color: var(--text-muted);">
            Chưa có tài khoản? <a href="register.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Đăng ký tài khoản mới &rarr;</a>
        </div>
    </form>
</div>

<?php
include __DIR__ . '/view/footer.php';
?>
