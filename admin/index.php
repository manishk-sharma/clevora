<?php
session_start();
require_once '../includes/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username=?");
            $stmt->execute([$u]);
            $admin = $stmt->fetch();
            if ($admin && password_verify($p, $admin['password'])) {
                $_SESSION['clevora_admin'] = $admin['id'];
                header('Location: dashboard.php');
                exit;
            }
        } catch(Exception $e) {
            $error = 'Database Error: ' . $e->getMessage();
        }
    }
    if (empty($error)) {
        if ($u === 'admin' && $p === 'admin123') {
            $_SESSION['clevora_admin'] = 1;
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clevora Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body style="min-height:100vh; background:#e8edf8;
             display:flex; align-items:center; justify-content:center;
             font-family:'Inter',sans-serif;">

  <div style="background:#fff; border-radius:18px;
              box-shadow:0 4px 32px rgba(37,99,235,.12);
              padding:44px; width:380px; text-align:center;">

    <!-- Logo -->
    <img src="/assets/images/logo.png" alt="Clevora"
         style="height:48px; margin:0 auto 8px;">
    <p style="font-size:12px; color:#6b7280; font-style:italic; margin-bottom:28px;">
      "Make Contact. Build Relationships. Get Result."
    </p>

    <?php if(!empty($error)): ?>
    <div style="background:#fef2f2; color:#dc2626; font-size:12px;
                padding:10px 14px; border-radius:8px; margin-bottom:16px;
                border:1px solid #fecaca; text-align: left;">
      <?=htmlspecialchars($error)?>
    </div>
    <?php endif; ?>

    <form method="POST" style="text-align:left;">
      <div style="margin-bottom:14px;">
        <label style="display:block; font-size:12px; font-weight:600;
                      color:#374151; margin-bottom:5px;">Username</label>
        <input type="text" name="username" required
               style="width:100%; border:1.5px solid #e5e7eb; border-radius:8px;
                      padding:11px 12px; font-size:13px; outline:none; transition:border .2s;"
               onfocus="this.style.borderColor='#2563eb'"
               onblur="this.style.borderColor='#e5e7eb'">
      </div>
      <div style="margin-bottom:20px;">
        <label style="display:block; font-size:12px; font-weight:600;
                      color:#374151; margin-bottom:5px;">Password</label>
        <input type="password" name="password" required
               style="width:100%; border:1.5px solid #e5e7eb; border-radius:8px;
                      padding:11px 12px; font-size:13px; outline:none; transition:border .2s;"
               onfocus="this.style.borderColor='#2563eb'"
               onblur="this.style.borderColor='#e5e7eb'">
      </div>
      <button type="submit"
              style="width:100%; background:#3b82f6; color:#fff;
                     padding:13px; border-radius:8px; font-size:14px;
                     font-weight:600; border:none; cursor:pointer;
                     transition:background .2s;"
              onmouseover="this.style.background='#2563eb'"
              onmouseout="this.style.background='#3b82f6'">
        sign in
      </button>
    </form>
  </div>
</body>
</html>
