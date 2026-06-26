<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Career Page Settings | Clevora Admin';
$msg = '';
$error = '';

$keys = [
    'hero_label',
    'hero_title',
    'main_heading',
    'intro_text',
    'apply_heading',
    'apply_text',
    'benefit_cards'
];

function get_career_setting(string $key, ?PDO $pdo): string {
    if (!$pdo) return '';
    try {
        $stmt = $pdo->prepare("SELECT section_value FROM career_settings WHERE section_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : '';
    } catch (Exception $e) {
        return '';
    }
}

// Handle update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    if ($pdo) {
        try {
            $pdo->beginTransaction();
            
            // 1. Save general fields
            $general_fields = ['hero_label', 'hero_title', 'main_heading', 'intro_text', 'apply_heading', 'apply_text'];
            foreach ($general_fields as $f) {
                $v = trim($_POST[$f] ?? '');
                $stmt = $pdo->prepare("INSERT INTO career_settings (section_key, section_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE section_value = ?");
                $stmt->execute([$f, $v, $v]);
            }

            // 2. Save benefit cards repeater
            $benefits = [];
            if (isset($_POST['benefit_titles']) && is_array($_POST['benefit_titles'])) {
                foreach ($_POST['benefit_titles'] as $idx => $title) {
                    $title = trim($title);
                    $desc = trim($_POST['benefit_descriptions'][$idx] ?? '');
                    if (!empty($title)) {
                        $benefits[] = [
                            'title' => $title,
                            'description' => $desc
                        ];
                    }
                }
            }
            $benefit_cards_json = json_encode($benefits);
            
            $stmt = $pdo->prepare("INSERT INTO career_settings (section_key, section_value) VALUES ('benefit_cards', ?) ON DUPLICATE KEY UPDATE section_value = ?");
            $stmt->execute([$benefit_cards_json, $benefit_cards_json]);

            $pdo->commit();
            $msg = 'Career settings and benefits updated successfully.';
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = 'Database update failed: ' . $e->getMessage();
        }
    }
}

// Fetch current values
$settings = [];
foreach ($keys as $k) {
    $settings[$k] = get_career_setting($k, $pdo);
}
?>

<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-1 p-6 md:p-10 overflow-auto">
  <div class="max-w-4xl mx-auto space-y-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-800 font-poppins">Career Page Settings</h1>
      <p class="text-xs text-gray-400 mt-1">Configure general texts, benefits grid, and application instructions displayed on the Careers page.</p>
    </div>

    <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
      <?= csrf_field() ?>

      <!-- Section 1: Hero & Info Texts -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Hero & Header Settings</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Hero Label / Kicker</label>
            <input type="text" name="hero_label" required value="<?= htmlspecialchars($settings['hero_label'] ?: 'Clevora Global Operations') ?>" placeholder="e.g. Why Clevora"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Hero Page Title</label>
            <input type="text" name="hero_title" required value="<?= htmlspecialchars($settings['hero_title'] ?: 'Careers') ?>" placeholder="e.g. Careers"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
        </div>

        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Main Content Heading</label>
          <input type="text" name="main_heading" required value="<?= htmlspecialchars($settings['main_heading'] ?: 'Build Your Career With Clevora') ?>" placeholder="e.g. Build Your Career With Clevora"
                 class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
        </div>

        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Introduction Description Text</label>
          <textarea name="intro_text" required rows="3" placeholder="Provide description text..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['intro_text'] ?: 'Join a growing outsourcing company where talented people work with global businesses and develop professional skills.') ?></textarea>
        </div>
      </div>

      <!-- Section 2: Benefit Cards Repeater -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex justify-between items-center border-b pb-2 mb-2">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Benefit Cards List</h2>
          <button type="button" data-repeater-add="#benefits-list" class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded transition">
            + Add Benefit Card
          </button>
        </div>

        <div id="benefits-list" class="space-y-3">
          <!-- Template Row -->
          <div data-repeater-template style="display: none;" class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
            <div>
              <input type="text" name="benefit_titles[]" placeholder="Benefit Title" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
            </div>
            <div class="md:col-span-2 flex items-center gap-2">
              <textarea name="benefit_descriptions[]" placeholder="Benefit description text..." rows="1" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none resize-none"></textarea>
              <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
            </div>
          </div>

          <!-- Existing Rows -->
          <?php
          $benefits_cards = json_decode($settings['benefit_cards'] ?? '[]', true);
          foreach ($benefits_cards as $b):
          ?>
          <div class="repeater-row grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
            <div>
              <input type="text" name="benefit_titles[]" value="<?= htmlspecialchars($b['title']) ?>" placeholder="Benefit Title" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
            </div>
            <div class="md:col-span-2 flex items-center gap-2">
              <textarea name="benefit_descriptions[]" placeholder="Benefit description text..." rows="1" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none resize-none"><?= htmlspecialchars($b['description']) ?></textarea>
              <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Section 3: Application Text settings -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Application Call-To-Action (CTA) Settings</h2>
        
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Application Heading</label>
          <input type="text" name="apply_heading" required value="<?= htmlspecialchars($settings['apply_heading'] ?: 'Ready to join us?') ?>" placeholder="e.g. Ready to join us?"
                 class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
        </div>

        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Application Instruction Text (HTML allowed)</label>
          <textarea name="apply_text" required rows="3" placeholder="Provide application instruction details..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['apply_text'] ?: 'Send your resume and a brief cover letter to <a href="mailto:info@clevora.in" style="color:#2563eb; font-weight:600; text-decoration:none; border-bottom:1px solid #bfdbfe;">info@clevora.in</a>. Include the role you\'re applying for in the subject line. We\'ll get back to you within 3 business days.') ?></textarea>
        </div>
      </div>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition duration-300">
        Save Career Page Settings
      </button>
    </form>
  </div>
</main>

<?php include '../includes/admin-footer.php'; ?>
