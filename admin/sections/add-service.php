<?php
require_once '../middleware/auth.php';

$id = (int)($_GET['id'] ?? 0);
$service = null;
$msg = '';
$error = '';

if ($id && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        $service = $stmt->fetch();
    } catch(Exception $e) {
        $error = 'Error fetching service: ' . $e->getMessage();
    }
}

// Convert features and benefits JSON back to text lists for the textarea fields
$features_text = '';
$benefits_text = '';
if ($service) {
    $features_arr = json_decode($service['features'] ?? '[]', true);
    if (is_array($features_arr)) {
        $features_text = implode("\n", $features_arr);
    }
    $benefits_arr = json_decode($service['benefits'] ?? '[]', true);
    if (is_array($benefits_arr)) {
        $benefits_text = implode("\n", $benefits_arr);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug    = trim($_POST['slug'] ?? '');
    $name    = trim($_POST['name'] ?? '');
    $intro   = trim($_POST['intro'] ?? '');
    $full    = trim($_POST['full_content'] ?? '');
    $feats   = json_encode(array_values(array_filter(array_map('trim', explode("\n", $_POST['features'] ?? '')))));
    $bens    = json_encode(array_values(array_filter(array_map('trim', explode("\n", $_POST['benefits'] ?? '')))));
    $active  = isset($_POST['is_active']) ? 1 : 0;
    $order   = (int)($_POST['sort_order'] ?? 0);

    // Validate inputs
    if (empty($name) || empty($slug)) {
        $error = 'Name and slug are required.';
    } else {
        // Handle icon upload
        $icon_url = $service['icon_url'] ?? '';
        if (!empty($_FILES['icon']['name'])) {
            $ext = strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
            if (in_array($ext, $allowed)) {
                $fname = 'service_' . $slug . '_' . time() . '.' . $ext;
                // Ensure upload directory exists
                if (!is_dir(UPLOAD_PATH)) {
                    mkdir(UPLOAD_PATH, 0775, true);
                }
                if (move_uploaded_file($_FILES['icon']['tmp_name'], UPLOAD_PATH . $fname)) {
                    $icon_url = '/assets/images/uploads/' . $fname;
                } else {
                    $error = 'Failed to upload icon image.';
                }
            } else {
                $error = 'Invalid file type. Allowed: png, jpg, jpeg, svg, webp';
            }
        }

        if (empty($error)) {
            if ($id) {
                try {
                    $stmt = $pdo->prepare("UPDATE services SET slug=?, name=?, intro=?, full_content=?, features=?, benefits=?, icon_url=?, is_active=?, sort_order=? WHERE id=?");
                    $stmt->execute([$slug, $name, $intro, $full, $feats, $bens, $icon_url, $active, $order, $id]);
                    $msg = 'Service updated successfully.';
                    // Refresh data
                    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
                    $stmt->execute([$id]);
                    $service = $stmt->fetch();
                } catch(Exception $e) {
                    $error = 'Database error: ' . $e->getMessage();
                }
            } else {
                try {
                    $stmt = $pdo->prepare("INSERT INTO services (slug, name, intro, full_content, features, benefits, icon_url, is_active, sort_order) VALUES(?,?,?,?,?,?,?,?,?)");
                    $stmt->execute([$slug, $name, $intro, $full, $feats, $bens, $icon_url, $active, $order]);
                    header('Location: services.php?success=Service added successfully');
                    exit;
                } catch(Exception $e) {
                    $error = 'Database error: ' . $e->getMessage();
                }
            }
            // Update local display values
            if ($service) {
                $features_arr = json_decode($service['features'] ?? '[]', true);
                $features_text = is_array($features_arr) ? implode("\n", $features_arr) : '';
                $benefits_arr = json_decode($service['benefits'] ?? '[]', true);
                $benefits_text = is_array($benefits_arr) ? implode("\n", $benefits_arr) : '';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? 'Edit Service' : 'Add Service' ?> | Clevora Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="flex bg-gray-50 min-h-screen font-sans">

  <!-- SIDEBAR -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-8 md:p-12">
    <div class="max-w-3xl mx-auto space-y-6">
      <div>
        <a href="services.php" class="text-xs text-blue-600 hover:underline font-semibold">&larr; Back to Services List</a>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins mt-2"><?= $id ? 'Edit Service' : 'Add New Service' ?></h1>
        <p class="text-xs text-gray-400">Specify details for the service page template and homepage teasers.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Service Name *</label>
            <input type="text" name="name" id="service-name" required value="<?= htmlspecialchars($service['name'] ?? '') ?>" placeholder="e.g. Content Moderation"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Slug *</label>
            <input type="text" name="slug" id="service-slug" required value="<?= htmlspecialchars($service['slug'] ?? '') ?>" placeholder="e.g. content-moderation"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all font-mono">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Short Introduction / Teaser *</label>
          <textarea name="intro" required rows="3" placeholder="Brief intro to show on the services listing and home page grid..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white resize-none transition-all"><?= htmlspecialchars($service['intro'] ?? '') ?></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Long Content (For Deep-Dive Page)</label>
          <textarea name="full_content" rows="6" placeholder="Detailed page body content (used on full-layout service pages like Content Moderation)..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($service['full_content'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Key Features (One per line)</label>
            <p class="text-[10px] text-gray-400 mb-2">Lines will be saved as bullet points.</p>
            <textarea name="features" rows="5" placeholder="Feature One&#10;Feature Two&#10;Feature Three"
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($features_text) ?></textarea>
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Key Benefits (One per line)</label>
            <p class="text-[10px] text-gray-400 mb-2">Lines will be saved as bullet points.</p>
            <textarea name="benefits" rows="5" placeholder="Benefit One&#10;Benefit Two&#10;Benefit Three"
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($benefits_text) ?></textarea>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Service Icon/Photo</label>
            <input type="file" name="icon" class="text-xs w-full">
            <?php if (!empty($service['icon_url'])): ?>
            <div class="mt-2 text-xs text-gray-400">Current: <a href="<?= htmlspecialchars($service['icon_url']) ?>" target="_blank" class="text-blue-500 hover:underline"><?= basename($service['icon_url']) ?></a></div>
            <?php endif; ?>
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Sort Order</label>
            <input type="number" name="sort_order" value="<?= htmlspecialchars($service['sort_order'] ?? '0') ?>"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div class="flex items-center h-10">
            <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 cursor-pointer">
              <input type="checkbox" name="is_active" value="1" <?= (!isset($service['is_active']) || $service['is_active'] == 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              Active & Publicly Visible
            </label>
          </div>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          Save Service Data
        </button>
      </form>
    </div>
  </main>

  <script>
    // JS Auto-slugifier helper
    const nameInput = document.getElementById('service-name');
    const slugInput = document.getElementById('service-slug');
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
