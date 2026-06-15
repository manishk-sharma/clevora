<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

$keys = [
    'hero_headline', 'hero_bullets', 'hero_cta_text', 'about_home_text',
    'stats_projects', 'stats_industries', 'stats_resumes', 'stats_clients'
];

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
            $msg = 'Homepage settings updated successfully.';
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
  <title>Homepage Snippet CMS | Clevora Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="flex bg-gray-50 min-h-screen font-sans">

  <!-- SIDEBAR -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-8 md:p-12">
    <div class="max-w-4xl mx-auto space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Edit Homepage & Hero Settings</h1>
        <p class="text-xs text-gray-400">Manage hero headings, bullet points, statistics counters, and landing snippets.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
        <!-- Hero settings -->
        <div class="space-y-4">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">1. Hero Banner Content</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Hero Headline</label>
              <input type="text" name="hero_headline" required value="<?= htmlspecialchars($settings['hero_headline']) ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Hero CTA Button Text</label>
              <input type="text" name="hero_cta_text" required value="<?= htmlspecialchars($settings['hero_cta_text']) ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all">
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Hero Bullet Points (One per line)</label>
            <p class="text-[10px] text-gray-400 mb-2">List details that will display below the headline on the banner.</p>
            <textarea name="hero_bullets" rows="4" required placeholder="Bullet details..."
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['hero_bullets']) ?></textarea>
          </div>
        </div>

        <!-- Home About -->
        <div class="space-y-4 pt-4 border-t border-gray-100">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">2. About Us Sneak Peek (index.php)</h2>
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">About Teaser Text</label>
            <textarea name="about_home_text" rows="5" required placeholder="Short dynamic snippet on homepage..."
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['about_home_text']) ?></textarea>
          </div>
        </div>

        <!-- Statistics counters -->
        <div class="space-y-4 pt-4 border-t border-gray-100">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">3. Statistics Counter Targets</h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Outsourcing Projects</label>
              <input type="number" name="stats_projects" required value="<?= htmlspecialchars($settings['stats_projects']) ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Industries Served</label>
              <input type="number" name="stats_industries" required value="<?= htmlspecialchars($settings['stats_industries']) ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Resumes Revised</label>
              <input type="number" name="stats_resumes" required value="<?= htmlspecialchars($settings['stats_resumes']) ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Happy Clients</label>
              <input type="number" name="stats_clients" required value="<?= htmlspecialchars($settings['stats_clients']) ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>
          </div>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          Save Home Settings
        </button>
      </form>
    </div>
  </main>

</body>
</html>
