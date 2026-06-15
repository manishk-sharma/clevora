<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = ['about_full_history', 'about_mission', 'about_vision'];
    if ($pdo) {
        try {
            $pdo->beginTransaction();
            foreach ($keys as $k) {
                $v = trim($_POST[$k] ?? '');
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $v, $v]);
            }
            $pdo->commit();
            $msg = 'About details updated successfully.';
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = 'Database update failed: ' . $e->getMessage();
        }
    }
}

// Fetch current values
$settings = [];
$keys = ['about_full_history', 'about_mission', 'about_vision'];
foreach ($keys as $k) {
    $settings[$k] = setting($k, $pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage About Content | Clevora Admin</title>
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
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Edit About Us Settings</h1>
        <p class="text-xs text-gray-400">Manage company history statements, mission statements, and corporate values.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Company Full History (about-us.php)</label>
          <textarea name="about_full_history" rows="8" required placeholder="Describe Clevora's journey from 2011 to today..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['about_full_history']) ?></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Corporate Mission Statement</label>
          <textarea name="about_mission" rows="3" required placeholder="To empower organizations globally..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['about_mission']) ?></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Corporate Vision Statement</label>
          <textarea name="about_vision" rows="3" required placeholder="To be the preferred global outsourcing partner..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['about_vision']) ?></textarea>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          Save About Settings
        </button>
      </form>
    </div>
  </main>

</body>
</html>
