<?php
session_start();

$host = '172.21.82.206';
$db = 'group12';
$user = 'group12';
$pass = '9503';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $redirect_to = 'login.php'; 

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT * FROM user WHERE user_email = ?');
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $error_message = "Email is already registered. Please login instead.";
    } else {
        // Find highest existing user_id and add 1
        $id_stmt = $pdo->query('SELECT MAX(user_id) AS max_id FROM user');
        $max_id = $id_stmt->fetch()['max_id'];
        $new_user_id = ($max_id !== null) ? $max_id + 1 : 1;

        // Hash password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer'; 

        // Insert including the manually calculated user_id
        $insert_stmt = $pdo->prepare('INSERT INTO user (user_id, user_name, user_email, user_password, role) VALUES (?, ?, ?, ?, ?)');
        
        if ($insert_stmt->execute([$new_user_id, $name, $email, $hashed_password, $role])) {
            header("Location: " . $redirect_to);
            exit();
        } else {
            $error_message = "Registration failed. Please try again.";
        }
    }
}
?>
<?php include("view/header.php");?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Yume Treat - Register</title>
        <link rel="stylesheet" href="css/register.css">
    </head>
    <body>
        <section>
            <h2>Create Account</h2>
            
            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; font-size: 14px; margin-bottom: 15px;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <!-- Hardcoded to login.php -->
                <input type="hidden" name="redirect_to" value="login.php">

                <label for="name">Name:</label>
                <!-- Added type="text" so CSS styles this input properly -->
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <div class="button-container">
                    <a href="login.php" class="btn">Back</a>
                    <button type="submit" class="btn">Create Account</button>
                </div>
            </form>
        </section>
    </body>
</html>
<?php include("view/footer.php");?>