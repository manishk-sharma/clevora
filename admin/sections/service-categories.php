<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Manage Service Categories | Clevora Admin';
$msg = '';
$error = '';

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $full_description = clean_rich_input($_POST['full_description'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $seo_title = trim($_POST['seo_title'] ?? '');
        $seo_description = trim($_POST['seo_description'] ?? '');
        
        $banner_image = $_POST['existing_banner_image'] ?? '';
        if (!empty($_FILES['banner_image']['name'])) {
            $v = validate_upload($_FILES['banner_image']);
            if ($v === true) {
                $url = save_upload($_FILES['banner_image'], 'category_banner');
                if ($url) {
                    $banner_image = $url;
                }
            } else {
                $error = $v;
            }
        }
        
        if (empty($name) || empty($slug)) {
            $error = 'Name and slug are required fields.';
        }
        
        if (empty($error)) {
            if ($id > 0) {
                // Edit
                try {
                    $stmt = $pdo->prepare("UPDATE service_categories SET name = ?, slug = ?, description = ?, full_description = ?, icon = ?, sort_order = ?, is_active = ?, banner_image = ?, seo_title = ?, seo_description = ? WHERE id = ?");
                    $stmt->execute([$name, $slug, $description, $full_description, $icon, $sort_order, $is_active, $banner_image, $seo_title, $seo_description, $id]);
                    header('Location: service-categories.php?success=Category updated successfully.');
                    exit;
                } catch (Exception $e) {
                    $error = 'Failed to update category: ' . $e->getMessage();
                }
            } else {
                // Add
                try {
                    $stmt = $pdo->prepare("INSERT INTO service_categories (name, slug, description, full_description, icon, sort_order, is_active, banner_image, seo_title, seo_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $slug, $description, $full_description, $icon, $sort_order, $is_active, $banner_image, $seo_title, $seo_description]);
                    header('Location: service-categories.php?success=Category added successfully.');
                    exit;
                } catch (Exception $e) {
                    $error = 'Failed to create category: ' . $e->getMessage();
                }
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    try {
        // Fetch existing banner image to delete it
        $stmt = $pdo->prepare("SELECT banner_image FROM service_categories WHERE id = ?");
        $stmt->execute([$del_id]);
        $row = $stmt->fetch();
        if ($row && $row['banner_image']) {
            $p = __DIR__ . '/../../' . ltrim($row['banner_image'], '/');
            if (file_exists($p)) {
                @unlink($p);
            }
        }
        
        $stmt = $pdo->prepare("DELETE FROM service_categories WHERE id = ?");
        $stmt->execute([$del_id]);
        header('Location: service-categories.php?success=Category deleted successfully.');
        exit;
    } catch (Exception $e) {
        $error = 'Failed to delete category: ' . $e->getMessage();
    }
}

// Fetch active editing category if requested
$edit_cat = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM service_categories WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_cat = $stmt->fetch();
    } catch (Exception $e) {
        $error = 'Failed to load category: ' . $e->getMessage();
    }
}

// Fetch all categories
$categories = [];
if ($pdo) {
    try {
        $categories = $pdo->query("SELECT * FROM service_categories ORDER BY sort_order ASC, name ASC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Failed to load category list: ' . $e->getMessage();
    }
}
?>
<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-6xl mx-auto space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 font-poppins">Service Categories</h1>
          <p class="text-xs text-gray-400">Classify your BPO, content operations, data and digital support services into structured customer solutions.</p>
        </div>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT: CATEGORY LIST (2 Columns span) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <table class="w-full text-sm border-collapse text-left text-gray-600">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider border-b border-gray-100">
              <tr>
                <th class="px-6 py-4">Sort</th>
                <th class="px-6 py-4">Icon/Banner</th>
                <th class="px-6 py-4">Category Name</th>
                <th class="px-6 py-4">Slug</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <?php if(empty($categories)): ?>
              <tr>
                <td colspan="6" class="px-6 py-8 text-center text-xs text-gray-400">No categories defined yet.</td>
              </tr>
              <?php else: ?>
                <?php foreach($categories as $cat): ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                  <td class="px-6 py-4 text-xs font-semibold text-gray-400">#<?= htmlspecialchars($cat['sort_order']) ?></td>
                  <td class="px-6 py-4 flex items-center gap-3">
                    <span class="text-xl"><?= htmlspecialchars($cat['icon'] ?: '💬') ?></span>
                    <?php if($cat['banner_image']): ?>
                      <img src="<?= htmlspecialchars($cat['banner_image']) ?>" class="w-10 h-6 object-cover rounded border border-gray-100">
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 font-semibold text-gray-800 text-xs uppercase"><?= htmlspecialchars($cat['name']) ?></td>
                  <td class="px-6 py-4 text-xs text-gray-400 font-mono"><?= htmlspecialchars($cat['slug']) ?></td>
                  <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full <?= $cat['is_active'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500 border border-gray-200' ?>">
                      <?= $cat['is_active'] ? 'Active' : 'Hidden' ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right flex justify-end gap-3 text-xs">
                    <a href="?edit=<?= $cat['id'] ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Edit</a>
                    <a href="?delete=<?= $cat['id'] ?>" class="text-red-500 hover:text-red-600 font-semibold" data-confirm="Are you sure you want to delete this category? Associated services will have their category unassigned.">Delete</a>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- RIGHT: ADD/EDIT FORM (1 Column span) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">
            <?= $edit_cat ? 'Edit Category' : 'Add New Category' ?>
          </h2>
          
          <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save">
            <?php if($edit_cat): ?>
              <input type="hidden" name="id" value="<?= $edit_cat['id'] ?>">
              <input type="hidden" name="existing_banner_image" value="<?= htmlspecialchars($edit_cat['banner_image'] ?? '') ?>">
            <?php endif; ?>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Category Name *</label>
              <input type="text" name="name" id="cat-name" data-slug-source="#cat-slug" required value="<?= htmlspecialchars($edit_cat['name'] ?? '') ?>" placeholder="e.g. Customer Support Services"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Slug *</label>
              <input type="text" name="slug" id="cat-slug" required value="<?= htmlspecialchars($edit_cat['slug'] ?? '') ?>" placeholder="e.g. customer-support-services"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description / Subtitle</label>
              <textarea name="description" rows="2" placeholder="Brief tagline or summary for client views..."
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white resize-none transition-all"><?= htmlspecialchars($edit_cat['description'] ?? '') ?></textarea>
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Detailed Description (WYSIWYG)</label>
              <input type="hidden" name="full_description" id="full-description" value="<?= htmlspecialchars($edit_cat['full_description'] ?? '') ?>">
              <div data-quill="#full-description" data-placeholder="Full description for service category details..." class="bg-gray-50 border border-gray-200 rounded-lg min-h-[100px]"></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Icon (Emoji/text)</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($edit_cat['icon'] ?? '💬') ?>" placeholder="e.g. 💬"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?= htmlspecialchars($edit_cat['sort_order'] ?? '0') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
              </div>
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Banner Image</label>
              <input type="file" name="banner_image" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
              <?php if($edit_cat && $edit_cat['banner_image']): ?>
                <div class="mt-2 text-[10px] text-gray-400">Current: <a href="<?= htmlspecialchars($edit_cat['banner_image']) ?>" target="_blank" class="text-blue-500 underline font-semibold">View Image</a></div>
              <?php endif; ?>
            </div>

            <div class="border-t pt-3 mt-3">
              <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">SEO Settings</h4>
              <div class="space-y-3">
                <div>
                  <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">SEO Title</label>
                  <input type="text" name="seo_title" value="<?= htmlspecialchars($edit_cat['seo_title'] ?? '') ?>" placeholder="Search Engine Title"
                         class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
                </div>
                <div>
                  <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">SEO Description</label>
                  <textarea name="seo_description" rows="2" placeholder="Search Engine Description..."
                            class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white resize-none transition-all"><?= htmlspecialchars($edit_cat['seo_description'] ?? '') ?></textarea>
                </div>
              </div>
            </div>

            <div class="flex items-center pt-2">
              <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?= (!isset($edit_cat['is_active']) || $edit_cat['is_active'] == 1) ? 'checked' : '' ?> class="rounded text-blue-600">
                Category is Active
              </label>
            </div>

            <div class="flex gap-2 pt-2">
              <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded-lg text-[10px] uppercase tracking-wider transition-all duration-300">
                <?= $edit_cat ? 'Update Category' : 'Create Category' ?>
              </button>
              <?php if($edit_cat): ?>
                <a href="service-categories.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-3 rounded-lg text-[10px] uppercase tracking-wider transition-all duration-300 text-center">
                  Cancel
                </a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

<?php include '../includes/admin-footer.php'; ?>
