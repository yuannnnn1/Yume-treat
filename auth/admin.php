<?php
session_start();

$host = '172.21.82.206';
$dbname = 'group12'; 
$username = 'group12';
$password = '9503'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: ../login.php");
    exit();
}

$user_stmt = $pdo->prepare("SELECT role FROM user WHERE user_id = ?");
$user_stmt->execute([$user_id]);
$current_user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$current_user || $current_user['role'] !== 'admin') {
    die("<h2 style='text-align:center; margin-top:50px;'>Access Denied: Admin Privileges Required</h2>");
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id   = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category     = $_POST['category'];
    $price_yen    = $_POST['price_yen'];
    $stock        = $_POST['stock'];
    $description  = $_POST['description'];

    $update_stmt = $pdo->prepare("
        UPDATE product_list 
        SET product_name = ?, category = ?, price_yen = ?, stock = ?, description = ?
        WHERE product_id = ?
    ");

    if ($update_stmt->execute([$product_name, $category, $price_yen, $stock, $description, $product_id])) {
        $message = "Product #{$product_id} updated successfully!";
    } else {
        $message = "Failed to update product.";
    }
}

$products_stmt = $pdo->query("SELECT * FROM product_list ORDER BY product_id ASC");
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yume Treat - Admin Product Management</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/admin.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include("../view/header.php"); ?>

    <main class="admin-container">
        <h1>Admin: Product Inventory</h1>

        <?php if (!empty($message)): ?>
            <div class="alert-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price (¥)</th>
                        <th>Stock</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <form method="POST" action="admin.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                
                                <td><strong>#<?php echo $product['product_id']; ?></strong></td>
                                
                                <td>
                                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                                </td>
                                
                                <td>
                                    <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
                                </td>
                                
                                <td>
                                    <input type="number" name="price_yen" value="<?php echo (int)$product['price_yen']; ?>" min="0" required>
                                </td>
                                
                                <td>
                                    <input type="number" name="stock" value="<?php echo (int)$product['stock']; ?>" min="0" required>
                                </td>
                                
                                <td>
                                    <textarea name="description" rows="2"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                </td>
                                
                                <td>
                                    <button type="submit" name="update_product" class="btn-save">Save</button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include("../view/footer.php"); ?>
</body>
</html>