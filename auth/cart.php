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

$user_id = $_SESSION['user_id'] ?? 1;

if (isset($_GET['action']) && isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];
    $action = $_GET['action'];

    if ($action === 'add') {
        $stmt = $pdo->prepare("
            INSERT INTO transaction (product_id, user_id, quantity, transaction_date) 
            VALUES (?, ?, 1, CURDATE()) 
            ON DUPLICATE KEY UPDATE quantity = quantity + 1
        ");
        $stmt->execute([$product_id, $user_id]);

        $type = $_GET['type'] ?? '';
        $redirect_url = $type ? "../category.php?type=" . urlencode($type) : "../category.php";
        header("Location: " . $redirect_url);
        exit();
    }

    if ($action === 'increase') {
        $stmt = $pdo->prepare("UPDATE transaction SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    } elseif ($action === 'decrease') {
        $check = $pdo->prepare("SELECT quantity FROM transaction WHERE user_id = ? AND product_id = ?");
        $check->execute([$user_id, $product_id]);
        $qty = $check->fetchColumn();

        if ($qty > 1) {
            $stmt = $pdo->prepare("UPDATE transaction SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM transaction WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM transaction WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    }

    header("Location: cart.php");
    exit();
}

$fetch_stmt = $pdo->prepare("
    SELECT 
        t.transaction_id,
        t.product_id, 
        t.quantity, 
        p.product_name, 
        p.price_yen AS price 
    FROM transaction t
    JOIN product_list p ON t.product_id = p.product_id
    WHERE t.user_id = ?
");
$fetch_stmt->execute([$user_id]);
$cart_items = $fetch_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yume Treat - Cart</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include("../view/header.php"); ?>

    <main class="cart-container">
        <h1>Cart</h1>

        <?php if (!empty($cart_items)): ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-card">
                        <div class="cart-image">
                            <img src="../image/product/<?php echo htmlspecialchars($item['product_name']); ?>.jpeg" 
                                alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                onerror="this.onerror=null; this.src='../images/<?php echo $item['product_id']; ?>.jpg';">
                        </div>

                        <div class="cart-details">
                            <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                            <p class="price">Price: ¥<?php echo htmlspecialchars($item['price']); ?></p>

                            <div class="quantity-controls">
                                <a href="cart.php?action=increase&product_id=<?php echo $item['product_id']; ?>" class="btn-qty">+</a>
                                <span class="qty-count"><?php echo $item['quantity']; ?></span>
                                <a href="cart.php?action=decrease&product_id=<?php echo $item['product_id']; ?>" class="btn-qty">-</a>
                                <a href="cart.php?action=delete&product_id=<?php echo $item['product_id']; ?>" class="btn-delete" title="Remove Item"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-actions">
                <button type="button" class="btn-order">Order</button>
            </div>
        <?php else: ?>
            <p class="empty-cart">Your cart is currently empty.</p>
        <?php endif; ?>
    </main>

    <?php include("../view/footer.php"); ?>
</body>
</html>