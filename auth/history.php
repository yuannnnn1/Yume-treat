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

$user_stmt = $pdo->prepare("SELECT user_name FROM user WHERE user_id = ?");
$user_stmt->execute([$user_id]);
$user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

$display_name = $user_data['user_name'] ?? 'Valued Customer';

$history_stmt = $pdo->prepare("
    SELECT 
        t.product_id, 
        t.quantity, 
        t.transaction_date, 
        p.product_name 
    FROM transaction t
    JOIN product_list p ON t.product_id = p.product_id
    WHERE t.user_id = ? AND t.status = 'ordered'
    ORDER BY t.transaction_date DESC, t.transaction_id DESC
");
$history_stmt->execute([$user_id]);
$ordered_items = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yume Treat - Order History</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include("../view/header.php"); ?>

    <main class="history-container">
        <h1><?php echo htmlspecialchars($display_name); ?>'s Order History</h1>

        <?php if (!empty($ordered_items)): ?>
            <div class="history-items">
                <?php foreach ($ordered_items as $item): ?>
                    <div class="history-card">
                        <div class="history-card-top">
                            <div class="history-image">
                                <img src="../image/product/<?php echo htmlspecialchars($item['product_name']); ?>.jpeg" 
                                     alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                     onerror="this.onerror=null; this.src='../images/<?php echo $item['product_id']; ?>.jpg';">
                            </div>

                            <div class="history-title-qty">
                                <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                                <span class="history-qty">× <?php echo (int)$item['quantity']; ?></span>
                            </div>
                        </div>

                        <div class="history-card-bottom">
                            <p class="order-date">Order Date : <?php echo date("n/j/Y", strtotime($item['transaction_date'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-history">You have no past orders.</p>
        <?php endif; ?>
    </main>

    <?php include("../view/footer.php"); ?>
</body>
</html>