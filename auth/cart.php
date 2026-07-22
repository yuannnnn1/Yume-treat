<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect immediately if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Yume-treat/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

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

if (isset($_GET['action']) && isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];
    $action = $_GET['action'];

    if ($action === 'add') {
        $check_stmt = $pdo->prepare("SELECT transaction_id FROM transaction WHERE user_id = ? AND product_id = ? AND status = 'cart' LIMIT 1");
        $check_stmt->execute([$user_id, $product_id]);
        $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $update = $pdo->prepare("UPDATE transaction SET quantity = quantity + 1 WHERE transaction_id = ?");
            $update->execute([$existing['transaction_id']]);
        } else {
            $insert = $pdo->prepare("INSERT INTO transaction (product_id, user_id, quantity, transaction_date, status) VALUES (?, ?, 1, CURDATE(), 'cart')");
            $insert->execute([$product_id, $user_id]);
        }

        $type = $_GET['type'] ?? '';
        $redirect_url = $type ? "../category.php?type=" . urlencode($type) : "../category.php";
        header("Location: " . $redirect_url);
        exit();
    }

    if ($action === 'increase') {
        $check = $pdo->prepare("SELECT transaction_id FROM transaction WHERE user_id = ? AND product_id = ? AND (status = 'cart' OR status IS NULL) LIMIT 1");
        $check->execute([$user_id, $product_id]);
        $row = $check->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $stmt = $pdo->prepare("UPDATE transaction SET quantity = quantity + 1 WHERE transaction_id = ?");
            $stmt->execute([$row['transaction_id']]);
        }
    } elseif ($action === 'decrease') {
        $sum_stmt = $pdo->prepare("SELECT SUM(quantity) AS total FROM transaction WHERE user_id = ? AND product_id = ? AND (status = 'cart' OR status IS NULL)");
        $sum_stmt->execute([$user_id, $product_id]);
        $total_qty = (int)$sum_stmt->fetchColumn();

        if ($total_qty > 1) {
            $check = $pdo->prepare("SELECT transaction_id, quantity FROM transaction WHERE user_id = ? AND product_id = ? AND (status = 'cart' OR status IS NULL) ORDER BY transaction_id DESC LIMIT 1");
            $check->execute([$user_id, $product_id]);
            $row = $check->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if ($row['quantity'] > 1) {
                    $stmt = $pdo->prepare("UPDATE transaction SET quantity = quantity - 1 WHERE transaction_id = ?");
                    $stmt->execute([$row['transaction_id']]);
                } else {
                    $stmt = $pdo->prepare("DELETE FROM transaction WHERE transaction_id = ?");
                    $stmt->execute([$row['transaction_id']]);
                }
            }
        } else {
            $stmt = $pdo->prepare("DELETE FROM transaction WHERE user_id = ? AND product_id = ? AND (status = 'cart' OR status IS NULL)");
            $stmt->execute([$user_id, $product_id]);
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM transaction WHERE user_id = ? AND product_id = ? AND (status = 'cart' OR status IS NULL)");
        $stmt->execute([$user_id, $product_id]);
    }

    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $checkout_stmt = $pdo->prepare("
        UPDATE transaction 
        SET status = 'ordered', transaction_date = CURDATE() 
        WHERE user_id = ? AND (status = 'cart' OR status IS NULL)
    ");
    $checkout_stmt->execute([$user_id]);

    header("Location: history.php");
    exit();
}

$fetch_stmt = $pdo->prepare("
    SELECT 
        t.product_id, 
        SUM(t.quantity) AS quantity, 
        p.product_name, 
        p.price_yen AS price 
    FROM transaction t
    JOIN product_list p ON t.product_id = p.product_id
    WHERE t.user_id = ? AND (t.status = 'cart' OR t.status IS NULL)
    GROUP BY t.product_id, p.product_name, p.price_yen
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
        <link rel="stylesheet" href="../css/cart.css?v=1.0">
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
                                    <a href="cart.php?action=decrease&product_id=<?php echo $item['product_id']; ?>" class="btn-qty">-</a>
                                    <span class="qty-count"><?php echo $item['quantity']; ?></span>
                                    <a href="cart.php?action=increase&product_id=<?php echo $item['product_id']; ?>" class="btn-qty">+</a>
                                    <a href="cart.php?action=delete&product_id=<?php echo $item['product_id']; ?>" class="btn-delete" title="Remove Item"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form method="POST" action="cart.php" class="cart-actions">
                    <button type="submit" name="checkout" class="btn-order">Order</button>
                </form>
            <?php else: ?>
                <p class="empty-cart">Your cart is currently empty.</p>
            <?php endif; ?>
        </main>

        <?php include("../view/footer.php"); ?>
    </body>
</html>