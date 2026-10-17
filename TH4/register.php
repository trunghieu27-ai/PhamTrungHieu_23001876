<?php
/**
 * Trang Đăng ký tài khoản mới - Shopping Cart Management System
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */

require_once __DIR__ . '/common/auth.php';
require_once __DIR__ . '/model/user.php';

// Nếu người dùng đã đăng nhập thì chuyển thẳng vào trang danh sách sản phẩm
redirectIfLoggedIn();

$pageTitle = 'Đăng ký tài khoản';
$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username        = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password        = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // 1. Kiểm tra các trường không được để trống
    if ($username === '') {
        $errors[] = 'Tên đăng nhập không được để trống.';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới (_).';
    }

    if ($password === '') {
        $errors[] = 'Mật khẩu không được để trống.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Mật khẩu phải có độ dài tối thiểu 6 ký tự.';
    }

    if ($password !== '' && $password !== $confirmPassword) {
        $errors[] = 'Mật khẩu nhập lại không trùng khớp.';
    }

    // 2. Kiểm tra tên đăng nhập chưa tồn tại trong database
    if (empty($errors)) {
        if (checkUsernameExists($username)) {
            $errors[] = 'Tên đăng nhập "' . htmlspecialchars($username) . '" đã tồn tại. Vui lòng chọn tên khác.';
        }
    }

    // 3. Nếu hợp lệ -> Mã hóa mật khẩu bằng password_hash() và lưu vào database
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = createUser($username, $hashedPassword);

        if ($result) {
            $_SESSION['success_msg'] = 'Đăng ký tài khoản "' . htmlspecialchars($username) . '" thành công! Vui lòng đăng nhập.';
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Có lỗi xảy ra khi lưu tài khoản vào cơ sở dữ liệu. Vui lòng thử lại!';
        }
    }
}

include __DIR__ . '/view/header.php';
?>

<div class="card" style="max-width: 520px; margin: 2rem auto;">
    <div class="page-header" style="justify-content: center; text-align: center; margin-bottom: 1.5rem;">
        <h2 class="page-title" style="font-size: 1.6rem;">📝 Đăng ký tài khoản</h2>
    </div>

    <!-- Hiển thị danh sách lỗi nếu có -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin-left: 1.25rem;">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" novalidate>
        <div class="form-group">
            <label for="username" class="form-label">Tên đăng nhập: <span style="color: var(--danger-color);">*</span></label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                class="form-control" 
                placeholder="Nhập tên đăng nhập (ví dụ: hieu_2026)"
                value="<?php echo htmlspecialchars($username); ?>" 
                required 
                autofocus
            >
            <small style="color: var(--text-muted); font-size: 0.825rem;">Tối thiểu 3 ký tự (chữ, số hoặc dấu gạch dưới).</small>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Mật khẩu: <span style="color: var(--danger-color);">*</span></label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-control" 
                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                required
            >
        </div>

        <div class="form-group">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu: <span style="color: var(--danger-color);">*</span></label>
            <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" 
                class="form-control" 
                placeholder="Nhập lại mật khẩu để xác nhận"
                required
            >
        </div>

        <div style="margin-top: 1.75rem;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 1rem; font-weight: 600;">
                ✨ Đăng ký ngay
            </button>
        </div>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color); color: var(--text-muted);">
            Đã có tài khoản? <a href="login.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Đăng nhập tại đây &rarr;</a>
        </div>
    </form>
</div>

<?php
include __DIR__ . '/view/footer.php';
?>
