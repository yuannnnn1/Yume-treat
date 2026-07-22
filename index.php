<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YUME TREAT</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

<?php include 'view/header.php'; ?>

<main>
    <section class="hero">
        <div class="hero-image">
            <img src="image/hero/yume.png" alt="Hero image">
        </div>
    </section>

    <section id="category" class="category">
        <h1>SHOP BY CATEGORY</h1>
        <div class="grid">
            <a href="category.php?type=Chocolate" class="category-item chocolate">
                <img src="image/category/chocolate.png" alt="Chocolate">
                <p>Chocolate</p>
            </a>
            
            <a href="category.php?type=Traditional Sweets" class="category-item trandition">
                <img src="image/category/traditional sweets.png" alt="Traditional Sweets">
                <p>Traditional Sweets</p>
            </a>
            
            <a href="category.php?type=Snacks" class="category-item snacks">
                <img src="image/category/snacks.png" alt="Snacks">
                <p>Snacks</p>
            </a>
            
            <a href="category.php?type=Drinks" class="category-item drinks">
                <img src="image/category/drinks.png" alt="Drinks">
                <p>Drinks</p>
            </a>
            
            <a href="category.php?type=Instant Noodles" class="category-item noodle">
                <img src="image/category/instant noodle.png" alt="Instant Noodles">
                <p>Instant Noodles</p>
            </a>  
        </div>
    </section>
</main>
<?php include 'view/footer.php'; ?>
</body>
</html>