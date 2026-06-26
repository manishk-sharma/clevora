<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Homepage CMS | Clevora Admin';

$msg = '';
$error = '';
$flash = get_flash();
if ($flash) { $flash['type'] === 'success' ? $msg = $flash['message'] : $error = $flash['message']; }
if (isset($_GET['success'])) $msg = $_GET['success'];

if (isset($_GET['delete_slider'])) {
    $id = (int)($_GET['delete_slider'] ?? 0);
    $token = $_GET['csrf_token'] ?? '';

    if ($id <= 0) {
        set_flash('error', 'Invalid slider selected for deletion.');
    } elseif (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        set_flash('error', 'CSRF validation failed.');
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM hero_sliders WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                set_flash('error', 'No slider was deleted. The selected slider may no longer exist.');
            } else {
                set_flash('success', 'Slider deleted successfully.');
            }
        } catch (Exception $e) {
            set_flash('error', 'Error: ' . $e->getMessage());
        }
    }

    header('Location: home-about.php');
    exit;
}

/**
 * Save uploaded hero media files to uploads/hero/
 */
function save_hero_upload(array $file, string $prefix = 'hero'): string|false {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = $prefix . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
    
    $target_dir = __DIR__ . '/../../uploads/hero/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0775, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_dir . $filename)) {
        global $pdo;
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO media_library (filename, original_name, mime_type, size, url, alt_text) VALUES (?, ?, ?, ?, ?, '')");
                $stmt->execute([$filename, $file['name'], $file['type'], $file['size'], '/uploads/hero/' . $filename]);
            } catch (Exception $e) {
                error_log('Media library insert failed: ' . $e->getMessage());
            }
        }
        return 'uploads/hero/' . $filename;
    }
    return false;
}

/**
 * Save uploaded partner logo files to uploads/partners/
 */
function save_partner_logo(array $file): string|false {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = 'partner_' . time() . '_' . rand(100, 999) . '.' . $ext;
    
    $target_dir = __DIR__ . '/../../uploads/partners/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0775, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_dir . $filename)) {
        global $pdo;
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO media_library (filename, original_name, mime_type, size, url, alt_text) VALUES (?, ?, ?, ?, ?, '')");
                $stmt->execute([$filename, $file['name'], $file['type'], $file['size'], '/uploads/partners/' . $filename]);
            } catch (Exception $e) {
                error_log('Media library insert failed: ' . $e->getMessage());
            }
        }
        return 'uploads/partners/' . $filename;
    }
    return false;
}

//  Handle POST Actions 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf()) {
    $action = $_POST['action'] ?? '';
    if (isset($_POST['delete_slider'])) {
        $action = 'delete_slider';
    }
    
    try {
         // Hero Slider Save 
        if ($action === 'save_slider') {
            $id = (int)($_POST['slider_id'] ?? 0);
            
            $media_type = trim($_POST['media_type'] ?? 'image');
            $media_file = $_POST['existing_media_file'] ?? '';
            $video_poster = $_POST['existing_video_poster'] ?? '';
            
            // Validate and handle background file upload
            if ($media_type === 'image') {
                if (!empty($_FILES['slider_image']['name'])) {
                    $v = validate_upload($_FILES['slider_image'], ['jpg', 'jpeg', 'png', 'webp'], 10);
                    if ($v === true) {
                        $url = save_hero_upload($_FILES['slider_image'], 'hero_img');
                        if ($url) $media_file = $url;
                    } else {
                        $error = $v;
                    }
                }
            } else {
                if (!empty($_FILES['slider_video']['name'])) {
                    $v = validate_upload($_FILES['slider_video'], ['mp4', 'webm', 'mov'], 50);
                    if ($v === true) {
                        $url = save_hero_upload($_FILES['slider_video'], 'hero_vid');
                        if ($url) $media_file = $url;
                    } else {
                        $error = $v;
                    }
                }
                
                // Video Poster Image
                if (!empty($_FILES['video_poster']['name'])) {
                    $v = validate_upload($_FILES['video_poster'], ['jpg', 'jpeg', 'png', 'webp'], 5);
                    if ($v === true) {
                        $url = save_hero_upload($_FILES['video_poster'], 'hero_poster');
                        if ($url) $video_poster = $url;
                    } else {
                        $error = $v;
                    }
                }
            }
            
            if (empty($error)) {
                $legacy_image = ($media_type === 'image') ? $media_file : $video_poster;
                
                $small_heading = trim($_POST['small_heading'] ?? '');
                $main_heading = trim($_POST['main_heading'] ?? '');
                $description = trim($_POST['slider_description'] ?? '');
                $bullets = json_encode(array_values(array_filter(array_map('trim', explode("\n", $_POST['bullets'] ?? '')))));
                $cta_text = trim($_POST['cta_text'] ?? 'Contact Us');
                $cta_link = trim($_POST['cta_link'] ?? '/contact.php');
                $secondary_cta_text = trim($_POST['secondary_cta_text'] ?? 'Explore Solutions');
                $secondary_cta_link = trim($_POST['secondary_cta_link'] ?? '/services.php');
                $slider_sort = (int)($_POST['slider_sort'] ?? 0);
                $slider_status = isset($_POST['slider_status']) ? 1 : 0;
                
                if ($id > 0) {
                    $stmt = $pdo->prepare("
                        UPDATE hero_sliders 
                        SET small_heading=?, main_heading=?, description=?, bullets=?, cta_text=?, cta_link=?, secondary_cta_text=?, secondary_cta_link=?, sort_order=?, status=?, media_type=?, media_file=?, video_poster=?, image=? 
                        WHERE id=?
                    ");
                    $stmt->execute([
                        $small_heading, $main_heading, $description, $bullets, $cta_text, $cta_link, $secondary_cta_text, $secondary_cta_link,
                        $slider_sort, $slider_status, $media_type, $media_file, $video_poster, $legacy_image, $id
                    ]);
                    header('Location: home-about.php?success=Slider updated successfully.');
                    exit;
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO hero_sliders 
                        (small_heading, main_heading, description, bullets, cta_text, cta_link, secondary_cta_text, secondary_cta_link, sort_order, status, media_type, media_file, video_poster, image) 
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ");
                    $stmt->execute([
                        $small_heading, $main_heading, $description, $bullets, $cta_text, $cta_link, $secondary_cta_text, $secondary_cta_link,
                        $slider_sort, $slider_status, $media_type, $media_file, $video_poster, $legacy_image
                    ]);
                    header('Location: home-about.php?success=Slider added successfully.');
                    exit;
                }
            }
        }
        
        // Delete Slider
        if ($action === 'delete_slider') {
            $id = (int)($_POST['slider_id'] ?? 0);
            if ($id <= 0) {
                set_flash('error', 'Invalid slider selected for deletion.');
                header('Location: home-about.php');
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM hero_sliders WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                set_flash('error', 'No slider was deleted. The selected slider may no longer exist.');
                header('Location: home-about.php');
                exit;
            }

            set_flash('success', 'Slider deleted successfully.');
            header('Location: home-about.php');
            exit;
        }
        
        // ── Homepage About Settings ──────────────────────
        if ($action === 'save_about') {
            $keys = ['hero_headline', 'hero_bullets', 'hero_cta_text', 'about_home_text',
                     'stats_projects', 'stats_industries', 'stats_resumes', 'stats_clients'];
            $pdo->beginTransaction();
            foreach ($keys as $k) {
                $v = trim($_POST[$k] ?? '');
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $v, $v]);
            }
            $pdo->commit();
            header('Location: home-about.php?success=Homepage settings updated successfully.');
            exit;
        }
        
        // ── Homepage Section (FAQ/Why Choose/Industry/CTA) ─
        if ($action === 'save_section') {
            $sec_id = (int)($_POST['section_id'] ?? 0);
            $sec_type = trim($_POST['section_type'] ?? '');
            $sec_data = [
                $sec_type,
                trim($_POST['section_title'] ?? ''),
                trim($_POST['section_description'] ?? ''),
                trim($_POST['section_icon'] ?? ''),
                json_encode($_POST['section_extra'] ?? []),
                (int)($_POST['section_sort'] ?? 0),
                isset($_POST['section_status']) ? 1 : 0,
            ];
            if ($sec_id > 0) {
                $stmt = $pdo->prepare("UPDATE homepage_sections SET section_type=?, title=?, description=?, icon=?, extra_data=?, sort_order=?, status=? WHERE id=?");
                $sec_data[] = $sec_id;
                $stmt->execute($sec_data);
                header('Location: home-about.php?success=' . urlencode(ucfirst($sec_type) . ' updated.'));
                exit;
            } else {
                $stmt = $pdo->prepare("INSERT INTO homepage_sections (section_type, title, description, icon, extra_data, sort_order, status) VALUES (?,?,?,?,?,?,?)");
                $stmt->execute($sec_data);
                header('Location: home-about.php?success=' . urlencode(ucfirst($sec_type) . ' added.'));
                exit;
            }
        }
        
        if ($action === 'delete_section') {
            $pdo->prepare("DELETE FROM homepage_sections WHERE id = ?")->execute([(int)($_POST['section_id'] ?? 0)]);
            header('Location: home-about.php?success=Section item deleted.');
            exit;
        }
        
        // Partner Logos Save 
        if ($action === 'save_partner') {
            $id = (int)($_POST['partner_id'] ?? 0);
            $company_name = trim($_POST['company_name'] ?? '');
            $logo = $_POST['existing_logo'] ?? '';
            $sort_order = (int)($_POST['partner_sort'] ?? 0);
            $is_active = isset($_POST['partner_status']) ? 1 : 0;
            
            if (!empty($_FILES['partner_logo']['name'])) {
                $v = validate_upload($_FILES['partner_logo'], ['jpg', 'jpeg', 'png', 'webp', 'svg'], 5);
                if ($v === true) {
                    $url = save_partner_logo($_FILES['partner_logo']);
                    if ($url) $logo = $url;
                } else {
                    $error = $v;
                }
            }
            
            if (empty($company_name)) {
                $error = 'Company name is required.';
            }
            if (empty($logo) && empty($error)) {
                $error = 'Logo image is required.';
            }
            
            if (empty($error)) {
                if ($id > 0) {
                    $stmt = $pdo->prepare("UPDATE home_partners SET company_name=?, logo=?, sort_order=?, is_active=? WHERE id=?");
                    $stmt->execute([$company_name, $logo, $sort_order, $is_active, $id]);
                    header('Location: home-about.php?success=Partner logo updated successfully.');
                    exit;
                } else {
                    $stmt = $pdo->prepare("INSERT INTO home_partners (company_name, logo, sort_order, is_active) VALUES (?,?,?,?)");
                    $stmt->execute([$company_name, $logo, $sort_order, $is_active]);
                    header('Location: home-about.php?success=Partner logo added successfully.');
                    exit;
                }
            }
        }
        
        if ($action === 'delete_partner') {
            $id = (int)($_POST['partner_id'] ?? 0);
            $pdo->prepare("DELETE FROM home_partners WHERE id = ?")->execute([$id]);
            header('Location: home-about.php?success=Partner logo deleted successfully.');
            exit;
        }
        
        // Core Solutions Save
        if ($action === 'save_solution') {
            $id = (int)($_POST['solution_id'] ?? 0);
            $title = trim($_POST['solution_title'] ?? '');
            $description = trim($_POST['solution_description'] ?? '');
            $icon = trim($_POST['solution_icon'] ?? 'CS');
            $button_text = trim($_POST['solution_button_text'] ?? 'Explore Solutions');
            $button_link = trim($_POST['solution_button_link'] ?? '/services.php');
            $sort_order = (int)($_POST['solution_sort'] ?? 0);
            $is_active = isset($_POST['solution_status']) ? 1 : 0;
            
            if (empty($title)) {
                $error = 'Solution title is required.';
            }
            
            if (empty($error)) {
                if ($id > 0) {
                    $stmt = $pdo->prepare("UPDATE home_solutions SET title=?, description=?, icon=?, button_text=?, button_link=?, sort_order=?, is_active=? WHERE id=?");
                    $stmt->execute([$title, $description, $icon, $button_text, $button_link, $sort_order, $is_active, $id]);
                    header('Location: home-about.php?success=Solution card updated successfully.');
                    exit;
                } else {
                    $stmt = $pdo->prepare("INSERT INTO home_solutions (title, description, icon, button_text, button_link, sort_order, is_active) VALUES (?,?,?,?,?,?,?)");
                    $stmt->execute([$title, $description, $icon, $button_text, $button_link, $sort_order, $is_active]);
                    header('Location: home-about.php?success=Solution card added successfully.');
                    exit;
                }
            }
        }
        
        if ($action === 'delete_solution') {
            $id = (int)($_POST['solution_id'] ?? 0);
            $pdo->prepare("DELETE FROM home_solutions WHERE id = ?")->execute([$id]);
            header('Location: home-about.php?success=Solution card deleted successfully.');
            exit;
        }
        
        // How It Works Save
        if ($action === 'save_step') {
            $id = (int)($_POST['step_id'] ?? 0);
            $step_number = (int)($_POST['step_number'] ?? 1);
            $title = trim($_POST['step_title'] ?? '');
            $description = trim($_POST['step_description'] ?? '');
            $sort_order = (int)($_POST['step_sort'] ?? 0);
            $is_active = isset($_POST['step_status']) ? 1 : 0;
            
            if (empty($title)) {
                $error = 'Step title is required.';
            }
            
            if (empty($error)) {
                if ($id > 0) {
                    $stmt = $pdo->prepare("UPDATE home_process_steps SET step_number=?, title=?, description=?, sort_order=?, is_active=? WHERE id=?");
                    $stmt->execute([$step_number, $title, $description, $sort_order, $is_active, $id]);
                    header('Location: home-about.php?success=Process step updated successfully.');
                    exit;
                } else {
                    $stmt = $pdo->prepare("INSERT INTO home_process_steps (step_number, title, description, sort_order, is_active) VALUES (?,?,?,?,?)");
                    $stmt->execute([$step_number, $title, $description, $sort_order, $is_active]);
                    header('Location: home-about.php?success=Process step added successfully.');
                    exit;
                }
            }
        }
        
        if ($action === 'delete_step') {
            $id = (int)($_POST['step_id'] ?? 0);
            $pdo->prepare("DELETE FROM home_process_steps WHERE id = ?")->execute([$id]);
            header('Location: home-about.php?success=Process step deleted successfully.');
            exit;
        }
        
        // Homepage CTA Save
        if ($action === 'save_homepage_cta') {
            $keys = ['cta_heading', 'cta_text', 'cta_button_text', 'cta_button_url'];
            $pdo->beginTransaction();
            foreach ($keys as $k) {
                $v = trim($_POST[$k] ?? '');
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $v, $v]);
            }
            $pdo->commit();
            header('Location: home-about.php?success=Homepage CTA settings updated successfully.');
            exit;
        }
        
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
    } else {
        $error = 'CSRF validation failed.';
    }
}

// Fetch Data
$sliders = [];
$faqs = [];
$why_choose = [];
$industries = [];
$cta_items = [];
$partners = [];
$solutions = [];
$steps = [];
$settings = [];

if ($pdo) {
    try {
        $sliders = $pdo->query("SELECT * FROM hero_sliders ORDER BY sort_order ASC, id ASC")->fetchAll();
        $faqs = $pdo->query("SELECT * FROM homepage_sections WHERE section_type='faq' ORDER BY sort_order")->fetchAll();
        $why_choose = $pdo->query("SELECT * FROM homepage_sections WHERE section_type='why_choose' ORDER BY sort_order")->fetchAll();
        $industries = $pdo->query("SELECT * FROM homepage_sections WHERE section_type='industry' ORDER BY sort_order")->fetchAll();
        $partners = $pdo->query("SELECT * FROM home_partners ORDER BY sort_order ASC, id ASC")->fetchAll();
        $solutions = $pdo->query("SELECT * FROM home_solutions ORDER BY sort_order ASC, id ASC")->fetchAll();
        $steps = $pdo->query("SELECT * FROM home_process_steps ORDER BY sort_order ASC, id ASC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Failed to fetch data: ' . $e->getMessage();
    }
}

$setting_keys = ['hero_headline','hero_bullets','hero_cta_text','about_home_text','stats_projects','stats_industries','stats_resumes','stats_clients', 'cta_heading', 'cta_text', 'cta_button_text', 'cta_button_url'];
foreach ($setting_keys as $k) { $settings[$k] = setting($k, $pdo); }

// Edit mode for slider
$edit_slider = null;
if (isset($_GET['edit_slider']) && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM hero_sliders WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_slider']]);
    $edit_slider = $stmt->fetch();
}

// Edit mode for section
$edit_section = null;
if (isset($_GET['edit_section']) && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_section']]);
    $edit_section = $stmt->fetch();
}

// Edit mode for partner
$edit_partner = null;
if (isset($_GET['edit_partner']) && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM home_partners WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_partner']]);
    $edit_partner = $stmt->fetch();
}

// Edit mode for solution
$edit_solution = null;
if (isset($_GET['edit_solution']) && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM home_solutions WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_solution']]);
    $edit_solution = $stmt->fetch();
}

// Edit mode for step
$edit_step = null;
if (isset($_GET['edit_step']) && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM home_process_steps WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_step']]);
    $edit_step = $stmt->fetch();
}
?>
<?php include '../includes/admin-header.php'; ?>
  <?php include '../includes/sidebar.php'; ?>

  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-5xl mx-auto space-y-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Home Page CMS</h1>
        <p class="text-xs text-gray-400 mt-1">Manage hero sliders, about snippet, statistics, FAQ, and call-to-action sections.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- HERO SLIDERS -->
      <div x-data="{ open: true }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[10px] font-bold text-blue-700">HS</span>
            <div>
              <h2 class="font-bold text-gray-800 text-sm">Hero Sliders</h2>
              <p class="text-[10px] text-gray-400"><?= count($sliders) ?> slide<?= count($sliders) !== 1 ? 's' : '' ?> configured</p>
            </div>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? 'rotate-0' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <!-- Slider List -->
          <div class="border-t border-gray-50 p-5 space-y-3">
            <?php if(empty($sliders)): ?>
            <p class="text-xs text-gray-400 text-center py-4">No hero sliders yet.</p>
            <?php else: ?>
              <?php foreach($sliders as $sl): ?>
              <div class="flex items-center gap-4 bg-gray-50 rounded-lg p-3 border border-gray-100">
                <?php
                $media_src = '';
                if (!empty($sl['media_file'])) {
                    $media_src = (str_starts_with($sl['media_file'], '/') || str_starts_with($sl['media_file'], 'http')) ? $sl['media_file'] : '/' . $sl['media_file'];
                }
                if (($sl['media_type'] ?? 'image') === 'video'):
                ?>
                  <video class="w-16 h-10 rounded object-cover border" muted preload="metadata">
                    <source src="<?= htmlspecialchars($media_src) ?>">
                  </video>
                <?php else: ?>
                  <?php if ($media_src): ?>
                    <img src="<?= htmlspecialchars($media_src) ?>" class="w-16 h-10 rounded object-cover border" alt="">
                  <?php endif; ?>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold text-gray-800 truncate"><?= htmlspecialchars($sl['main_heading']) ?></p>
                  <p class="text-[10px] text-gray-400">
                    <?= htmlspecialchars($sl['small_heading']) ?> &middot; Sort: <?= $sl['sort_order'] ?>
                    <span class="ml-2 px-1.5 py-0.2 rounded text-[8px] font-bold uppercase <?= ($sl['media_type'] ?? 'image') === 'video' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                      <?= htmlspecialchars($sl['media_type'] ?? 'image') ?>
                    </span>
                  </p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $sl['status'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500' ?>">
                  <?= $sl['status'] ? 'Active' : 'Hidden' ?>
                </span>
                <div class="flex gap-2 text-xs">
                  <a href="?edit_slider=<?= $sl['id'] ?>" class="text-blue-600 font-semibold hover:underline">Edit</a>
                  <a href="?delete_slider=<?= $sl['id'] ?>&csrf_token=<?= urlencode(csrf_token()) ?>"
                     class="text-red-500 font-semibold hover:underline"
                     data-confirm="Delete this slider?">Delete</a>
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Add/Edit Slider Form -->
          <form method="POST" enctype="multipart/form-data" class="border-t border-gray-100 p-5 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_slider">
            <input type="hidden" name="slider_id" value="<?= $edit_slider['id'] ?? 0 ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_slider['image'] ?? '') ?>">
            <input type="hidden" name="existing_media_file" value="<?= htmlspecialchars($edit_slider['media_file'] ?? '') ?>">
            <input type="hidden" name="existing_video_poster" value="<?= htmlspecialchars($edit_slider['video_poster'] ?? '') ?>">
            
            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider"><?= $edit_slider ? 'Edit Slider' : 'Add New Slider' ?></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Small Heading</label>
                <input type="text" name="small_heading" value="<?= htmlspecialchars($edit_slider['small_heading'] ?? '') ?>" placeholder="e.g. SCALABLE OUTSOURCING"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Main Heading *</label>
                <input type="text" name="main_heading" required value="<?= htmlspecialchars($edit_slider['main_heading'] ?? '') ?>" placeholder="e.g. Customer Experience (CX)"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description</label>
              <input type="text" name="slider_description" value="<?= htmlspecialchars($edit_slider['description'] ?? '') ?>" placeholder="Short quote or tagline"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Bullet Points (one per line)</label>
              <textarea name="bullets" rows="4" placeholder="Feature one&#10;Feature two"
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition"><?php
                if ($edit_slider) {
                    $b = json_decode($edit_slider['bullets'] ?? '[]', true);
                    echo htmlspecialchars(is_array($b) ? implode("\n", $b) : '');
                }
              ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Primary CTA Button Text</label>
                <input type="text" name="cta_text" value="<?= htmlspecialchars($edit_slider['cta_text'] ?? 'Contact Us') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Primary CTA Link</label>
                <input type="text" name="cta_link" value="<?= htmlspecialchars($edit_slider['cta_link'] ?? '/contact.php') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition font-mono">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Secondary CTA Button Text</label>
                <input type="text" name="secondary_cta_text" value="<?= htmlspecialchars($edit_slider['secondary_cta_text'] ?? 'Explore Solutions') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Secondary CTA Link</label>
                <input type="text" name="secondary_cta_link" value="<?= htmlspecialchars($edit_slider['secondary_cta_link'] ?? '/services.php') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition font-mono">
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Media Type</label>
                <select name="media_type" id="media_type" onchange="toggleMediaFields()"
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
                  <option value="image" <?= ($edit_slider && ($edit_slider['media_type'] ?? 'image') === 'image') ? 'selected' : '' ?>>Image</option>
                  <option value="video" <?= ($edit_slider && ($edit_slider['media_type'] ?? 'image') === 'video') ? 'selected' : '' ?>>Video</option>
                </select>
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort Order</label>
                <input type="number" name="slider_sort" value="<?= $edit_slider['sort_order'] ?? 0 ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
              <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                  <input type="checkbox" name="slider_status" value="1" <?= (!isset($edit_slider['status']) || $edit_slider['status']) ? 'checked' : '' ?> class="rounded text-blue-600">
                  Active
                </label>
              </div>
            </div>

            <!-- Conditional Media Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div id="image_field_container">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Background Image</label>
                <input type="file" name="slider_image" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
                <?php if ($edit_slider && ($edit_slider['media_type'] ?? 'image') === 'image' && !empty($edit_slider['media_file'])): ?>
                  <p class="text-[10px] text-gray-400 mt-1">Current: <?= htmlspecialchars(basename($edit_slider['media_file'])) ?></p>
                <?php endif; ?>
              </div>
              <div id="video_field_container" style="display: none;">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Background Video (MP4/WebM/MOV, max 50MB)</label>
                <input type="file" name="slider_video" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
                <?php if ($edit_slider && ($edit_slider['media_type'] ?? 'image') === 'video' && !empty($edit_slider['media_file'])): ?>
                  <p class="text-[10px] text-gray-400 mt-1">Current: <?= htmlspecialchars(basename($edit_slider['media_file'])) ?></p>
                <?php endif; ?>
              </div>
              <div id="poster_field_container" style="display: none;">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Video Poster Image</label>
                <input type="file" name="video_poster" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
                <?php if ($edit_slider && !empty($edit_slider['video_poster'])): ?>
                  <p class="text-[10px] text-gray-400 mt-1">Current: <?= htmlspecialchars(basename($edit_slider['video_poster'])) ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition">
                <?= $edit_slider ? 'Update Slider' : 'Add Slider' ?>
              </button>
              <?php if($edit_slider): ?>
              <a href="home-about.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-xs uppercase transition">Cancel</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>

      <!-- HOMEPAGE ABOUT & STATS -->
      <div x-data="{ open: true }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-[10px] font-bold text-emerald-700">ST</span>
            <h2 class="font-bold text-gray-800 text-sm">Homepage About & Statistics</h2>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <form method="POST" class="border-t border-gray-50 p-5 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_about">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Hero Headline</label>
                <input type="text" name="hero_headline" required value="<?= htmlspecialchars($settings['hero_headline']) ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Hero CTA Button</label>
                <input type="text" name="hero_cta_text" required value="<?= htmlspecialchars($settings['hero_cta_text']) ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition">
              </div>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Hero Bullet Points (one per line)</label>
              <textarea name="hero_bullets" rows="4" required class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($settings['hero_bullets']) ?></textarea>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">About Teaser Text (Homepage)</label>
              <textarea name="about_home_text" rows="5" required class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($settings['about_home_text']) ?></textarea>
            </div>
            <div>
              <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider border-b pb-2 mb-3">Statistics Counter Targets</h3>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                  <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Projects</label>
                  <input type="number" name="stats_projects" required value="<?= htmlspecialchars($settings['stats_projects']) ?>"
                         class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
                </div>
                <div>
                  <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Industries</label>
                  <input type="number" name="stats_industries" required value="<?= htmlspecialchars($settings['stats_industries']) ?>"
                         class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
                </div>
                <div>
                  <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Processes</label>
                  <input type="number" name="stats_resumes" required value="<?= htmlspecialchars($settings['stats_resumes']) ?>"
                         class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
                </div>
                <div>
                  <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Clients</label>
                  <input type="number" name="stats_clients" required value="<?= htmlspecialchars($settings['stats_clients']) ?>"
                         class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
                </div>
              </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition">
              Save Homepage Settings
            </button>
          </form>
        </div>
      </div>

      <!-- FAQ SECTION -->
      <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-[10px] font-bold text-amber-700">FAQ</span>
            <div>
              <h2 class="font-bold text-gray-800 text-sm">FAQs</h2>
              <p class="text-[10px] text-gray-400"><?= count($faqs) ?> question<?= count($faqs) !== 1 ? 's' : '' ?></p>
            </div>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <div class="border-t border-gray-50 p-5 space-y-3">
            <?php foreach($faqs as $f): ?>
            <div class="flex items-start gap-3 bg-gray-50 rounded-lg p-3 border border-gray-100">
              <div class="flex-1">
                <p class="text-xs font-bold text-gray-800"><?= htmlspecialchars($f['title']) ?></p>
                <p class="text-[10px] text-gray-500 mt-1"><?= htmlspecialchars(substr($f['description'], 0, 100)) ?>...</p>
              </div>
              <div class="flex gap-2 text-xs shrink-0">
                <a href="?edit_section=<?= $f['id'] ?>" class="text-blue-600 font-semibold">Edit</a>
                <form method="POST" class="inline" onsubmit="return confirm('Delete?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete_section">
                  <input type="hidden" name="section_id" value="<?= $f['id'] ?>">
                  <button class="text-red-500 font-semibold">Delete</button>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <form method="POST" class="border-t border-gray-100 p-5 space-y-3">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_section">
            <input type="hidden" name="section_type" value="faq">
            <input type="hidden" name="section_id" value="<?= ($edit_section && ($edit_section['section_type']??'') === 'faq') ? $edit_section['id'] : 0 ?>">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Question *</label>
              <input type="text" name="section_title" required value="<?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'faq') ? $edit_section['title'] : '') ?>"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Answer *</label>
              <textarea name="section_description" required rows="3" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'faq') ? $edit_section['description'] : '') ?></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort Order</label>
                <input type="number" name="section_sort" value="<?= ($edit_section && ($edit_section['section_type']??'') === 'faq') ? $edit_section['sort_order'] : 0 ?>"
                       class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
              <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                  <input type="checkbox" name="section_status" value="1" <?= (!$edit_section || ($edit_section['status'] ?? 1)) ? 'checked' : '' ?> class="rounded text-blue-600">
                  Active
                </label>
              </div>
            </div>
            <input type="hidden" name="section_icon" value="?">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition">
              <?= ($edit_section && ($edit_section['section_type']??'') === 'faq') ? 'Update FAQ' : 'Add FAQ' ?>
            </button>
          </form>
        </div>
      </div>

      <!-- WHY CHOOSE US CARDS -->
      <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center text-[10px] font-bold text-violet-700">WC</span>
            <div>
              <h2 class="font-bold text-gray-800 text-sm">Why Choose Us Cards</h2>
              <p class="text-[10px] text-gray-400"><?= count($why_choose) ?> card<?= count($why_choose) !== 1 ? 's' : '' ?></p>
            </div>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <div class="border-t border-gray-50 p-5 space-y-3">
            <?php foreach($why_choose as $wc): ?>
            <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3 border border-gray-100">
              <span class="text-lg"><?= htmlspecialchars($wc['icon']) ?></span>
              <div class="flex-1"><p class="text-xs font-bold text-gray-800"><?= htmlspecialchars($wc['title']) ?></p></div>
              <div class="flex gap-2 text-xs">
                <a href="?edit_section=<?= $wc['id'] ?>" class="text-blue-600 font-semibold">Edit</a>
                <form method="POST" class="inline" onsubmit="return confirm('Delete?')">
                  <?= csrf_field() ?><input type="hidden" name="action" value="delete_section"><input type="hidden" name="section_id" value="<?= $wc['id'] ?>">
                  <button class="text-red-500 font-semibold">Delete</button>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <form method="POST" class="border-t border-gray-100 p-5 space-y-3">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_section">
            <input type="hidden" name="section_type" value="why_choose">
            <input type="hidden" name="section_id" value="<?= ($edit_section && ($edit_section['section_type']??'') === 'why_choose') ? $edit_section['id'] : 0 ?>">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Title *</label>
                <input type="text" name="section_title" required value="<?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'why_choose') ? $edit_section['title'] : '') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Icon (emoji)</label>
                <input type="text" name="section_icon" value="<?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'why_choose') ? $edit_section['icon'] : '*') ?>"
                       class="w-20 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort</label>
                <input type="number" name="section_sort" value="<?= ($edit_section && ($edit_section['section_type']??'') === 'why_choose') ? $edit_section['sort_order'] : 0 ?>"
                       class="w-20 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description</label>
              <textarea name="section_description" rows="2" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'why_choose') ? $edit_section['description'] : '') ?></textarea>
            </div>
            <input type="hidden" name="section_status" value="1">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition">
              <?= ($edit_section && ($edit_section['section_type']??'') === 'why_choose') ? 'Update Card' : 'Add Card' ?>
            </button>
          </form>
        </div>
      </div>

      <!-- INDUSTRIES -->
      <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[10px] font-bold text-cyan-700">IN</span>
            <div>
              <h2 class="font-bold text-gray-800 text-sm">Industries Served</h2>
              <p class="text-[10px] text-gray-400"><?= count($industries) ?> industri<?= count($industries) !== 1 ? 'es' : 'y' ?></p>
            </div>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <div class="border-t border-gray-50 p-5 space-y-3">
            <?php foreach($industries as $ind): ?>
            <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3 border border-gray-100">
              <span class="text-lg"><?= htmlspecialchars($ind['icon']) ?></span>
              <div class="flex-1"><p class="text-xs font-bold text-gray-800"><?= htmlspecialchars($ind['title']) ?></p></div>
              <div class="flex gap-2 text-xs">
                <a href="?edit_section=<?= $ind['id'] ?>" class="text-blue-600 font-semibold">Edit</a>
                <form method="POST" class="inline" onsubmit="return confirm('Delete?')">
                  <?= csrf_field() ?><input type="hidden" name="action" value="delete_section"><input type="hidden" name="section_id" value="<?= $ind['id'] ?>">
                  <button class="text-red-500 font-semibold">Delete</button>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <form method="POST" class="border-t border-gray-100 p-5 space-y-3">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_section">
            <input type="hidden" name="section_type" value="industry">
            <input type="hidden" name="section_id" value="<?= ($edit_section && ($edit_section['section_type']??'') === 'industry') ? $edit_section['id'] : 0 ?>">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Industry Name *</label>
                <input type="text" name="section_title" required value="<?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'industry') ? $edit_section['title'] : '') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Icon</label>
                <input type="text" name="section_icon" value="<?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'industry') ? $edit_section['icon'] : 'IN') ?>"
                       class="w-20 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort</label>
                <input type="number" name="section_sort" value="<?= ($edit_section && ($edit_section['section_type']??'') === 'industry') ? $edit_section['sort_order'] : 0 ?>"
                       class="w-20 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description</label>
              <textarea name="section_description" rows="2" class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars(($edit_section && ($edit_section['section_type']??'') === 'industry') ? $edit_section['description'] : '') ?></textarea>
            </div>
            <input type="hidden" name="section_status" value="1">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition">
              <?= ($edit_section && ($edit_section['section_type']??'') === 'industry') ? 'Update' : 'Add Industry' ?>
            </button>
          </form>
        </div>
      </div>

      <!-- PARTNER LOGOS -->
      <div x-data="{ open: <?= ($edit_partner) ? 'true' : 'false' ?> }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-700">PL</span>
            <div>
              <h2 class="font-bold text-gray-800 text-sm">Partner Logos</h2>
              <p class="text-[10px] text-gray-400"><?= count($partners) ?> partner<?= count($partners) !== 1 ? 's' : '' ?> listed</p>
            </div>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <!-- Partners List -->
          <div class="border-t border-gray-50 p-5 space-y-3">
            <?php if(empty($partners)): ?>
            <p class="text-xs text-gray-400 text-center py-4">No partners added yet.</p>
            <?php else: ?>
              <?php foreach($partners as $p): ?>
              <div class="flex items-center gap-4 bg-gray-50 rounded-lg p-3 border border-gray-100">
                <?php if (!empty($p['logo'])): ?>
                  <img src="<?= htmlspecialchars((str_starts_with($p['logo'], '/') || str_starts_with($p['logo'], 'http')) ? $p['logo'] : '/' . $p['logo']) ?>" class="w-16 h-10 rounded object-contain bg-white border p-1" alt="">
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold text-gray-800 truncate"><?= htmlspecialchars($p['company_name']) ?></p>
                  <p class="text-[10px] text-gray-400">Sort Order: <?= $p['sort_order'] ?></p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $p['is_active'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500' ?>">
                  <?= $p['is_active'] ? 'Active' : 'Hidden' ?>
                </span>
                <div class="flex gap-2 text-xs">
                  <a href="?edit_partner=<?= $p['id'] ?>" class="text-blue-600 font-semibold hover:underline">Edit</a>
                  <form method="POST" class="inline" onsubmit="return confirm('Delete this partner logo?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_partner">
                    <input type="hidden" name="partner_id" value="<?= $p['id'] ?>">
                    <button class="text-red-500 font-semibold hover:underline">Delete</button>
                  </form>
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Add/Edit Partner Form -->
          <form method="POST" enctype="multipart/form-data" class="border-t border-gray-100 p-5 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_partner">
            <input type="hidden" name="partner_id" value="<?= $edit_partner['id'] ?? 0 ?>">
            <input type="hidden" name="existing_logo" value="<?= htmlspecialchars($edit_partner['logo'] ?? '') ?>">

            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider"><?= $edit_partner ? 'Edit Partner Logo' : 'Add New Partner Logo' ?></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Company Name *</label>
                <input type="text" name="company_name" required value="<?= htmlspecialchars($edit_partner['company_name'] ?? '') ?>" placeholder="e.g. Client One"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Logo Upload *</label>
                <input type="file" name="partner_logo" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
                <?php if ($edit_partner && !empty($edit_partner['logo'])): ?>
                  <p class="text-[10px] text-gray-400 mt-1">Current: <?= htmlspecialchars(basename($edit_partner['logo'])) ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort Order</label>
                <input type="number" name="partner_sort" value="<?= $edit_partner['sort_order'] ?? 0 ?>"
                       class="w-24 bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
              <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                  <input type="checkbox" name="partner_status" value="1" <?= (!isset($edit_partner['is_active']) || $edit_partner['is_active']) ? 'checked' : '' ?> class="rounded text-blue-600">
                  Active
                </label>
              </div>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition">
                <?= $edit_partner ? 'Update Partner' : 'Add Partner' ?>
              </button>
              <?php if($edit_partner): ?>
              <a href="home-about.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-xs uppercase transition">Cancel</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>

      <!-- CORE SOLUTIONS -->
      <div x-data="{ open: <?= ($edit_solution) ? 'true' : 'false' ?> }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-[10px] font-bold text-amber-700">CS</span>
            <div>
              <h2 class="font-bold text-gray-800 text-sm">Core Solutions</h2>
              <p class="text-[10px] text-gray-400"><?= count($solutions) ?> solution card<?= count($solutions) !== 1 ? 's' : '' ?> listed</p>
            </div>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <!-- Solutions List -->
          <div class="border-t border-gray-50 p-5 space-y-3">
            <?php if(empty($solutions)): ?>
            <p class="text-xs text-gray-400 text-center py-4">No solutions added yet.</p>
            <?php else: ?>
              <?php foreach($solutions as $sol): ?>
              <div class="flex items-center gap-4 bg-gray-50 rounded-lg p-3 border border-gray-100">
                <span class="text-2xl p-1 bg-white rounded border w-10 h-10 flex items-center justify-center"><?= htmlspecialchars($sol['icon']) ?></span>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold text-gray-800 truncate"><?= htmlspecialchars($sol['title']) ?></p>
                  <p class="text-[10px] text-gray-400">Sort Order: <?= $sol['sort_order'] ?> &middot; Link: <?= htmlspecialchars($sol['button_link']) ?></p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $sol['is_active'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500' ?>">
                  <?= $sol['is_active'] ? 'Active' : 'Hidden' ?>
                </span>
                <div class="flex gap-2 text-xs">
                  <a href="?edit_solution=<?= $sol['id'] ?>" class="text-blue-600 font-semibold hover:underline">Edit</a>
                  <form method="POST" class="inline" onsubmit="return confirm('Delete this solution card?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_solution">
                    <input type="hidden" name="solution_id" value="<?= $sol['id'] ?>">
                    <button class="text-red-500 font-semibold hover:underline">Delete</button>
                  </form>
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Add/Edit Solution Form -->
          <form method="POST" class="border-t border-gray-100 p-5 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_solution">
            <input type="hidden" name="solution_id" value="<?= $edit_solution['id'] ?? 0 ?>">

            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider"><?= $edit_solution ? 'Edit Solution Card' : 'Add New Solution Card' ?></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="md:col-span-2">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Solution Title *</label>
                <input type="text" name="solution_title" required value="<?= htmlspecialchars($edit_solution['title'] ?? '') ?>" placeholder="e.g. Customer Support Services"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Icon (emoji / HTML character)</label>
                <input type="text" name="solution_icon" value="<?= htmlspecialchars($edit_solution['icon'] ?? 'CS') ?>" placeholder="e.g. CS"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description *</label>
              <textarea name="solution_description" required rows="3" placeholder="Brief outline of the service..."
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($edit_solution['description'] ?? '') ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Button Text</label>
                <input type="text" name="solution_button_text" value="<?= htmlspecialchars($edit_solution['button_text'] ?? 'Explore Solutions') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
              <div class="md:col-span-2">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Button Link</label>
                <input type="text" name="solution_button_link" value="<?= htmlspecialchars($edit_solution['button_link'] ?? '/services.php') ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none font-mono">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort Order</label>
                <input type="number" name="solution_sort" value="<?= $edit_solution['sort_order'] ?? 0 ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none">
              </div>
            </div>
            <div class="flex items-center justify-between">
              <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                <input type="checkbox" name="solution_status" value="1" <?= (!isset($edit_solution['is_active']) || $edit_solution['is_active']) ? 'checked' : '' ?> class="rounded text-blue-600">
                Active
              </label>
              <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition">
                  <?= $edit_solution ? 'Update Solution' : 'Add Solution' ?>
                </button>
                <?php if($edit_solution): ?>
                <a href="home-about.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-xs uppercase transition">Cancel</a>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- HOW IT WORKS STEPS -->
      <div x-data="{ open: <?= ($edit_step) ? 'true' : 'false' ?> }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-[10px] font-bold text-teal-700">HW</span>
            <div>
              <h2 class="font-bold text-gray-800 text-sm">How It Works Steps</h2>
              <p class="text-[10px] text-gray-400"><?= count($steps) ?> process step<?= count($steps) !== 1 ? 's' : '' ?> listed</p>
            </div>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <!-- Steps List -->
          <div class="border-t border-gray-50 p-5 space-y-3">
            <?php if(empty($steps)): ?>
            <p class="text-xs text-gray-400 text-center py-4">No process steps added yet.</p>
            <?php else: ?>
              <?php foreach($steps as $st): ?>
              <div class="flex items-center gap-4 bg-gray-50 rounded-lg p-3 border border-gray-100">
                <span class="text-xs font-bold w-8 h-8 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center shrink-0 font-poppins">Step <?= $st['step_number'] ?></span>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold text-gray-800 truncate"><?= htmlspecialchars($st['title']) ?></p>
                  <p class="text-[10px] text-gray-400">Sort Order: <?= $st['sort_order'] ?></p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $st['is_active'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500' ?>">
                  <?= $st['is_active'] ? 'Active' : 'Hidden' ?>
                </span>
                <div class="flex gap-2 text-xs">
                  <a href="?edit_step=<?= $st['id'] ?>" class="text-blue-600 font-semibold hover:underline">Edit</a>
                  <form method="POST" class="inline" onsubmit="return confirm('Delete this process step?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete_step">
                    <input type="hidden" name="step_id" value="<?= $st['id'] ?>">
                    <button class="text-red-500 font-semibold hover:underline">Delete</button>
                  </form>
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Add/Edit Step Form -->
          <form method="POST" class="border-t border-gray-100 p-5 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_step">
            <input type="hidden" name="step_id" value="<?= $edit_step['id'] ?? 0 ?>">

            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider"><?= $edit_step ? 'Edit Process Step' : 'Add New Process Step' ?></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Step Number *</label>
                <input type="number" name="step_number" required value="<?= htmlspecialchars($edit_step['step_number'] ?? '1') ?>" placeholder="e.g. 1"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
              <div class="md:col-span-2">
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Step Title *</label>
                <input type="text" name="step_title" required value="<?= htmlspecialchars($edit_step['title'] ?? '') ?>" placeholder="e.g. Tell Us What You Need"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition">
              </div>
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Description *</label>
              <textarea name="step_description" required rows="3" placeholder="Step description details..."
                        class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($edit_step['description'] ?? '') ?></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Sort Order</label>
                <input type="number" name="step_sort" value="<?= $edit_step['sort_order'] ?? 0 ?>"
                       class="w-24 bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none font-poppins">
              </div>
              <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                  <input type="checkbox" name="step_status" value="1" <?= (!isset($edit_step['is_active']) || $edit_step['is_active']) ? 'checked' : '' ?> class="rounded text-blue-600">
                  Active
                </label>
              </div>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg text-xs uppercase tracking-wider transition">
                <?= $edit_step ? 'Update Step' : 'Add Step' ?>
              </button>
              <?php if($edit_step): ?>
              <a href="home-about.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg text-xs uppercase transition">Cancel</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>

      <!-- HOMEPAGE CTA SETTINGS -->
      <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-[10px] font-bold text-rose-700">CTA</span>
            <h2 class="font-bold text-gray-800 text-sm">Homepage Call-to-Action (CTA) Settings</h2>
          </div>
          <span class="text-gray-400 text-xs" :class="open ? '' : '-rotate-90'" style="transition:transform .2s">&darr;</span>
        </button>
        <div x-show="open" x-collapse>
          <form method="POST" class="border-t border-gray-50 p-5 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_homepage_cta">
            
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">CTA Heading *</label>
              <input type="text" name="cta_heading" required value="<?= htmlspecialchars($settings['cta_heading']) ?>" placeholder="e.g. Ready to become our next success story?"
                     class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition font-poppins">
            </div>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">CTA Text *</label>
              <textarea name="cta_text" rows="3" required class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($settings['cta_text']) ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Button Text *</label>
                <input type="text" name="cta_button_text" required value="<?= htmlspecialchars($settings['cta_button_text']) ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition font-poppins">
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">Button URL *</label>
                <input type="text" name="cta_button_url" required value="<?= htmlspecialchars($settings['cta_button_url']) ?>"
                       class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition font-mono">
              </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition">
              Save CTA Settings
            </button>
          </form>
        </div>
      </div>

    </div>
  </main>
  
  <script>
  function toggleMediaFields() {
      const mediaType = document.getElementById('media_type').value;
      const imgContainer = document.getElementById('image_field_container');
      const vidContainer = document.getElementById('video_field_container');
      const posterContainer = document.getElementById('poster_field_container');
      
      if (mediaType === 'image') {
          imgContainer.style.display = 'block';
          vidContainer.style.display = 'none';
          posterContainer.style.display = 'none';
      } else {
          imgContainer.style.display = 'none';
          vidContainer.style.display = 'block';
          posterContainer.style.display = 'block';
      }
  }
  document.addEventListener('DOMContentLoaded', toggleMediaFields);
  </script>

<?php include '../includes/admin-footer.php'; ?>
