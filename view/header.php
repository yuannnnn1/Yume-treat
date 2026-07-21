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
        <a href="index.php">YUME TREAT</a>
    </div>

    <div class="header-right">
        <a href="search.php">
            <i class="fa-solid fa-magnifying-glass"></i>
        </a>
        <a href="auth/cart.php">
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
            <a href="login.php" class="menu-item">LOGIN/REGISTER</a>
            <a href="index.php#category" class="menu-item" id="category-link">CATEGORY</a>
            <a href="auth/cart.php" class="menu-item">CART</a>
            <a href="auth/orders.php" class="menu-item">ORDER HISTORY</a>
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

    // 打开菜单
    menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        sideMenu.classList.add('active');
    });

    // 关闭菜单
    function closeMenu() {
        sideMenu.classList.remove('active');
    }

    menuClose.addEventListener('click', closeMenu);
    menuOverlay.addEventListener('click', closeMenu);
    categoryLink.addEventListener('click', closeMenu);
});
</script>