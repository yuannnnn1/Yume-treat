<?php
// 1. 数据库连接配置
$host = '172.21.82.206';
$dbname = 'group12'; 
$username = 'group12';    
$password = '9503';         

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 2. 获取并校验 URL 传入的商品 ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    die("Invalid product ID.");
}

// 3. 从数据库查询对应的商品详情
try {
    $stmt = $pdo->prepare("SELECT * FROM product_list WHERE product_id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        die("Product not found.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$category = isset($product['category']) ? $product['category'] : 'chocolate';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - YUME TREAT</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/product.css"> 
</head>

<body>

<?php include 'view/header.php'; ?>

<main>
    <nav class="breadcrumbs">
        <a href="index.php">home</a>
        <span class="separator">&gt;</span>
        <a href="index.php#category">category</a>
        <span class="separator">&gt;</span>
        <a href="category.php?type=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a>
        <span class="separator">&gt;</span>
        <span class="current"><?php echo htmlspecialchars($product['name']); ?></span>
    </nav>

    <section class="product-detail">
        <div class="product-image">
            <img src="image/product/<?php echo htmlspecialchars($product['product_name']); ?>.jpeg" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        
        <div class="product-info">
            <p class="price">PRICE: ¥<?php echo htmlspecialchars($product['price_yen']); ?></p>
            <p class="stock">STOCK: <?php echo htmlspecialchars($product['stock']); ?></p>
        </div>
        
        <div class="product-description">
            <h3>DESCRIPTION:</h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
        </div>
        
        <form action="cart.php" method="POST" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" class="btn-add-cart">ADD TO CART</button>
        </form>
    </section>
</main>

<?php include 'view/footer.php'; ?>

</body>
</html>