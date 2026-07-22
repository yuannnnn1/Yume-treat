<?php
session_start();

$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Destroy server session
session_destroy();

// Redirect straight to login page
header("Location: /Yume-treat/login.php");
exit();
?>