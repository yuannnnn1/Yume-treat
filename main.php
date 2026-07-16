<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YUME TREAT</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include 'view/header.php'; ?>

<main>
    <section class="hero">
        <div class="hero-image">
        <img src="image/hero/yume.png" alt="Hero image ">
        </div>
        </div>
    </section>

    <section class="category">
        <h1>SHOP BY CATEGORY</h1>
        <div class="grid">
        <a href="category.php?type=chocolate" class="category-item chocolate">
            <img src="image/category/chocolate.jpg" alt="Chocolate">
            <p>Chocolate</p>
        </a>
        
        <a href="category.php?type=tradition" class="category-item trandition">
            <img src="image/category/traditional.jpg" alt="Traditional Sweets">
            <p>Traditional Sweets</p>
        </a>
        
        <a href="category.php?type=snack" class="category-item snacks">
            <img src="image/category/snacks.jpg" alt="Snacks">
            <p>Snacks</p>
        </a>
        
        <a href="category.php?type=drink" class="category-item drinks">
            <img src="image/category/drinks.jpg" alt="Drinks">
            <p>Drinks</p>
        </a>
        
        <a href="category.php?type=noodle" class="category-item noodle">
            <img src="image/category/noodles.jpg" alt="Instant Noodles">
            <p>Instant Noodles</p>
        </a>  
    </div>
    </section>
</main>
<?php include 'view/footer.php'; ?>
</body>
</html>
