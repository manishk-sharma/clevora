<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Contact & Social Settings | Clevora Admin';
$msg = '';
$error = '';

$keys = [
    'contact_phone',
    'contact_email',
    'contact_address',
    'contact_whatsapp',
    'contact_hours',
    'contact_map_embed',
    'social_facebook',
    'social_linkedin',
    'social_xing',
    'social_linktree',
    'footer_subscribe_text'
];

// Handle update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    if ($pdo) {
        try {
            $pdo->beginTransaction();
            foreach ($keys as $k) {
                $v = trim($_POST[$k] ?? '');
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $v, $v]);
            }
            $pdo->commit();
            $msg = 'Contact and social settings updated successfully.';
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
<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-4xl mx-auto space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Contact & Social Settings</h1>
        <p class="text-xs text-gray-400 mt-1">Configure global business coordinates, embeds, and social channels displayed on the footer and contact page.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- CARD 1: Business Details -->
          <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Business Details</h2>
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
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">WhatsApp Link or Number</label>
              <input type="text" name="contact_whatsapp" value="<?= htmlspecialchars($settings['contact_whatsapp']) ?>" placeholder="e.g. +919953310085"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Office Address</label>
              <textarea name="contact_address" required rows="3" placeholder="Office physical coordinates..."
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['contact_address']) ?></textarea>
            </div>
          </div>

          <!-- CARD 2: Maps, Hours & Footer Text -->
          <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Hours & Embeds</h2>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Business Hours</label>
              <input type="text" name="contact_hours" value="<?= htmlspecialchars($settings['contact_hours']) ?>" placeholder="e.g. Mon - Sat: 9:00 AM - 6:00 PM"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Google Map Embed URL</label>
              <p class="text-[9px] text-gray-400 mb-1">Paste the source URL from map embed iframe src attribute.</p>
              <input type="text" name="contact_map_embed" value="<?= htmlspecialchars($settings['contact_map_embed']) ?>" placeholder="https://www.google.com/maps/embed?..."
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Footer Description (Newsletter section)</label>
              <textarea name="footer_subscribe_text" rows="3" placeholder="Footer subscribe text tag..."
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['footer_subscribe_text']) ?></textarea>
            </div>
          </div>
        </div>

        <!-- CARD 3: Social Channels -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Social Channels</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Facebook Page URL</label>
              <input type="url" name="social_facebook" value="<?= htmlspecialchars($settings['social_facebook']) ?>" placeholder="https://facebook.com/..."
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">LinkedIn Page URL</label>
              <input type="url" name="social_linkedin" value="<?= htmlspecialchars($settings['social_linkedin']) ?>" placeholder="https://linkedin.com/company/..."
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Xing Profile URL</label>
              <input type="url" name="social_xing" value="<?= htmlspecialchars($settings['social_xing']) ?>" placeholder="https://xing.com/profile/..."
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Linktree / General Link URL</label>
              <input type="url" name="social_linktree" value="<?= htmlspecialchars($settings['social_linktree']) ?>" placeholder="https://linktr.ee/..."
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
          </div>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition duration-300">
          Save Contact & Social Details
        </button>
      </form>
    </div>
  </main>

<?php include '../includes/admin-footer.php'; ?>
