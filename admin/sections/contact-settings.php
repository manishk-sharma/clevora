<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

$keys = ['contact_phone', 'contact_email', 'contact_address'];

// Handle update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    if ($pdo) {
        try {
            $pdo->beginTransaction();
            foreach ($keys as $k) {
                $v = trim($_POST[$k] ?? '');
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $v, $v]);
            }
            $pdo->commit();
            $msg = 'Contact details updated successfully.';
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

// Fetch leads list
$leads = [];
if ($pdo) {
    try {
        $leads = $pdo->query("SELECT * FROM leads ORDER BY id DESC")->fetchAll();
    } catch(Exception $e) {
        $error = 'Failed to fetch contact leads: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Settings & Leads | Clevora Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="flex bg-gray-50 min-h-screen font-sans">

  <!-- SIDEBAR -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-8 md:p-12">
    <div class="max-w-5xl mx-auto space-y-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Contact Page & Lead Inquiries</h1>
        <p class="text-xs text-gray-400">Configure public business details and inspect lead notifications submitted by prospects.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Settings Form -->
        <form method="POST" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <input type="hidden" name="save_settings" value="1">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">Business Details</h2>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Phone Number</label>
            <input type="text" name="contact_phone" required value="<?= htmlspecialchars($settings['contact_phone']) ?>" placeholder="+91 9953310085"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Email Address</label>
            <input type="email" name="contact_email" required value="<?= htmlspecialchars($settings['contact_email']) ?>" placeholder="info@clevora.in"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Office Address</label>
            <textarea name="contact_address" required rows="3" placeholder="Office physical coordinates..."
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['contact_address']) ?></textarea>
          </div>
          <button type="submit"
                  class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition duration-300">
            Save Details
          </button>
        </form>

        <!-- Leads Viewer List -->
        <div class="lg:col-span-2 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-2">Prospect Leads Logs</h2>
          <div class="space-y-4">
            <?php if(empty($leads)): ?>
            <div class="bg-white rounded-xl border p-8 text-center text-xs text-gray-400">No submission records logged yet.</div>
            <?php else: ?>
              <?php foreach($leads as $l): ?>
              <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm space-y-3 relative">
                <span class="absolute top-4 right-4 text-[10px] text-gray-400 font-semibold"><?= htmlspecialchars($l['created_at']) ?></span>
                <div class="space-y-1">
                  <h4 class="font-bold text-gray-800 text-xs"><?= htmlspecialchars($l['name']) ?></h4>
                  <div class="flex gap-2 text-[10px] text-gray-500 font-medium">
                    <span>✉ <?= htmlspecialchars($l['email']) ?></span>
                    <span>📞 <?= htmlspecialchars($l['phone'] ?: 'No Phone') ?></span>
                  </div>
                </div>
                <div class="text-xs text-gray-400">
                  <span class="font-bold text-gray-600 uppercase text-[9px] bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Interest: <?= htmlspecialchars($l['interest'] ?: 'General') ?></span>
                </div>
                <p class="bg-gray-50 p-2.5 rounded text-gray-600 text-xs whitespace-pre-line leading-relaxed border border-gray-100">
                  <?= htmlspecialchars($l['message']) ?>
                </p>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
</html>
