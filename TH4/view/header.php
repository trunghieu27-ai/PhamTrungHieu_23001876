<?php
/**
 * Header giao diện dùng chung
 * Dự án: Shopping Cart - Product Management System
 * Sinh viên: Phạm Trung Hiếu (MSSV: 23001876)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentScript = basename($_SERVER['SCRIPT_NAME']);
$isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
$currentUsername = $isLoggedIn ? htmlspecialchars($_SESSION['user']['username']) : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Shopping Cart' : 'Shopping Cart - Quản lý sản phẩm'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-content">
            <a href="index.php" class="brand-title">
                🛒 Shopping Cart <span class="brand-badge">TH4</span>
            </a>
            <nav class="main-nav">
                <a href="index.php" class="nav-link <?php echo ($currentScript === 'index.php') ? 'active' : ''; ?>">Trang chủ</a>

                <?php if ($isLoggedIn): ?>
                    <a href="product_list.php" class="nav-link <?php echo ($currentScript === 'product_list.php') ? 'active' : ''; ?>">Danh sách sản phẩm</a>
                    <a href="product_add.php" class="nav-link <?php echo ($currentScript === 'product_add.php') ? 'active' : ''; ?>">+ Thêm sản phẩm</a>
                    <div class="user-menu">
                        <span class="user-badge" title="Tài khoản hiện tại">
                            👤 <strong><?php echo $currentUsername; ?></strong>
                        </span>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">
                            Đăng xuất
                        </a>
                    </div>
                <?php else: ?>
                    <div class="user-menu">
                        <a href="login.php" class="nav-link <?php echo ($currentScript === 'login.php') ? 'active' : ''; ?>">
                            🔐 Đăng nhập
                        </a>
                        <a href="register.php" class="btn btn-primary btn-sm <?php echo ($currentScript === 'register.php') ? 'active' : ''; ?>">
                            📝 Đăng ký
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="content-area">
        <div class="container">
