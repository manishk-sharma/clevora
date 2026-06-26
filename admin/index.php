<?php
/**
 * Admin Login Page
 * Secure login with bcrypt password verification, remember-me, and CSRF protection.
 */
session_start();
require_once '../includes/db.php';

$error = '';
$success = $_SESSION['login_message'] ?? '';
unset($_SESSION['login_message']);

// Already logged in? Redirect to dashboard
if (!empty($_SESSION['clevora_admin'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ── Auto-login via Remember Token ───────────────────────
if (empty($_SESSION['clevora_admin']) && !empty($_COOKIE['clevora_remember']) && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE remember_token = ? AND remember_token IS NOT NULL");
        $stmt->execute([$_COOKIE['clevora_remember']]);
        $admin = $stmt->fetch();
        if ($admin) {
            $_SESSION['clevora_admin'] = $admin['id'];
            $_SESSION['clevora_admin_name'] = $admin['username'];
            $_SESSION['clevora_last_activity'] = time();
            // Update last login
            $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?")->execute([$admin['id']]);
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            // Invalid token — clear cookie
            setcookie('clevora_remember', '', time() - 3600, '/', '', false, true);
        }
    } catch (Exception $e) {
        error_log('Remember token check failed: ' . $e->getMessage());
    }
}

// ── Handle Login POST ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        $u = trim($_POST['username'] ?? '');
        $p = trim($_POST['password'] ?? '');
        $remember = !empty($_POST['remember']);
        
        $authenticated = false;
        
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
                $stmt->execute([$u]);
                $admin = $stmt->fetch();
                if ($admin && password_verify($p, $admin['password'])) {
                    $authenticated = true;
                    $_SESSION['clevora_admin'] = $admin['id'];
                    $_SESSION['clevora_admin_name'] = $admin['username'];
                    $_SESSION['clevora_last_activity'] = time();
                    
                    // Update last login
                    $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?")->execute([$admin['id']]);
                    
                    // Set remember-me cookie (30 days)
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $pdo->prepare("UPDATE admin_users SET remember_token = ? WHERE id = ?")->execute([$token, $admin['id']]);
                        setcookie('clevora_remember', $token, time() + (30 * 24 * 3600), '/', '', false, true);
                    }
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    header('Location: /admin/dashboard.php');
                    exit;
                }
            } catch (Exception $e) {
                $error = 'A system error occurred. Please try again.';
                error_log('Login error: ' . $e->getMessage());
            }
        }
        
        if (!$authenticated && empty($error)) {
            $error = 'Invalid username or password.';
        }
    }
    
    // Regenerate CSRF token after attempt
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clevora Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      min-height: 100vh; background: linear-gradient(135deg, #e8edf8 0%, #dbeafe 100%);
      display: flex; align-items: center; justify-content: center;
      font-family: 'Inter', sans-serif;
    }
    .login-card {
      background: #fff; border-radius: 20px;
      box-shadow: 0 8px 40px rgba(37,99,235,.10), 0 2px 8px rgba(0,0,0,.04);
      padding: 48px; width: 400px; text-align: center;
    }
    .login-card img { height: 44px; margin: 0 auto 6px; }
    .login-card .tagline { font-size: 11px; color: #9ca3af; font-style: italic; margin-bottom: 28px; }
    .alert {
      font-size: 12px; padding: 10px 14px; border-radius: 8px;
      margin-bottom: 16px; text-align: left;
      animation: fadeIn 0.3s ease;
    }
    .alert-error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .alert-info { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .form-group { margin-bottom: 16px; text-align: left; }
    .form-group label {
      display: block; font-size: 12px; font-weight: 600;
      color: #374151; margin-bottom: 6px;
    }
    .form-group input[type="text"],
    .form-group input[type="password"] {
      width: 100%; border: 1.5px solid #e5e7eb; border-radius: 10px;
      padding: 12px 14px; font-size: 13px; outline: none;
      transition: border .2s, box-shadow .2s; font-family: 'Inter', sans-serif;
    }
    .form-group input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }
    .remember-row {
      display: flex; align-items: center; gap: 8px; margin-bottom: 22px;
      font-size: 12px; color: #6b7280; text-align: left;
    }
    .remember-row input[type="checkbox"] { accent-color: #3b82f6; }
    .btn-login {
      width: 100%; background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: #fff; padding: 14px; border-radius: 10px; font-size: 14px;
      font-weight: 600; border: none; cursor: pointer;
      transition: all .2s; font-family: 'Inter', sans-serif;
      letter-spacing: 0.3px;
    }
    .btn-login:hover { background: linear-gradient(135deg, #2563eb, #1d4ed8); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.3); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }
  </style>
</head>
<body>
  <div class="login-card">
    <img src="/assets/images/logo.png" alt="Clevora">
    <p class="tagline">"Make Contact. Build Relationships. Get Result."</p>

    <?php if(!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required autocomplete="username"
               placeholder="Enter your username">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required autocomplete="current-password"
               placeholder="Enter your password">
      </div>
      <div class="remember-row">
        <input type="checkbox" name="remember" id="remember" value="1">
        <label for="remember" style="margin:0; font-weight:500; cursor:pointer;">Remember me for 30 days</label>
      </div>
      <button type="submit" class="btn-login">Sign In</button>
    </form>
  </div>
</body>
</html>
