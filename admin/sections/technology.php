<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Technology Management | Clevora Admin';
$msg = '';
$error = '';

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

$settings_keys = [
    'hero_small_text',
    'hero_title',
    'breadcrumb_title',
    'main_label',
    'main_heading',
    'main_description',
    'security_title',
    'security_description',
    'security_badge'
];

function get_tech_setting(string $key, ?PDO $pdo): string {
    if (!$pdo) return '';
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM technology_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : '';
    } catch (Exception $e) {
        return '';
    }
}

// 1. Handle Delete Card
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM technology_sections WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: technology.php?success=Technology card deleted successfully.');
        exit;
    } catch(Exception $e) {
        $error = 'Error deleting card: ' . $e->getMessage();
    }
}

// 2. Handle Status Toggle
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    try {
        $stmt = $pdo->prepare("UPDATE technology_sections SET is_active = 1 - is_active WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: technology.php?success=Card status updated.');
        exit;
    } catch (Exception $e) {
        $error = 'Error toggling status: ' . $e->getMessage();
    }
}

// 3. Handle Add / Edit Card Submission & Page Settings Update
$action = isset($_GET['action']) ? $_GET['action'] : '';
$edit_id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $form_type = $_POST['form_type'] ?? '';

    if ($form_type === 'page_settings') {
        // Save general page settings
        if ($pdo) {
            try {
                $pdo->beginTransaction();
                foreach ($settings_keys as $key) {
                    $val = trim($_POST[$key] ?? '');
                    $stmt = $pdo->prepare("INSERT INTO technology_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                    $stmt->execute([$key, $val, $val]);
                }
                $pdo->commit();
                $msg = 'Technology page settings updated successfully.';
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Database update failed: ' . $e->getMessage();
            }
        }
    } elseif ($form_type === 'card_save') {
        // Save Card (Add or Edit)
        $section_title = trim($_POST['section_title'] ?? '');
        $section_key = trim($_POST['section_key'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $badge_text = trim($_POST['badge_text'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($section_title)) {
            $error = 'Card Title is a required field.';
        } else {
            // Generate section key if empty
            if (empty($section_key)) {
                $section_key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $section_title));
            }

            try {
                if ($edit_id > 0) {
                    // Update
                    $stmt = $pdo->prepare("
                        UPDATE technology_sections 
                        SET section_key = ?, section_title = ?, subtitle = ?, description = ?, icon = ?, badge_text = ?, sort_order = ?, is_active = ? 
                        WHERE id = ?
                    ");
                    $stmt->execute([$section_key, $section_title, $subtitle, $description, $icon, $badge_text, $sort_order, $is_active, $edit_id]);
                    header('Location: technology.php?success=Technology card updated successfully.');
                    exit;
                } else {
                    // Insert
                    $stmt = $pdo->prepare("
                        INSERT INTO technology_sections (section_key, section_title, subtitle, description, icon, badge_text, sort_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$section_key, $section_title, $subtitle, $description, $icon, $badge_text, $sort_order, $is_active]);
                    header('Location: technology.php?success=New technology card added successfully.');
                    exit;
                }
            } catch (Exception $e) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $error = 'A card with this section key or slug already exists.';
                } else {
                    $error = 'Database operation failed: ' . $e->getMessage();
                }
            }
        }
    }
}

// 4. Fetch Card Data for Editing
$card = null;
if ($edit_id > 0 && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM technology_sections WHERE id = ?");
    $stmt->execute([$edit_id]);
    $card = $stmt->fetch();
    if (!$card) {
        header('Location: technology.php?error=Card not found.');
        exit;
    }
}

// 5. Fetch Settings and Cards
$settings = [];
foreach ($settings_keys as $k) {
    $settings[$k] = get_tech_setting($k, $pdo);
}

$cards = [];
if ($pdo) {
    try {
        $cards = $pdo->query("SELECT * FROM technology_sections ORDER BY sort_order ASC, id DESC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Failed to fetch cards: ' . $e->getMessage();
    }
}
?>

<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-1 p-6 md:p-10 overflow-auto">
  <div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Top Header -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Technology Management</h1>
        <p class="text-xs text-gray-400 mt-1">Manage the dynamic cards, settings, and main descriptions of your Technology page.</p>
      </div>
      <?php if (empty($action)): ?>
        <a href="technology.php?action=add" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          + Add Tech Card
        </a>
      <?php else: ?>
        <a href="technology.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2 rounded-lg text-xs uppercase tracking-wider transition-all">
          ← Back to CMS
        </a>
      <?php endif; ?>
    </div>

    <!-- Feedback messages -->
    <?php if (!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Action View: Add or Edit Card Form -->
    <?php if ($action === 'add' || $action === 'edit' || $card): ?>
      <form method="POST" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 md:p-8 space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="form_type" value="card_save">
        <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-3"><?= $card ? 'Edit Technology Card' : 'Add New Technology Card' ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Card Title -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Card Title *</label>
            <input type="text" name="section_title" id="section_title" required
                   value="<?= htmlspecialchars($card['section_title'] ?? '') ?>" placeholder="e.g. Quality Monitoring"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>

          <!-- Section Key -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Section Key / Slug</label>
            <input type="text" name="section_key" id="section_key"
                   value="<?= htmlspecialchars($card['section_key'] ?? '') ?>" placeholder="e.g. quality-monitoring"
                   data-slug-source="#section_title"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
          </div>

          <!-- Icon (Emoji or Icon name) -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Icon (Emoji or String) *</label>
            <input type="text" name="icon" required
                   value="<?= htmlspecialchars($card['icon'] ?? '🔧') ?>" placeholder="e.g. 📊"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-sans">
          </div>

          <!-- Subtitle -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Card Subtitle (Optional)</label>
            <input type="text" name="subtitle"
                   value="<?= htmlspecialchars($card['subtitle'] ?? '') ?>" placeholder="e.g. SLA metrics"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>

          <!-- Badge Text -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Badge Text (Optional)</label>
            <input type="text" name="badge_text"
                   value="<?= htmlspecialchars($card['badge_text'] ?? '') ?>" placeholder="e.g. Audits"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>

          <!-- Sort Order -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" value="<?= $card['sort_order'] ?? 0 ?>"
                   class="w-32 bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Card Description *</label>
          <textarea name="description" required rows="4" placeholder="Describe this technology standard..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($card['description'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center border-t pt-4">
          <label class="flex items-center gap-2.5 text-xs text-gray-700 font-semibold cursor-pointer">
            <input type="checkbox" name="is_active" value="1" <?= (!isset($card) || ($card['is_active'] ?? 1)) ? 'checked' : '' ?> 
                   class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
            Publish Card (Visible on Technology Page)
          </label>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition duration-300">
          <?= $card ? 'Save Changes' : 'Create Card' ?>
        </button>
      </form>

    <!-- Default CMS View: Page Settings + Card List -->
    <?php else: ?>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- List of Cards -->
        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center">
              <div>
                <h2 class="font-bold text-gray-800 text-sm">Technology Cards</h2>
                <p class="text-[10px] text-gray-400"><?= count($cards) ?> card<?= count($cards) !== 1 ? 's' : '' ?> configured</p>
              </div>
            </div>
            
            <div class="divide-y divide-gray-50">
              <?php if (empty($cards)): ?>
                <div class="text-center p-8 text-gray-400">No technology cards found. Click "+ Add Tech Card" to create one.</div>
              <?php else: ?>
                <?php foreach ($cards as $c): ?>
                  <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition">
                    <span class="text-xl w-10 text-center"><?= htmlspecialchars($c['icon']) ?></span>
                    <div class="flex-1 min-w-0">
                      <p class="text-xs font-bold text-gray-800"><?= htmlspecialchars($c['section_title']) ?></p>
                      <p class="text-[10px] text-gray-400 font-mono">Key: <?= htmlspecialchars($c['section_key']) ?> · Sort: <?= $c['sort_order'] ?></p>
                    </div>
                    <a href="technology.php?toggle_status=<?= $c['id'] ?>" title="Click to toggle status"
                       class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider <?= $c['is_active'] ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' ?>">
                      <?= $c['is_active'] ? 'Active' : 'Disabled' ?>
                    </a>
                    <div class="flex gap-2 text-xs pl-2">
                      <a href="technology.php?action=edit&id=<?= $c['id'] ?>" class="text-blue-600 font-semibold hover:text-blue-800">Edit</a>
                      <a href="technology.php?delete=<?= $c['id'] ?>" data-confirm="Are you sure you want to delete this tech card?" class="text-red-500 font-semibold hover:text-red-700">Delete</a>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Page Settings Editor -->
        <div class="space-y-6">
          <form method="POST" class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="form_type" value="page_settings">
            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider border-b pb-2 mb-2">Page Header Settings</h3>
            
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Hero Small Text</label>
              <input type="text" name="hero_small_text" required value="<?= htmlspecialchars($settings['hero_small_text'] ?: 'Clevora Global Operations') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Hero Title</label>
              <input type="text" name="hero_title" required value="<?= htmlspecialchars($settings['hero_title'] ?: 'Technology') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Breadcrumb Title</label>
              <input type="text" name="breadcrumb_title" required value="<?= htmlspecialchars($settings['breadcrumb_title'] ?: 'Technology') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider border-t pt-4 pb-2 mb-2">Main Content Section</h3>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Main Section Label</label>
              <input type="text" name="main_label" required value="<?= htmlspecialchars($settings['main_label'] ?: 'Our Capabilities') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Main Heading</label>
              <input type="text" name="main_heading" required value="<?= htmlspecialchars($settings['main_heading'] ?: 'Secure Operations & Technology Infrastructure') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Main Description</label>
              <textarea name="main_description" required rows="4"
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['main_description'] ?: '') ?></textarea>
            </div>

            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider border-t pt-4 pb-2 mb-2">Security Highlight Card</h3>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Highlight Card Title</label>
              <input type="text" name="security_title" required value="<?= htmlspecialchars($settings['security_title'] ?: 'Data Protection & Security') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Highlight Description</label>
              <textarea name="security_description" required rows="3"
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['security_description'] ?: '') ?></textarea>
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Highlight Badge text</label>
              <input type="text" name="security_badge" required value="<?= htmlspecialchars($settings['security_badge'] ?: 'Security & Compliance Assured') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded-lg text-xs uppercase tracking-wider transition">
              Save Page Settings
            </button>
          </form>
        </div>

      </div>
    <?php endif; ?>

  </div>
</main>

<?php include '../includes/admin-footer.php'; ?>
