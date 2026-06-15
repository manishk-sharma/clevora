<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

$keys = ['tech_infrastructure', 'footer_subscribe_text'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pdo) {
        try {
            $pdo->beginTransaction();
            foreach ($keys as $k) {
                $v = trim($_POST[$k] ?? '');
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $v, $v]);
            }
            $pdo->commit();
            $msg = 'Technology settings updated successfully.';
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = 'Database update failed: ' . $e->getMessage();
        }
    }
}

// Fetch current values
$settings = [];
foreach ($keys as $k) {
    $settings[$k] = setting($k, $pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Technology Settings | Clevora Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="flex bg-gray-50 min-h-screen font-sans">

  <!-- SIDEBAR -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-8 md:p-12">
    <div class="max-w-3xl mx-auto space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Edit Technology & Corporate Capabilities</h1>
        <p class="text-xs text-gray-400">Configure infrastructure descriptions and footer corporate information statements.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Technology & Infrastructure Description (technology.php)</label>
          <textarea name="tech_infrastructure" rows="8" required placeholder="Describe server specs, redundant power networks, ISP pipelines, and security grids..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['tech_infrastructure']) ?></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Footer Capability Statement</label>
          <textarea name="footer_subscribe_text" rows="4" required placeholder="General capability statement text to show on footer column 4..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['footer_subscribe_text']) ?></textarea>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          Save Technology Settings
        </button>
      </form>
    </div>
  </main>

</body>
</html>
