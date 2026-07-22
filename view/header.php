<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

$base_url = '/Yume-treat/';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user']) || isset($_SESSION['user_id']);

// Check if the user has an admin role across common session structures
$is_admin = false;
if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['role'])) {
    $is_admin = ($_SESSION['user']['role'] === 'admin');
} elseif (isset($_SESSION['role'])) {
    $is_admin = ($_SESSION['role'] === 'admin');
} elseif (isset($_SESSION['user_role'])) {
    $is_admin = ($_SESSION['user_role'] === 'admin');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YUME TREAT</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
</head>

<body>

<header class="header">
    <div class="header-left">
        <a href="javascript:void(0);" id="menu-toggle">
            <i class="fa-solid fa-bars"></i>
        </a>
    </div>

    <div class="header-logo">
        <a href="<?php echo $base_url; ?>index.php">YUME TREAT</a>
    </div>

    <div class="header-right">
        <a href="<?php echo $base_url; ?>auth/cart.php">
            <i class="fa-solid fa-cart-shopping"></i>
        </a>
    </div>
</header>

<div class="side-menu" id="side-menu">
    <div class="menu-overlay" id="menu-overlay"></div>
    
    <div class="menu-content">
        <div class="menu-header">
            <span>YUME TREAT</span>
            <a href="javascript:void(0);" class="close-btn" id="menu-close">
                <i class="fa-solid fa-xmark"></i>
            </a>
        </div>
        
        <div class="menu-links">
            <?php if ($is_logged_in): ?>
                <a href="<?php echo $base_url; ?>login.php" class="menu-item">LOGOUT</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>login.php" class="menu-item">LOGIN/REGISTER</a>
            <?php endif; ?>

            <a href="<?php echo $base_url; ?>index.php#category" class="menu-item" id="category-link">CATEGORY</a>
            <a href="<?php echo $base_url; ?>auth/cart.php" class="menu-item">CART</a>
            <a href="<?php echo $base_url; ?>auth/history.php" class="menu-item">ORDER HISTORY</a>

            <?php if ($is_admin): ?>
                <a href="<?php echo $base_url; ?>auth/admin_products.php" class="menu-item admin-link">ADMIN DASHBOARD</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const menuClose = document.getElementById('menu-close');
    const menuOverlay = document.getElementById('menu-overlay');
    const sideMenu = document.getElementById('side-menu');
    const categoryLink = document.getElementById('category-link');

    menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        sideMenu.classList.add('active');
    });

    function closeMenu() {
        sideMenu.classList.remove('active');
    }

    menuClose.addEventListener('click', closeMenu);
    menuOverlay.addEventListener('click', closeMenu);
    if (categoryLink) {
        categoryLink.addEventListener('click', closeMenu);
    }
});
</script>