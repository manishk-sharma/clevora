<?php
/**
 * Admin Logout
 * Clears session, remember token, and redirects to login.
 */
session_start();
require_once '../includes/db.php';

// Clear remember token from database
if (!empty($_SESSION['clevora_admin']) && $pdo) {
    try {
        $pdo->prepare("UPDATE admin_users SET remember_token = NULL WHERE id = ?")
            ->execute([$_SESSION['clevora_admin']]);
    } catch (Exception $e) {
        error_log('Logout token clear failed: ' . $e->getMessage());
    }
}

// Clear remember cookie
if (isset($_COOKIE['clevora_remember'])) {
    setcookie('clevora_remember', '', time() - 3600, '/', '', false, true);
}

// Destroy session completely
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header('Location: /admin/index.php');
exit;
