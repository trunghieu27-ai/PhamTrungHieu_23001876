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
                <a href="product_list.php" class="nav-link <?php echo ($currentScript === 'product_list.php') ? 'active' : ''; ?>">Danh sách sản phẩm</a>
                <a href="product_add.php" class="nav-link <?php echo ($currentScript === 'product_add.php') ? 'active' : ''; ?>">+ Thêm sản phẩm</a>
            </nav>
        </div>
    </header>

    <main class="content-area">
        <div class="container">
