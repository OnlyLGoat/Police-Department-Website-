<?php
session_start();
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    // Delete from database if exists
    include 'connect.php';
    $stmt = $pdo->prepare('DELETE FROM remember_tokens WHERE token = ?');
    $stmt->execute([$token]);
    
    setcookie('remember_token', '', time() - 3600, '/');
}

session_destroy(); 
header("Location: logins/loginc.php");
exit;
