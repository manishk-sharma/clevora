<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($name) || empty($slug)) {
            $error = 'Name and slug are required fields.';
        } else {
            if ($id > 0) {
                // Edit
                try {
                    $stmt = $pdo->prepare("UPDATE service_categories SET name = ?, slug = ?, description = ?, icon = ?, sort_order = ?, is_active = ? WHERE id = ?");
                    $stmt->execute([$name, $slug, $description, $icon, $sort_order, $is_active, $id]);
                    $msg = 'Category updated successfully.';
                } catch (Exception $e) {
                    $error = 'Failed to update category: ' . $e->getMessage();
                }
            } else {
                // Add
                try {
                    $stmt = $pdo->prepare("INSERT INTO service_categories (name, slug, description, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $slug, $description, $icon, $sort_order, $is_active]);
                    $msg = 'Category added successfully.';
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
        $stmt = $pdo->prepare("DELETE FROM service_categories WHERE id = ?");
        $stmt->execute([$del_id]);
        $msg = 'Category deleted successfully.';
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Service Categories | Clevora Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="flex bg-gray-50 min-h-screen font-sans">

  <!-- SIDEBAR -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-8 md:p-12">
    <div class="max-w-6xl mx-auto space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 font-poppins">Service Categories</h1>
          <p class="text-xs text-gray-400">Classify your BPO, content operations, data and digital support services into structured customer solutions.</p>
        </div>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT: CATEGORY LIST (2 Columns span) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <table class="w-full text-sm border-collapse text-left text-gray-600">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider border-b border-gray-100">
              <tr>
                <th class="px-6 py-4">Sort</th>
                <th class="px-6 py-4">Icon</th>
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
                  <td class="px-6 py-4 text-base"><?= htmlspecialchars($cat['icon']) ?></td>
                  <td class="px-6 py-4 font-semibold text-gray-800 text-xs uppercase"><?= htmlspecialchars($cat['name']) ?></td>
                  <td class="px-6 py-4 text-xs text-gray-400 font-mono"><?= htmlspecialchars($cat['slug']) ?></td>
                  <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full <?= $cat['is_active'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500 border border-gray-200' ?>">
                      <?= $cat['is_active'] ? 'Active' : 'Hidden' ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right flex justify-end gap-3 text-xs">
                    <a href="?edit=<?= $cat['id'] ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Edit</a>
                    <a href="?delete=<?= $cat['id'] ?>" class="text-red-500 hover:text-red-600 font-semibold"
                       onclick="return confirm('Are you sure you want to delete this category? Associated services will have their category unassigned.')">Delete</a>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- RIGHT: ADD/EDIT FORM (1 Column span) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
          <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
            <?= $edit_cat ? 'Edit Category' : 'Add New Category' ?>
          </h2>
          
          <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="save">
            <?php if($edit_cat): ?>
              <input type="hidden" name="id" value="<?= $edit_cat['id'] ?>">
            <?php endif; ?>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Category Name *</label>
              <input type="text" name="name" id="cat-name" required value="<?= htmlspecialchars($edit_cat['name'] ?? '') ?>" placeholder="e.g. Customer Support Services"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Slug *</label>
              <input type="text" name="slug" id="cat-slug" required value="<?= htmlspecialchars($edit_cat['slug'] ?? '') ?>" placeholder="e.g. customer-support-services"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>

            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description / Subtitle</label>
              <textarea name="description" rows="3" placeholder="Brief tagline or summary for client views..."
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white resize-none transition-all"><?= htmlspecialchars($edit_cat['description'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Icon (Emoji or text)</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($edit_cat['icon'] ?? '💬') ?>" placeholder="e.g. 💬"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?= htmlspecialchars($edit_cat['sort_order'] ?? '0') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
              </div>
            </div>

            <div class="flex items-center pt-2">
              <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?= (!isset($edit_cat['is_active']) || $edit_cat['is_active'] == 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Category is Active
              </label>
            </div>

            <div class="flex gap-2 pt-2">
              <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 rounded-lg text-[10px] uppercase tracking-wider transition-all duration-300">
                <?= $edit_cat ? 'Update Category' : 'Create Category' ?>
              </button>
              <?php if($edit_cat): ?>
                <a href="service-categories.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-3 rounded-lg text-[10px] uppercase tracking-wider transition-all duration-300 text-center">
                  Cancel
                </a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>
    const nameInput = document.getElementById('cat-name');
    const slugInput = document.getElementById('cat-slug');
    if (nameInput && slugInput) {
      nameInput.addEventListener('input', () => {
        if (!slugInput.dataset.touched) {
          slugInput.value = nameInput.value.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        }
      });
      slugInput.addEventListener('change', () => {
        slugInput.dataset.touched = true;
      });
    }
  </script>
</body>
</html>
