<?php include("view/header.php");?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Yume Treat</title>
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
        <section>
            <h2>Login</h2>
            
            <form action="login.php" method="POST">
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
    </body>
</html>
<?php include("view/footer.php");?>