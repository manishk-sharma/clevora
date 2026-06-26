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

// Fetch categories for the select dropdown
$categories = [];
if ($pdo) {
    try {
        $categories = $pdo->query("SELECT id, name FROM service_categories WHERE is_active=1 ORDER BY sort_order ASC, name ASC")->fetchAll();
    } catch(Exception $e) {
        $error = 'Error fetching categories: ' . $e->getMessage();
    }
}

// Fetch features, benefits, process steps, industries if editing
$features = [];
$benefits = [];
$process = [];
$industries = [];
if ($id && $pdo) {
    try {
        $features = $pdo->prepare("SELECT * FROM service_features WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $features->execute([$id]);
        $features = $features->fetchAll();

        $benefits = $pdo->prepare("SELECT * FROM service_benefits WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $benefits->execute([$id]);
        $benefits = $benefits->fetchAll();

        $process = $pdo->prepare("SELECT * FROM service_process WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $process->execute([$id]);
        $process = $process->fetchAll();

        $industries = $pdo->prepare("SELECT * FROM service_industries WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $industries->execute([$id]);
        $industries = $industries->fetchAll();
    } catch(Exception $e) {
        $error = 'Error fetching service details: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $category_id = (int)($_POST['category_id'] ?? 0);
    $slug    = trim($_POST['slug'] ?? '');
    $name    = trim($_POST['name'] ?? '');
    $intro   = trim($_POST['intro'] ?? '');
    $detailed_description = clean_rich_input($_POST['detailed_description'] ?? '');
    $challenge_solved = clean_rich_input($_POST['challenge_solved'] ?? '');
    $active  = isset($_POST['is_active']) ? 1 : 0;
    $order   = (int)($_POST['sort_order'] ?? 0);
    
    // SEO fields
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $keywords = trim($_POST['keywords'] ?? '');

    // CTA fields
    $cta_heading = trim($_POST['cta_heading'] ?? '');
    $cta_text = trim($_POST['cta_text'] ?? '');
    $cta_button = trim($_POST['cta_button'] ?? '');

    // Validate inputs
    if (empty($name) || empty($slug) || empty($category_id)) {
        $error = 'Category, name, and slug are required.';
    } else {
        // Handle icon upload
        $icon_url = $service['icon_url'] ?? '';
        if (!empty($_FILES['icon']['name'])) {
            $v = validate_upload($_FILES['icon']);
            if ($v === true) {
                $url = save_upload($_FILES['icon'], 'service_icon');
                if ($url) $icon_url = $url;
            } else {
                $error = $v;
            }
        }

        // Handle banner image upload
        $image_url = $service['image'] ?? '';
        if (!empty($_FILES['image']['name'])) {
            $v = validate_upload($_FILES['image']);
            if ($v === true) {
                $url = save_upload($_FILES['image'], 'service_banner');
                if ($url) $image_url = $url;
            } else {
                $error = $v;
            }
        }

        if (empty($error)) {
            try {
                $pdo->beginTransaction();

                // Build a simple array of features/benefits for JSON column compatibility
                $feats_post = $_POST['feature_titles'] ?? [];
                $bens_post = $_POST['benefit_titles'] ?? [];
                $feats_json = json_encode(array_values(array_filter(array_map('trim', $feats_post))));
                $bens_json = json_encode(array_values(array_filter(array_map('trim', $bens_post))));

                if ($id) {
                    // Update
                    $stmt = $pdo->prepare("UPDATE services SET category_id=?, slug=?, name=?, intro=?, detailed_description=?, challenge_solved=?, full_content=?, features=?, benefits=?, icon_url=?, image=?, is_active=?, sort_order=?, meta_title=?, meta_description=?, keywords=?, cta_heading=?, cta_text=?, cta_button=? WHERE id=?");
                    $stmt->execute([
                        $category_id, $slug, $name, $intro, $detailed_description, $challenge_solved, $detailed_description, $feats_json, $bens_json, 
                        $icon_url, $image_url, $active, $order, $meta_title, $meta_description, $keywords, $cta_heading, $cta_text, $cta_button, $id
                    ]);
                    $service_id = $id;
                    $msg = 'Service updated successfully.';
                } else {
                    // Insert
                    $stmt = $pdo->prepare("INSERT INTO services (category_id, slug, name, intro, detailed_description, challenge_solved, full_content, features, benefits, icon_url, image, is_active, sort_order, meta_title, meta_description, keywords, cta_heading, cta_text, cta_button) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt->execute([
                        $category_id, $slug, $name, $intro, $detailed_description, $challenge_solved, $detailed_description, $feats_json, $bens_json, 
                        $icon_url, $image_url, $active, $order, $meta_title, $meta_description, $keywords, $cta_heading, $cta_text, $cta_button
                    ]);
                    $service_id = $pdo->lastInsertId();
                    $msg = 'Service created successfully.';
                }

                // Synchronize Repeaters: Features
                $pdo->prepare("DELETE FROM service_features WHERE service_id=?")->execute([$service_id]);
                if (!empty($_POST['feature_titles'])) {
                    $feat_desc = $_POST['feature_descriptions'] ?? [];
                    $feat_sort = $_POST['feature_sorts'] ?? [];
                    $stmt = $pdo->prepare("INSERT INTO service_features (service_id, title, description, sort_order) VALUES (?, ?, ?, ?)");
                    foreach ($_POST['feature_titles'] as $idx => $title) {
                        $title = trim($title);
                        if ($title !== '') {
                            $desc = trim($feat_desc[$idx] ?? '');
                            $sort = (int)($feat_sort[$idx] ?? 0);
                            $stmt->execute([$service_id, $title, $desc, $sort]);
                        }
                    }
                }

                // Synchronize Repeaters: Benefits
                $pdo->prepare("DELETE FROM service_benefits WHERE service_id=?")->execute([$service_id]);
                if (!empty($_POST['benefit_titles'])) {
                    $ben_desc = $_POST['benefit_descriptions'] ?? [];
                    $ben_sort = $_POST['benefit_sorts'] ?? [];
                    $stmt = $pdo->prepare("INSERT INTO service_benefits (service_id, title, description, sort_order) VALUES (?, ?, ?, ?)");
                    foreach ($_POST['benefit_titles'] as $idx => $title) {
                        $title = trim($title);
                        if ($title !== '') {
                            $desc = trim($ben_desc[$idx] ?? '');
                            $sort = (int)($ben_sort[$idx] ?? 0);
                            $stmt->execute([$service_id, $title, $desc, $sort]);
                        }
                    }
                }

                // Synchronize Repeaters: Process Steps
                $pdo->prepare("DELETE FROM service_process WHERE service_id=?")->execute([$service_id]);
                if (!empty($_POST['process_titles'])) {
                    $proc_desc = $_POST['process_descriptions'] ?? [];
                    $proc_sort = $_POST['process_sorts'] ?? [];
                    $stmt = $pdo->prepare("INSERT INTO service_process (service_id, title, description, sort_order) VALUES (?, ?, ?, ?)");
                    foreach ($_POST['process_titles'] as $idx => $title) {
                        $title = trim($title);
                        if ($title !== '') {
                            $desc = trim($proc_desc[$idx] ?? '');
                            $sort = (int)($proc_sort[$idx] ?? 0);
                            $stmt->execute([$service_id, $title, $desc, $sort]);
                        }
                    }
                }

                // Synchronize Repeaters: Industry Tags
                $pdo->prepare("DELETE FROM service_industries WHERE service_id=?")->execute([$service_id]);
                if (!empty($_POST['industry_names'])) {
                    $ind_sort = $_POST['industry_sorts'] ?? [];
                    $stmt = $pdo->prepare("INSERT INTO service_industries (service_id, name, sort_order) VALUES (?, ?, ?)");
                    foreach ($_POST['industry_names'] as $idx => $name_tag) {
                        $name_tag = trim($name_tag);
                        if ($name_tag !== '') {
                            $sort = (int)($ind_sort[$idx] ?? 0);
                            $stmt->execute([$service_id, $name_tag, $sort]);
                        }
                    }
                }

                $pdo->commit();
                header('Location: services.php?success=' . urlencode($msg));
                exit;
            } catch(Exception $e) {
                $pdo->rollBack();
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>
<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-4xl mx-auto space-y-6">
      <div>
        <a href="services.php" class="text-xs text-blue-600 hover:underline font-semibold">&larr; Back to Services List</a>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins mt-2"><?= $id ? 'Edit Service' : 'Add New Service' ?></h1>
        <p class="text-xs text-gray-400">Specify details for the service page template and homepage teasers.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        
        <!-- CARD 1: Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
          <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">Basic Info</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Service Category *</label>
              <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
                <option value="">-- Select Category --</option>
                <?php foreach($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= (isset($service['category_id']) && $service['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Service Name *</label>
              <input type="text" name="name" id="service-name" data-slug-source="#service-slug" required value="<?= htmlspecialchars($service['name'] ?? '') ?>" placeholder="e.g. Content Moderation"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Slug *</label>
              <input type="text" name="slug" id="service-slug" required value="<?= htmlspecialchars($service['slug'] ?? '') ?>" placeholder="e.g. content-moderation"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all font-mono">
            </div>
          </div>

          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Short Introduction / Teaser *</label>
            <textarea name="intro" required rows="2" placeholder="Brief intro to show on the services listing and home page grid..."
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white resize-none transition-all"><?= htmlspecialchars($service['intro'] ?? '') ?></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Detailed Description (WYSIWYG)</label>
              <input type="hidden" name="detailed_description" id="detailed-desc" value="<?= htmlspecialchars($service['detailed_description'] ?? $service['full_content'] ?? '') ?>">
              <div data-quill="#detailed-desc" data-placeholder="Explain service solutions in detail..." class="bg-gray-50 border border-gray-200 rounded-lg min-h-[150px]"></div>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Business Challenge Solved (WYSIWYG)</label>
              <input type="hidden" name="challenge_solved" id="challenge-sol" value="<?= htmlspecialchars($service['challenge_solved'] ?? '') ?>">
              <div data-quill="#challenge-sol" data-placeholder="Explain the specific client challenge this service addresses..." class="bg-gray-50 border border-gray-200 rounded-lg min-h-[150px]"></div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Service Icon (square)</label>
              <input type="file" name="icon" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
              <?php if (!empty($service['icon_url'])): ?>
              <div class="mt-1 text-[10px] text-gray-400">Current: <a href="<?= htmlspecialchars($service['icon_url']) ?>" target="_blank" class="text-blue-500 underline font-semibold"><?= basename($service['icon_url']) ?></a></div>
              <?php endif; ?>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Service Banner Image (wide)</label>
              <input type="file" name="image" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
              <?php if (!empty($service['image'])): ?>
              <div class="mt-1 text-[10px] text-gray-400">Current: <a href="<?= htmlspecialchars($service['image']) ?>" target="_blank" class="text-blue-500 underline font-semibold"><?= basename($service['image']) ?></a></div>
              <?php endif; ?>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="<?= htmlspecialchars($service['sort_order'] ?? '0') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none">
              </div>
              <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                  <input type="checkbox" name="is_active" value="1" <?= (!isset($service['is_active']) || $service['is_active'] == 1) ? 'checked' : '' ?> class="rounded text-blue-600"> Active
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- CARD 2: Dynamic Repeaters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
          <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">Service Repeatable Sections</h3>
          
          <!-- Features Repeater -->
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <label class="block text-[10px] font-bold text-gray-600 uppercase">Features</label>
              <button type="button" data-repeater-add="#features-list" class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded transition">
                + Add Feature
              </button>
            </div>
            <div id="features-list" class="space-y-3">
              <!-- Template Row -->
              <div data-repeater-template style="display: none;" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                <div class="md:col-span-2">
                  <input type="text" name="feature_titles[]" placeholder="Feature Title" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div>
                  <input type="text" name="feature_descriptions[]" placeholder="Short description" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div class="flex items-center gap-2">
                  <input type="number" name="feature_sorts[]" placeholder="Sort" value="0" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                  <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
                </div>
              </div>
              
              <!-- Existing Rows -->
              <?php foreach ($features as $f): ?>
              <div class="repeater-row grid grid-cols-1 md:grid-cols-4 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                <div class="md:col-span-2">
                  <input type="text" name="feature_titles[]" value="<?= htmlspecialchars($f['title']) ?>" placeholder="Feature Title" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div>
                  <input type="text" name="feature_descriptions[]" value="<?= htmlspecialchars($f['description']) ?>" placeholder="Short description" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div class="flex items-center gap-2">
                  <input type="number" name="feature_sorts[]" value="<?= $f['sort_order'] ?>" placeholder="Sort" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                  <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Benefits Repeater -->
          <div class="space-y-3 border-t pt-4">
            <div class="flex justify-between items-center">
              <label class="block text-[10px] font-bold text-gray-600 uppercase">Benefits</label>
              <button type="button" data-repeater-add="#benefits-list" class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded transition">
                + Add Benefit
              </button>
            </div>
            <div id="benefits-list" class="space-y-3">
              <!-- Template Row -->
              <div data-repeater-template style="display: none;" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                <div class="md:col-span-2">
                  <input type="text" name="benefit_titles[]" placeholder="Benefit Title" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div>
                  <input type="text" name="benefit_descriptions[]" placeholder="Short description" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div class="flex items-center gap-2">
                  <input type="number" name="benefit_sorts[]" placeholder="Sort" value="0" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                  <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
                </div>
              </div>
              
              <!-- Existing Rows -->
              <?php foreach ($benefits as $b): ?>
              <div class="repeater-row grid grid-cols-1 md:grid-cols-4 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                <div class="md:col-span-2">
                  <input type="text" name="benefit_titles[]" value="<?= htmlspecialchars($b['title']) ?>" placeholder="Benefit Title" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div>
                  <input type="text" name="benefit_descriptions[]" value="<?= htmlspecialchars($b['description']) ?>" placeholder="Short description" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div class="flex items-center gap-2">
                  <input type="number" name="benefit_sorts[]" value="<?= $b['sort_order'] ?>" placeholder="Sort" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                  <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Process Steps Repeater -->
          <div class="space-y-3 border-t pt-4">
            <div class="flex justify-between items-center">
              <label class="block text-[10px] font-bold text-gray-600 uppercase">Process Steps</label>
              <button type="button" data-repeater-add="#process-list" class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded transition">
                + Add Step
              </button>
            </div>
            <div id="process-list" class="space-y-3">
              <!-- Template Row -->
              <div data-repeater-template style="display: none;" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                <div class="md:col-span-2">
                  <input type="text" name="process_titles[]" placeholder="Step Title (e.g. 01. Audit)" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div>
                  <input type="text" name="process_descriptions[]" placeholder="Short explanation" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div class="flex items-center gap-2">
                  <input type="number" name="process_sorts[]" placeholder="Sort" value="0" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                  <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
                </div>
              </div>
              
              <!-- Existing Rows -->
              <?php foreach ($process as $p_row): ?>
              <div class="repeater-row grid grid-cols-1 md:grid-cols-4 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                <div class="md:col-span-2">
                  <input type="text" name="process_titles[]" value="<?= htmlspecialchars($p_row['title']) ?>" placeholder="Step Title" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div>
                  <input type="text" name="process_descriptions[]" value="<?= htmlspecialchars($p_row['description']) ?>" placeholder="Short explanation" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                </div>
                <div class="flex items-center gap-2">
                  <input type="number" name="process_sorts[]" value="<?= $p_row['sort_order'] ?>" placeholder="Sort" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                  <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 ml-auto">Delete</button>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Industries Tags Repeater -->
          <div class="space-y-3 border-t pt-4">
            <div class="flex justify-between items-center">
              <label class="block text-[10px] font-bold text-gray-600 uppercase">Target Industries</label>
              <button type="button" data-repeater-add="#industries-list" class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded transition">
                + Add Industry Tag
              </button>
            </div>
            <div id="industries-list" class="space-y-2">
              <!-- Template Row -->
              <div data-repeater-template style="display: none;" class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
                <input type="text" name="industry_names[]" placeholder="Industry Name (e.g. E-Commerce)" class="flex-1 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                <input type="number" name="industry_sorts[]" placeholder="Sort" value="0" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1">Delete</button>
              </div>
              
              <!-- Existing Rows -->
              <?php foreach ($industries as $ind): ?>
              <div class="repeater-row flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
                <input type="text" name="industry_names[]" value="<?= htmlspecialchars($ind['name']) ?>" placeholder="Industry Name" class="flex-1 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                <input type="number" name="industry_sorts[]" value="<?= $ind['sort_order'] ?>" placeholder="Sort" class="w-16 bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
                <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1">Delete</button>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- CARD 3: CTA & SEO Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            <!-- CTA Section -->
            <div class="space-y-4 pr-0 md:pr-4">
              <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">Custom CTA Block</h3>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">CTA Heading</label>
                <input type="text" name="cta_heading" value="<?= htmlspecialchars($service['cta_heading'] ?? '') ?>" placeholder="e.g. Ready to scale your operations?"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">CTA Description</label>
                <textarea name="cta_text" rows="2" placeholder="e.g. Contact our experts today to build a dedicated support squad."
                          class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none resize-none"><?= htmlspecialchars($service['cta_text'] ?? '') ?></textarea>
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">CTA Button Text</label>
                <input type="text" name="cta_button" value="<?= htmlspecialchars($service['cta_button'] ?? '') ?>" placeholder="e.g. Talk to Us"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none">
              </div>
            </div>

            <!-- SEO Settings -->
            <div class="space-y-4 pt-4 md:pt-0 pl-0 md:pl-8">
              <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider border-b pb-2">SEO Fields</h3>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Meta Title</label>
                <input type="text" name="meta_title" value="<?= htmlspecialchars($service['meta_title'] ?? '') ?>" placeholder="Search Engine page title tag"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Meta Keywords</label>
                <input type="text" name="keywords" value="<?= htmlspecialchars($service['keywords'] ?? '') ?>" placeholder="e.g. bpo, content moderation, user safety"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Meta Description</label>
                <textarea name="meta_description" rows="3" placeholder="Page summary snippet for search result listings..."
                          class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none resize-none"><?= htmlspecialchars($service['meta_description'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Panel -->
        <div class="flex gap-3">
          <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
            <?= $id ? 'Save Changes' : 'Publish Service' ?>
          </button>
          <a href="services.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-lg text-xs uppercase tracking-wider transition-all duration-300 text-center">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </main>

<?php include '../includes/admin-footer.php'; ?>
