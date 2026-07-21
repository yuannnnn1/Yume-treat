<?php
$host = '172.21.82.206';
$dbname = 'group12'; 
$username = 'group12';$password = '9503';         

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$category = isset($_GET['type']) ?$_GET['type'] : 'chocolate';

try {
    $stmt =$pdo->prepare("SELECT * FROM product_list WHERE category = :category");
    $stmt->execute(['category' =>$category]);
    
    $products =$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error" . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(ucfirst($category)); ?> - YUME TREAT</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/category.css"> 
</head>

<body>

<?php include 'view/header.php'; ?>

<main>
    <nav class="breadcrumbs">
        <a href="index.php">home</a>
        <span class="separator">&gt;</span>
        <a href="index.php#category">category</a>
        <span class="separator">&gt;</span>
        <span class="current"><?php echo htmlspecialchars($category); ?></span>
    </nav>

    <div class="category-title-container">
        <h1><?php echo htmlspecialchars(ucfirst($category)); ?></h1>
    </div>
    <section id="product" class="product-grid">
        <?php if (count($products) > 0) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="image/product/<?php echo htmlspecialchars($product['product_name']); ?>.jpeg" 
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>
                
                <p><?php echo htmlspecialchars($product['product_name']); ?></p>
                
                <div class="product-actions">
                    <a href="product.php?id=<?php echo $product['product_id']; ?>">
                        <button type="button" class="btn-yellow">SEE MORE</button>
                    </a>
                    
                    <a href="auth/cart.php?action=add&product_id=<?php echo $product['product_id']; ?>&type=<?php echo urlencode($category); ?>">
                        <button type="button" class="btn-yellow">ADD</button>
                    </a>
                </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="no-products">No products found in this category.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'view/footer.php'; ?>
</body>
</html>