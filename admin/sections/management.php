<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Founder / Management | Clevora Admin';
$msg = ''; $error = '';
$flash = get_flash(); if ($flash) { $flash['type']==='success' ? $msg=$flash['message'] : $error=$flash['message']; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    try {
        $fields = ['name','role','bio','message','experience_text'];
        $social = json_encode([
            'linkedin' => trim($_POST['social_linkedin'] ?? ''),
            'facebook' => trim($_POST['social_facebook'] ?? ''),
            'twitter'  => trim($_POST['social_twitter'] ?? ''),
            'website'  => trim($_POST['social_website'] ?? ''),
        ]);
        
        // Image upload
        $image = $_POST['existing_image'] ?? '/assets/images/founder.jpg';
        if (!empty($_FILES['founder_photo']['name'])) {
            $v = validate_upload($_FILES['founder_photo']);
            if ($v === true) {
                $url = save_upload($_FILES['founder_photo'], 'founder');
                if ($url) $image = $url;
            } else { $error = $v; }
        }
        
        if (empty($error)) {
            $existing = $pdo->query("SELECT id FROM founder LIMIT 1")->fetch();
            $vals = array_map(fn($f) => trim($_POST[$f] ?? ''), $fields);
            $vals[] = $social;
            $vals[] = $image;
            
            if ($existing) {
                $set = implode(', ', array_map(fn($f) => "$f = ?", $fields));
                $stmt = $pdo->prepare("UPDATE founder SET $set, social_links = ?, image = ? WHERE id = ?");
                $vals[] = $existing['id'];
                $stmt->execute($vals);
            } else {
                $cols = implode(', ', $fields) . ', social_links, image';
                $stmt = $pdo->prepare("INSERT INTO founder ($cols) VALUES (?,?,?,?,?,?,?)");
                $stmt->execute($vals);
            }
            $msg = 'Management details updated successfully.';
        }
    } catch (Exception $e) { $error = 'Error: ' . $e->getMessage(); }
}

$founder = $pdo ? ($pdo->query("SELECT * FROM founder LIMIT 1")->fetch() ?: []) : [];
$social = json_decode($founder['social_links'] ?? '{}', true) ?: [];
?>
<?php include '../includes/admin-header.php'; ?>
  <?php include '../includes/sidebar.php'; ?>
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-3xl mx-auto space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Founder / Management</h1>
        <p class="text-xs text-gray-400 mt-1">Edit leadership profile, biography, and social links.</p>
      </div>
      <?php if($msg): ?><div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if($error): ?><div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
        <?= csrf_field() ?>
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($founder['image'] ?? '/assets/images/founder.jpg') ?>">
        
        <div class="flex items-center gap-6 pb-4 border-b border-gray-100">
          <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200 shrink-0">
            <img src="<?= htmlspecialchars($founder['image'] ?? '/assets/images/founder.jpg') ?>" class="w-full h-full object-cover" alt="">
          </div>
          <div class="flex-1">
            <p class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($founder['name'] ?? 'Mayank Chandhok') ?></p>
            <p class="text-xs text-gray-500"><?= htmlspecialchars($founder['role'] ?? 'Founder & Managing Director') ?></p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Founder Name</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($founder['name'] ?? 'Mayank Chandhok') ?>"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Role / Designation</label>
            <input type="text" name="role" required value="<?= htmlspecialchars($founder['role'] ?? 'Founder & Managing Director') ?>"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition">
          </div>
        </div>
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Biography</label>
          <textarea name="bio" rows="5" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($founder['bio'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Founder Message</label>
          <textarea name="message" rows="3" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($founder['message'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Experience Text</label>
          <textarea name="experience_text" rows="3" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($founder['experience_text'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Photo</label>
          <input type="file" name="founder_photo" class="text-xs w-full bg-gray-50 p-2.5 rounded border border-gray-200">
          <p class="text-[9px] text-gray-400 mt-1">Recommended: 400x400 square. Allowed: jpg, jpeg, png, webp.</p>
        </div>
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider border-b pb-2 pt-2">Social Links</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">LinkedIn</label>
            <input type="url" name="social_linkedin" value="<?= htmlspecialchars($social['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/..."
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition font-mono">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Facebook</label>
            <input type="url" name="social_facebook" value="<?= htmlspecialchars($social['facebook'] ?? '') ?>" placeholder="https://facebook.com/..."
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition font-mono">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Twitter / X</label>
            <input type="url" name="social_twitter" value="<?= htmlspecialchars($social['twitter'] ?? '') ?>" placeholder="https://x.com/..."
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition font-mono">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Website</label>
            <input type="url" name="social_website" value="<?= htmlspecialchars($social['website'] ?? '') ?>" placeholder="https://..."
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition font-mono">
          </div>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition">Save Management Details</button>
      </form>
    </div>
  </main>
<?php include '../includes/admin-footer.php'; ?>
