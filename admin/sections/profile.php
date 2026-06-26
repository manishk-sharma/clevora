<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Admin Profile Settings | Clevora Admin';
$msg = '';
$error = '';

$admin_id = (int)($_SESSION['clevora_admin'] ?? 0);

// Fetch current details
$admin_user = null;
if ($admin_id && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
        $stmt->execute([$admin_id]);
        $admin_user = $stmt->fetch();
    } catch (Exception $e) {
        $error = 'Failed to fetch profile: ' . $e->getMessage();
    }
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $new_username = trim($_POST['username'] ?? '');
        if (empty($new_username)) {
            $error = 'Username cannot be empty.';
        } else {
            try {
                // Check if username is already taken by another admin
                $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
                $stmt->execute([$new_username, $admin_id]);
                if ($stmt->fetch()) {
                    $error = 'Username is already in use by another account.';
                } else {
                    $stmt = $pdo->prepare("UPDATE admin_users SET username = ? WHERE id = ?");
                    $stmt->execute([$new_username, $admin_id]);
                    $_SESSION['clevora_admin_name'] = $new_username;
                    
                    // Refresh data
                    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
                    $stmt->execute([$admin_id]);
                    $admin_user = $stmt->fetch();
                    
                    $msg = 'Username updated successfully.';
                }
            } catch (Exception $e) {
                $error = 'Failed to update username: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'change_password') {
        $current_pass = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm_pass = $_POST['confirm_password'] ?? '';
        
        if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
            $error = 'All password fields are required.';
        } elseif ($new_pass !== $confirm_pass) {
            $error = 'New password and confirm password do not match.';
        } elseif (strlen($new_pass) < 6) {
            $error = 'New password must be at least 6 characters long.';
        } else {
            try {
                // Check current password
                if ($admin_user && password_verify($current_pass, $admin_user['password'])) {
                    $new_hash = password_hash($new_pass, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                    $stmt->execute([$new_hash, $admin_id]);
                    $msg = 'Password changed successfully.';
                } else {
                    $error = 'Incorrect current password.';
                }
            } catch (Exception $e) {
                $error = 'Failed to change password: ' . $e->getMessage();
            }
        }
    }
}
?>
<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-4xl mx-auto space-y-6">
      
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Admin Profile Settings</h1>
        <p class="text-xs text-gray-400 mt-1">Manage admin credentials and update password requirements.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Username Profile Form -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Account Profile</h2>
          
          <form method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_profile">
            
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5 font-sans">Username</label>
              <input type="text" name="username" required value="<?= htmlspecialchars($admin_user['username'] ?? '') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
            
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1 font-sans">Role Privilege</label>
              <input type="text" readonly value="Administrator"
                     class="w-full bg-gray-100 border border-gray-200 rounded px-2.5 py-2 text-xs text-gray-500 outline-none select-none cursor-not-allowed">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1 font-sans">Last Login</label>
              <input type="text" readonly value="<?= $admin_user['last_login'] ? date('M d, Y h:i A', strtotime($admin_user['last_login'])) : 'First login session' ?>"
                     class="w-full bg-gray-100 border border-gray-200 rounded px-2.5 py-2 text-xs text-gray-500 outline-none select-none cursor-not-allowed">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition">
              Update Profile Name
            </button>
          </form>
        </div>

        <!-- Password Change Form -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Change Password</h2>
          
          <form method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="change_password">
            
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Current Password *</label>
              <input type="password" name="current_password" required placeholder="••••••••"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
            </div>
            
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">New Password *</label>
              <input type="password" name="new_password" required placeholder="Min 6 characters"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Confirm New Password *</label>
              <input type="password" name="confirm_password" required placeholder="Repeat new password"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition">
              Update Credentials
            </button>
          </form>
        </div>

      </div>

    </div>
  </main>

<?php include '../includes/admin-footer.php'; ?>
