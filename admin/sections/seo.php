<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'SEO Settings | Clevora Admin';
$msg = '';
$error = '';

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

$pages = [
    'home' => 'Home Page',
    'about' => 'About Us',
    'services' => 'Services Listing',
    'technology' => 'Technology & Infrastructure',
    'gallery' => 'Gallery & Culture',
    'clients' => 'Clients & Partners',
    'careers' => 'Careers',
    'contact' => 'Contact Us'
];

// Ensure all standard pages exist in the DB
if ($pdo) {
    try {
        foreach ($pages as $slug => $label) {
            $stmt = $pdo->prepare("INSERT INTO seo_settings (page_slug, meta_title) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=id");
            $stmt->execute([$slug, $label . ' | ' . SITE_NAME]);
        }
    } catch (Exception $e) {
        $error = 'Failed to seed SEO settings: ' . $e->getMessage();
    }
}

// Handle Save SEO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $page_slug = $_POST['page_slug'] ?? '';
    
    if (array_key_exists($page_slug, $pages)) {
        $meta_title = trim($_POST['meta_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $keywords = trim($_POST['keywords'] ?? '');
        
        $og_image = $_POST['existing_og_image'] ?? '';
        if (!empty($_FILES['og_image']['name'])) {
            $v = validate_upload($_FILES['og_image']);
            if ($v === true) {
                $url = save_upload($_FILES['og_image'], 'seo_og');
                if ($url) $og_image = $url;
            } else {
                $error = $v;
            }
        }
        
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("UPDATE seo_settings SET meta_title = ?, meta_description = ?, keywords = ?, og_image = ? WHERE page_slug = ?");
                $stmt->execute([$meta_title, $meta_description, $keywords, $og_image, $page_slug]);
                header('Location: seo.php?success=' . urlencode('SEO settings for ' . $pages[$page_slug] . ' updated successfully.'));
                exit;
            } catch (Exception $e) {
                $error = 'Failed to update SEO: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all seo records
$seo_records = [];
if ($pdo) {
    try {
        $seo_records = $pdo->query("SELECT * FROM seo_settings")->fetchAll();
        // Index by slug
        $seo_by_slug = [];
        foreach ($seo_records as $r) {
            $seo_by_slug[$r['page_slug']] = $r;
        }
    } catch (Exception $e) {
        $error = 'Failed to load SEO settings: ' . $e->getMessage();
    }
}

$edit_slug = $_GET['edit'] ?? '';
$edit_record = null;
if ($edit_slug && array_key_exists($edit_slug, $pages)) {
    $edit_record = $seo_by_slug[$edit_slug] ?? null;
}
?>
<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-6xl mx-auto space-y-6">
      
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Global SEO Settings</h1>
        <p class="text-xs text-gray-400 mt-1">Configure search engine titles, keywords, snippets, and social sharing images for each primary page.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT: PAGES LIST (2 Columns span) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <table class="w-full text-sm border-collapse text-left text-gray-600">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider border-b border-gray-100">
              <tr>
                <th class="px-6 py-4">Page Title / Slug</th>
                <th class="px-6 py-4">Meta Title Tag</th>
                <th class="px-6 py-4">Description Length</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <?php foreach ($pages as $slug => $label): 
                $rec = $seo_by_slug[$slug] ?? null;
                $desc_len = $rec ? strlen($rec['meta_description'] ?? '') : 0;
              ?>
              <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-semibold text-gray-800 text-xs uppercase"><?= htmlspecialchars($label) ?></div>
                  <div class="text-[10px] text-gray-400 font-mono">slug: <?= htmlspecialchars($slug) ?></div>
                </td>
                <td class="px-6 py-4 text-xs text-gray-600 font-medium">
                  <?= htmlspecialchars($rec['meta_title'] ?? 'N/A') ?>
                </td>
                <td class="px-6 py-4">
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase <?= $desc_len > 120 && $desc_len < 160 ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500 border border-gray-200' ?>">
                    <?= $desc_len ?> chars
                  </span>
                </td>
                <td class="px-6 py-4 text-right flex justify-end gap-3 text-xs">
                  <a href="?edit=<?= htmlspecialchars($slug) ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Configure SEO</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- RIGHT: EDIT FORM (1 Column span) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">
            <?= $edit_record ? 'Edit SEO: ' . $pages[$edit_slug] : 'Select a Page' ?>
          </h2>
          
          <?php if(!$edit_record): ?>
            <p class="text-xs text-gray-400 text-center py-10">Click "Configure SEO" next to any page in the list to update its search metadata.</p>
          <?php else: ?>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
              <?= csrf_field() ?>
              <input type="hidden" name="page_slug" value="<?= htmlspecialchars($edit_slug) ?>">
              <input type="hidden" name="existing_og_image" value="<?= htmlspecialchars($edit_record['og_image'] ?? '') ?>">

              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Meta Title *</label>
                <input type="text" name="meta_title" required value="<?= htmlspecialchars($edit_record['meta_title'] ?? '') ?>" placeholder="e.g. Clevora | Professional Outsourcing Partner"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
              </div>

              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Keywords</label>
                <input type="text" name="keywords" value="<?= htmlspecialchars($edit_record['keywords'] ?? '') ?>" placeholder="e.g. bpo, customer service, outbound support"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
              </div>

              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" rows="4" placeholder="Brief snippet to show in search engine results (optimal: 120-160 chars)..."
                          class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white resize-none transition-all"><?= htmlspecialchars($edit_record['meta_description'] ?? '') ?></textarea>
              </div>

              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Social Sharing Image (OG:Image)</label>
                <input type="file" name="og_image" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
                <?php if($edit_record['og_image']): ?>
                  <div class="mt-2 flex items-center gap-2">
                    <img src="<?= htmlspecialchars($edit_record['og_image']) ?>" class="w-12 h-8 object-cover rounded border">
                    <span class="text-[9px] text-gray-400">Current og:image</span>
                  </div>
                <?php endif; ?>
              </div>

              <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition-all duration-300">
                  Save Metadata
                </button>
                <a href="seo.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-3 rounded text-xs uppercase tracking-wider transition-all duration-300 text-center">
                  Cancel
                </a>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

<?php include '../includes/admin-footer.php'; ?>
