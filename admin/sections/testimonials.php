<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Manage Testimonials | Clevora Admin';
$msg = ''; $error = ''; $edit_t = null;
if (isset($_GET['success'])) $msg = $_GET['success'];

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id=?"); $stmt->execute([(int)$_GET['edit']]); $edit_t = $stmt->fetch();
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT photo_url FROM testimonials WHERE id=?"); $stmt->execute([$id]); $row = $stmt->fetch();
        if ($row && !empty($row['photo_url'])) { $p = __DIR__ . '/../../' . ltrim($row['photo_url'], '/'); if (file_exists($p)) @unlink($p); }
        $pdo->prepare("DELETE FROM testimonials WHERE id=?")->execute([$id]);
        header('Location: testimonials.php?success=Testimonial deleted.'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $quote = trim($_POST['quote'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $industry = trim($_POST['industry'] ?? '');
    $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $active = isset($_POST['is_active']) ? 1 : 0;
    $submit_id = (int)($_POST['id'] ?? 0);

    if (empty($name) || empty($quote)) { $error = 'Name and Quote are required.'; }
    else {
        $photo_url = $edit_t['photo_url'] ?? '';
        if (!empty($_FILES['photo']['name'])) {
            $v = validate_upload($_FILES['photo']); 
            if ($v === true) { $url = save_upload($_FILES['photo'], 'testimonial'); if ($url) { 
                if ($submit_id && !empty($edit_t['photo_url'])) { $old = __DIR__.'/../../'.ltrim($edit_t['photo_url'],'/'); if(file_exists($old)) @unlink($old); }
                $photo_url = $url;
            }} else { $error = $v; }
        }
        if (empty($error)) {
            try {
                if ($submit_id) {
                    $pdo->prepare("UPDATE testimonials SET name=?, location=?, quote=?, photo_url=?, position=?, company=?, industry=?, rating=?, is_active=? WHERE id=?")
                        ->execute([$name, $location, $quote, $photo_url, $position, $company, $industry, $rating, $active, $submit_id]);
                    header('Location: testimonials.php?success=Testimonial updated.'); exit;
                } else {
                    $pdo->prepare("INSERT INTO testimonials (name, location, quote, photo_url, position, company, industry, rating, is_active) VALUES (?,?,?,?,?,?,?,?,?)")
                        ->execute([$name, $location, $quote, $photo_url, $position, $company, $industry, $rating, $active]);
                    header('Location: testimonials.php?success=Testimonial added.'); exit;
                }
            } catch (Exception $e) { $error = $e->getMessage(); }
        }
    }
}

$testimonials = $pdo ? $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll() : [];
?>
<?php include '../includes/admin-header.php'; ?>
  <?php include '../includes/sidebar.php'; ?>
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-5xl mx-auto space-y-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Manage Testimonials</h1>
        <p class="text-xs text-gray-400 mt-1">Add, edit, or delete customer reviews displayed on the homepage slider.</p>
      </div>
      <?php if($msg): ?><div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if($error): ?><div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <?= csrf_field() ?>
          <?php if($edit_t): ?><input type="hidden" name="id" value="<?= $edit_t['id'] ?>"><?php endif; ?>
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2"><?= $edit_t ? 'Edit Testimonial' : 'Create Testimonial' ?></h2>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Client Name *</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($edit_t['name'] ?? '') ?>" placeholder="e.g. John Doe"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Position</label>
              <input type="text" name="position" value="<?= htmlspecialchars($edit_t['position'] ?? '') ?>" placeholder="e.g. CEO"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Company</label>
              <input type="text" name="company" value="<?= htmlspecialchars($edit_t['company'] ?? '') ?>" placeholder="e.g. Acme Inc."
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Location</label>
              <input type="text" name="location" value="<?= htmlspecialchars($edit_t['location'] ?? '') ?>" placeholder="e.g. New York, USA"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Industry</label>
              <input type="text" name="industry" value="<?= htmlspecialchars($edit_t['industry'] ?? '') ?>" placeholder="e.g. E-Commerce"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
            </div>
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Photo</label>
            <input type="file" name="photo" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
            <?php if ($edit_t && !empty($edit_t['photo_url'])): ?>
            <p class="mt-1 text-[10px] text-gray-400">Current: <a href="<?= htmlspecialchars($edit_t['photo_url']) ?>" target="_blank" class="text-blue-500 hover:underline"><?= basename($edit_t['photo_url']) ?></a></p>
            <?php endif; ?>
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Quote *</label>
            <textarea name="quote" required rows="4" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($edit_t['quote'] ?? '') ?></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Rating</label>
              <select name="rating" class="w-full bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                <?php for($r=5;$r>=1;$r--): ?>
                <option value="<?= $r ?>" <?= (($edit_t['rating']??5)==$r)?'selected':'' ?>><?= str_repeat('⭐',$r) ?> (<?= $r ?>)</option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="flex items-end pb-1">
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?= (!isset($edit_t['is_active'])||$edit_t['is_active']==1)?'checked':'' ?> class="rounded text-blue-600"> Active & Visible
              </label>
            </div>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition"><?= $edit_t ? 'Save Changes' : 'Add Testimonial' ?></button>
            <?php if($edit_t): ?><a href="testimonials.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-3 py-2.5 rounded text-xs uppercase transition">Cancel</a><?php endif; ?>
          </div>
        </form>

        <div class="lg:col-span-2 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-2">All Testimonials (<?= count($testimonials) ?>)</h2>
          <?php if(empty($testimonials)): ?>
          <div class="bg-white rounded-xl border p-8 text-center text-xs text-gray-400">No testimonials created yet.</div>
          <?php else: foreach($testimonials as $t): ?>
          <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm flex gap-4">
            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-50 shrink-0 border">
              <?php if(!empty($t['photo_url'])): ?><img src="<?= htmlspecialchars($t['photo_url']) ?>" class="w-full h-full object-cover">
              <?php else: ?><div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm"><?= strtoupper(substr($t['name'],0,1)) ?></div><?php endif; ?>
            </div>
            <div class="flex-1 space-y-2">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-bold text-gray-800 text-xs"><?= htmlspecialchars($t['name']) ?></h4>
                  <p class="text-[10px] text-gray-400"><?= htmlspecialchars(($t['position']??'').($t['company']?' at '.$t['company']:'')) ?> · <?= htmlspecialchars($t['location'] ?? '') ?></p>
                </div>
                <div class="flex gap-3 text-xs">
                  <a href="?edit=<?= $t['id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                  <a href="?delete=<?= $t['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Delete?')">Delete</a>
                </div>
              </div>
              <p class="text-[11px] text-amber-500"><?= str_repeat('⭐', $t['rating'] ?? 5) ?></p>
              <p class="text-gray-600 text-xs italic">"<?= htmlspecialchars(substr($t['quote'],0,120)) ?>..."</p>
              <span class="inline-block px-2.5 py-0.5 text-[9px] font-bold uppercase rounded-full <?= $t['is_active']?'bg-green-50 text-green-700 border border-green-100':'bg-gray-100 text-gray-500' ?>"><?= $t['is_active']?'Active':'Hidden' ?></span>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>
  </main>
<?php include '../includes/admin-footer.php'; ?>
