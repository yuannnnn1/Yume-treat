<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YUME TREAT</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

    <style>
        /* 1. 侧边栏基础容器：默认完全隐藏 */
        .side-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            visibility: hidden;
            pointer-events: none;
        }

        /* 激活状态 */
        .side-menu.active {
            visibility: visible;
            pointer-events: auto;
        }

        /* 2. 遮罩层：仅提供点击关闭区域，不设背景色 */
        .menu-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* 3. 菜单内容滑出定位：去除背景色、阴影等所有视觉样式 */
        .menu-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 280px; /* 设定一个基础宽度 */
            height: 100%;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            background: #fff; /* 仅保留一个基础白底，确保文字可见 */
        }
        
        .side-menu.active .menu-content {
            transform: translateX(0);
        }

        /* 4. 内部排版基础布局（无装饰性样式） */
        .menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }

        .menu-links {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 15px;
        }

        .menu-item {
            display: block;
            text-decoration: none;
            color: inherit; /* 继承父级文字颜色 */
        }

        /* 页面平滑滚动 */
        html {
            scroll-behavior: smooth;
        }
    </style>
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

        <a href="cart.php">
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
            <a href="main.php#category" class="menu-item" id="category-link">CATEGORY</a>
            <a href="cart.php" class="menu-item">CART</a>
            <a href="orders.php" class="menu-item">ORDER HISTORY</a>
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