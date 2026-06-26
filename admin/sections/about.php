<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'About Page CMS | Clevora Admin';
$msg = ''; $error = '';
$flash = get_flash();
if ($flash) { $flash['type'] === 'success' ? $msg = $flash['message'] : $error = $flash['message']; }

// ── Handle POST ─────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save_about') {
            $fields = ['page_title','intro','company_story','problem_section','solution_section','mission','vision'];
            $existing = $pdo->query("SELECT id FROM about_page LIMIT 1")->fetch();
            if ($existing) {
                $set = implode(', ', array_map(fn($f) => "$f = ?", $fields));
                $stmt = $pdo->prepare("UPDATE about_page SET $set WHERE id = ?");
                $vals = array_map(fn($f) => trim($_POST[$f] ?? ''), $fields);
                $vals[] = $existing['id'];
                $stmt->execute($vals);
            } else {
                $cols = implode(', ', $fields);
                $placeholders = implode(', ', array_fill(0, count($fields), '?'));
                $stmt = $pdo->prepare("INSERT INTO about_page ($cols) VALUES ($placeholders)");
                $stmt->execute(array_map(fn($f) => trim($_POST[$f] ?? ''), $fields));
            }
            header("Location: about.php?success=About page updated successfully.");
            exit;
        }
        if ($action === 'save_value') {
            $id = (int)($_POST['value_id'] ?? 0);
            $title = trim($_POST['value_title'] ?? '');
            $description = trim($_POST['value_description'] ?? '');
            $icon = trim($_POST['value_icon'] ?? '⭐');
            $sort_order = (int)($_POST['value_sort'] ?? 0);
            $is_active = isset($_POST['value_status']) ? 1 : 0;
            
            if ($id > 0) {
                $stmt = $pdo->prepare("UPDATE about_values SET title=?, description=?, icon=?, sort_order=?, status=? WHERE id=?");
                $stmt->execute([$title, $description, $icon, $sort_order, $is_active, $id]);
                header("Location: about.php?success=Value updated.");
                exit;
            } else {
                $stmt = $pdo->prepare("INSERT INTO about_values (title, description, icon, sort_order, status) VALUES (?,?,?,?,?)");
                $stmt->execute([$title, $description, $icon, $sort_order, $is_active]);
                header("Location: about.php?success=Value added.");
                exit;
            }
        }
        if ($action === 'delete_value') {
            $pdo->prepare("DELETE FROM about_values WHERE id = ?")->execute([(int)($_POST['value_id'] ?? 0)]);
            header("Location: about.php?success=Value deleted.");
            exit;
        }
    } catch (Exception $e) { $error = 'Error: ' . $e->getMessage(); }
}

// ── Fetch Data ──────────────────────────────────────────
$about = $pdo ? ($pdo->query("SELECT * FROM about_page LIMIT 1")->fetch() ?: []) : [];
$values = $pdo ? $pdo->query("SELECT * FROM about_values ORDER BY sort_order")->fetchAll() : [];
$edit_value = null;
if (isset($_GET['edit_value']) && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM about_values WHERE id=?");
    $stmt->execute([(int)$_GET['edit_value']]);
    $edit_value = $stmt->fetch();
}
?>
<?php include '../includes/admin-header.php'; ?>
  <?php include '../includes/sidebar.php'; ?>
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-4xl mx-auto space-y-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">About Page CMS</h1>
        <p class="text-xs text-gray-400 mt-1">Manage company story, mission, vision, and core values.</p>
      </div>
      <?php if($msg): ?><div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if($error): ?><div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <!-- About Content -->
      <form method="POST" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save_about">
        <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">Page Content</h2>
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Page Title</label>
          <input type="text" name="page_title" value="<?= htmlspecialchars($about['page_title'] ?? 'About Clevora') ?>"
                 class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition">
        </div>
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Introduction</label>
          <textarea name="intro" rows="3" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($about['intro'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Company Story</label>
          <textarea name="company_story" rows="6" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($about['company_story'] ?? '') ?></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Problem Section</label>
            <textarea name="problem_section" rows="4" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($about['problem_section'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Solution Section</label>
            <textarea name="solution_section" rows="4" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($about['solution_section'] ?? '') ?></textarea>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Mission</label>
            <textarea name="mission" rows="3" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($about['mission'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Vision</label>
            <textarea name="vision" rows="3" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($about['vision'] ?? '') ?></textarea>
          </div>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition">Save About Page</button>
      </form>

      <!-- Core Values -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-50">
          <h2 class="font-bold text-gray-800 text-sm">Core Values</h2>
          <p class="text-[10px] text-gray-400"><?= count($values) ?> value<?= count($values)!==1?'s':'' ?> defined</p>
        </div>
        <div class="p-5 space-y-3">
          <?php foreach($values as $v): ?>
          <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3 border border-gray-100">
            <span class="text-lg"><?= htmlspecialchars($v['icon']) ?></span>
            <div class="flex-1"><p class="text-xs font-bold text-gray-800"><?= htmlspecialchars($v['title']) ?></p></div>
            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $v['status']?'bg-green-50 text-green-700':'bg-gray-100 text-gray-500' ?>"><?= $v['status']?'Active':'Hidden' ?></span>
            <div class="flex gap-2 text-xs">
              <a href="?edit_value=<?= $v['id'] ?>" class="text-blue-600 font-semibold">Edit</a>
              <form method="POST" class="inline" onsubmit="return confirm('Delete?')">
                <?= csrf_field() ?><input type="hidden" name="action" value="delete_value"><input type="hidden" name="value_id" value="<?= $v['id'] ?>">
                <button class="text-red-500 font-semibold">Delete</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <form method="POST" class="border-t border-gray-100 p-5 space-y-3">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="save_value">
          <input type="hidden" name="value_id" value="<?= $edit_value['id'] ?? 0 ?>">
          <h3 class="text-xs font-bold text-gray-700 uppercase"><?= $edit_value ? 'Edit Value' : 'Add Value' ?></h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Title *</label>
              <input type="text" name="value_title" required value="<?= htmlspecialchars($edit_value['title'] ?? '') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Icon</label>
              <input type="text" name="value_icon" value="<?= htmlspecialchars($edit_value['icon'] ?? '⭐') ?>"
                     class="w-20 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
            </div>
            <div class="flex items-end gap-3">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort</label>
                <input type="number" name="value_sort" value="<?= $edit_value['sort_order'] ?? 0 ?>"
                       class="w-16 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
              <label class="flex items-center gap-2 text-xs text-gray-600 pb-1 cursor-pointer">
                <input type="checkbox" name="value_status" value="1" <?= (!$edit_value||($edit_value['status']??1))?'checked':'' ?> class="rounded text-blue-600"> Active
              </label>
            </div>
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description</label>
            <textarea name="value_description" rows="2" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($edit_value['description'] ?? '') ?></textarea>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition"><?= $edit_value ? 'Update' : 'Add Value' ?></button>
            <?php if($edit_value): ?><a href="about.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-xs uppercase transition">Cancel</a><?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </main>
<?php include '../includes/admin-footer.php'; ?>
