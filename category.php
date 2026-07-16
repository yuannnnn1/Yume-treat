<?php
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


$category = isset($_GET['type']) ? $_GET['type'] : 'chocolate';

try {
    $stmt = $pdo->prepare("SELECT * FROM product_list WHERE category = :category");
    $stmt->execute(['category' => $category]);
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
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

    <h1><?php echo htmlspecialchars(ucfirst($category)); ?></h1>
    
    <section id="product" class="product">
    <?php if (count($products) > 0) : ?>
        <?php foreach ($products as $product) : ?>
            <div>
               <img src="image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
               <p><?php echo htmlspecialchars($product['name']); ?></p>
               
               <a href="product.php?id=<?php echo $product['id']; ?>"><button>SEE MORE</button></a>
               
               <form action="cart.php" method="POST" style="display:inline;">
                   <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                   <button type="submit">ADD</button>
               </form>
            </div>
        <?php endforeach; ?>
        <?php else : ?>
        <p>No products found in this category.</p>
    <?php endif; ?>
    </section>
</main>
<?php include 'view/footer.php'; ?>
</body>
</html>