<?php
session_start();

$host = '172.21.82.206';
$db   = 'group12';
$user = 'group12';
$pass = '9503';

$dsn = "mysql:host=$host;dbname=$db";
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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'index.php';

    $stmt = $pdo->prepare('SELECT * FROM user WHERE LOWER(user_email) = LOWER(?)');
    $stmt->execute([$email]);
    $user_data = $stmt->fetch();

    if ($user_data) {
        $db_password = $user_data['user_password'];

        $is_valid_password = password_verify($password, $db_password) || ($password === $db_password);

        if ($is_valid_password) {
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['email']   = $user_data['user_email'];
            $_SESSION['role']    = $user_data['role'];
            
            $_SESSION['user'] = [
                'id'    => $user_data['user_id'],
                'email' => $user_data['user_email'],
                'role'  => $user_data['role']
            ];

            header("Location: " . $redirect_to);
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Yume Treat</title>
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
        <?php include("view/header.php");?>

        <section>
            <h2>Login</h2>
            
            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; font-size: 14px; margin-bottom: 15px;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="redirect_to" value="index.php">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <div class="button-container">
                    <a href="register.php" class="btn">Create An Account</a>
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>
        </section>

        <?php include("view/footer.php");?>
    </body>
</html>