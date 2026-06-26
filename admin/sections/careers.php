<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Manage Careers | Clevora Admin';
$msg = '';
$error = '';

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

// 1. Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM careers WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: careers.php?success=Job opening deleted successfully.');
        exit;
    } catch(Exception $e) {
        $error = 'Error deleting job: ' . $e->getMessage();
    }
}

// 2. Handle Status Toggle
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    try {
        $stmt = $pdo->prepare("UPDATE careers SET is_active = 1 - is_active WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: careers.php?success=Job status updated.');
        exit;
    } catch (Exception $e) {
        $error = 'Error toggling status: ' . $e->getMessage();
    }
}

// 3. Handle Add / Edit Submission
$action = isset($_GET['action']) ? $_GET['action'] : '';
$edit_id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $job_title = trim($_POST['job_title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $job_type = trim($_POST['job_type'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    // Process Responsibilities Repeater
    $responsibilities = [];
    if (isset($_POST['responsibilities']) && is_array($_POST['responsibilities'])) {
        foreach ($_POST['responsibilities'] as $r) {
            $r = trim($r);
            if (!empty($r)) {
                $responsibilities[] = $r;
            }
        }
    }
    $responsibilities_json = json_encode($responsibilities);

    // Process Requirements Repeater
    $requirements = [];
    if (isset($_POST['requirements']) && is_array($_POST['requirements'])) {
        foreach ($_POST['requirements'] as $req) {
            $req = trim($req);
            if (!empty($req)) {
                $requirements[] = $req;
            }
        }
    }
    $requirements_json = json_encode($requirements);

    if (empty($job_title) || empty($slug)) {
        $error = 'Job Title and Slug are required fields.';
    } else {
        try {
            if ($edit_id > 0) {
                // Edit existing job
                $stmt = $pdo->prepare("
                    UPDATE careers 
                    SET job_title = ?, slug = ?, department = ?, location = ?, job_type = ?, experience = ?, short_description = ?, responsibilities = ?, requirements = ?, is_active = ?, sort_order = ? 
                    WHERE id = ?
                ");
                $stmt->execute([
                    $job_title, $slug, $department, $location, $job_type, $experience, 
                    $short_description, $responsibilities_json, $requirements_json, $is_active, $sort_order, $edit_id
                ]);
                header('Location: careers.php?success=Job opening updated successfully.');
                exit;
            } else {
                // Add new job
                $stmt = $pdo->prepare("
                    INSERT INTO careers (job_title, slug, department, location, job_type, experience, short_description, responsibilities, requirements, is_active, sort_order) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $job_title, $slug, $department, $location, $job_type, $experience, 
                    $short_description, $responsibilities_json, $requirements_json, $is_active, $sort_order
                ]);
                header('Location: careers.php?success=New job opening added successfully.');
                exit;
            }
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $error = 'A job opening with this slug already exists.';
            } else {
                $error = 'Database operation failed: ' . $e->getMessage();
            }
        }
    }
}

// 4. Fetch Job Data for Editing
$job = null;
if ($edit_id > 0 && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM careers WHERE id = ?");
    $stmt->execute([$edit_id]);
    $job = $stmt->fetch();
    if (!$job) {
        header('Location: careers.php?error=Job not found.');
        exit;
    }
}

// 5. Fetch All Jobs for Listing
$jobs = [];
if ($pdo) {
    try {
        $jobs = $pdo->query("SELECT * FROM careers ORDER BY sort_order ASC, id DESC")->fetchAll();
    } catch(Exception $e) {
        $error = 'Failed to fetch jobs: ' . $e->getMessage();
    }
}
?>

<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="flex-1 p-6 md:p-10 overflow-auto">
  <div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Header Area -->
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Careers Management</h1>
        <p class="text-xs text-gray-400 mt-1">Add, edit, or delete dynamic job openings displayed on the website.</p>
      </div>
      <?php if (empty($action)): ?>
        <a href="careers.php?action=add" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          + Add Job Opening
        </a>
      <?php else: ?>
        <a href="careers.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2 rounded-lg text-xs uppercase tracking-wider transition-all">
          ← Back to List
        </a>
      <?php endif; ?>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Action View: Add or Edit Form -->
    <?php if ($action === 'add' || $action === 'edit' || $job): ?>
      <form method="POST" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 md:p-8 space-y-6">
        <?= csrf_field() ?>
        <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-3"><?= $job ? 'Edit Job Opening' : 'Add New Job Opening' ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          
          <!-- Job Title -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Job Title *</label>
            <input type="text" name="job_title" id="job_title" required 
                   value="<?= htmlspecialchars($job['job_title'] ?? '') ?>" 
                   placeholder="e.g. Business Development Associate / Sr Associate"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>

          <!-- Slug -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Slug (URL friendly) *</label>
            <input type="text" name="slug" id="slug" required 
                   value="<?= htmlspecialchars($job['slug'] ?? '') ?>" 
                   placeholder="e.g. business-development-associate"
                   data-slug-source="#job_title"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-mono">
          </div>

          <!-- Department -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Department *</label>
            <input type="text" name="department" required 
                   value="<?= htmlspecialchars($job['department'] ?? '') ?>" 
                   placeholder="e.g. Sales & Business Development"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>

          <!-- Location -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Location *</label>
            <input type="text" name="location" required 
                   value="<?= htmlspecialchars($job['location'] ?? 'Delhi, India') ?>" 
                   placeholder="e.g. Delhi, India"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-sans">
          </div>

          <!-- Employment Type -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Employment Type *</label>
            <select name="job_type" required 
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
              <?php
              $types = ['Full-Time', 'Part-Time', 'Remote', 'Hybrid'];
              $current_type = $job['job_type'] ?? 'Full-Time';
              foreach ($types as $t):
                $sel = (strtolower(str_replace('-', '', $t)) === strtolower(str_replace('-', '', $current_type))) ? 'selected' : '';
              ?>
                <option value="<?= $t ?>" <?= $sel ?>><?= $t ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Experience Requirements -->
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Experience *</label>
            <input type="text" name="experience" required 
                   value="<?= htmlspecialchars($job['experience'] ?? '') ?>" 
                   placeholder="e.g. 1-3 Years"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all font-sans">
          </div>
        </div>

        <!-- Short Description -->
        <div>
          <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Short Description *</label>
          <textarea name="short_description" required rows="3" 
                    placeholder="Provide a brief summary of the role..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($job['short_description'] ?? '') ?></textarea>
        </div>

        <!-- Responsibilities Repeater -->
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <label class="block text-[10px] font-bold text-gray-600 uppercase">Responsibilities *</label>
            <button type="button" data-repeater-add="#responsibilities-list" class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded transition">
              + Add Responsibility
            </button>
          </div>
          <div id="responsibilities-list" class="space-y-2">
            <!-- Template Row -->
            <div data-repeater-template style="display: none;" class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-100 relative">
              <input type="text" name="responsibilities[]" placeholder="e.g. Identify new business opportunities" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
              <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 flex-shrink-0">Delete</button>
            </div>
            
            <!-- Existing Rows -->
            <?php
            $resps = json_decode($job['responsibilities'] ?? '[]', true);
            if (empty($resps)) $resps = ['']; // at least one input by default
            foreach ($resps as $r):
            ?>
            <div class="repeater-row flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-100 relative">
              <input type="text" name="responsibilities[]" value="<?= htmlspecialchars($r) ?>" placeholder="e.g. Identify new business opportunities" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
              <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 flex-shrink-0">Delete</button>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Requirements Repeater -->
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <label class="block text-[10px] font-bold text-gray-600 uppercase">Requirements *</label>
            <button type="button" data-repeater-add="#requirements-list" class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded transition">
              + Add Requirement
            </button>
          </div>
          <div id="requirements-list" class="space-y-2">
            <!-- Template Row -->
            <div data-repeater-template style="display: none;" class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-100 relative">
              <input type="text" name="requirements[]" placeholder="e.g. Good communication skills" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
              <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 flex-shrink-0">Delete</button>
            </div>
            
            <!-- Existing Rows -->
            <?php
            $reqs = json_decode($job['requirements'] ?? '[]', true);
            if (empty($reqs)) $reqs = ['']; // at least one input by default
            foreach ($reqs as $req):
            ?>
            <div class="repeater-row flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-100 relative">
              <input type="text" name="requirements[]" value="<?= htmlspecialchars($req) ?>" placeholder="e.g. Good communication skills" class="w-full bg-white border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
              <button type="button" data-repeater-remove class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 flex-shrink-0">Delete</button>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Sort Order and Status Toggle -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-4">
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" value="<?= $job['sort_order'] ?? 0 ?>"
                   class="w-32 bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div class="flex items-center">
            <label class="flex items-center gap-2.5 text-xs text-gray-700 font-semibold cursor-pointer">
              <input type="checkbox" name="is_active" value="1" <?= (!isset($job) || ($job['is_active'] ?? 1)) ? 'checked' : '' ?> 
                     class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
              Publish Role (Visible on Career Page)
            </label>
          </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition duration-300">
          <?= $job ? 'Save Changes' : 'Create Job Opening' ?>
        </button>
      </form>

    <!-- Default view: List jobs -->
    <?php else: ?>
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div style="overflow-x:auto;">
          <table class="w-full border-collapse font-sans text-xs text-left">
            <thead>
              <tr class="bg-gray-50 border-bottom border-gray-150">
                <th class="p-4 font-bold text-gray-500 uppercase tracking-wider text-[10px]">Sort</th>
                <th class="p-4 font-bold text-gray-500 uppercase tracking-wider text-[10px]">Job Title</th>
                <th class="p-4 font-bold text-gray-500 uppercase tracking-wider text-[10px]">Department</th>
                <th class="p-4 font-bold text-gray-500 uppercase tracking-wider text-[10px]">Location</th>
                <th class="p-4 font-bold text-gray-500 uppercase tracking-wider text-[10px]">Type</th>
                <th class="p-4 font-bold text-gray-500 uppercase tracking-wider text-[10px] text-center">Status</th>
                <th class="p-4 font-bold text-gray-500 uppercase tracking-wider text-[10px] text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($jobs)): ?>
                <tr>
                  <td colspan="7" class="text-center p-8 text-gray-400">No job openings found. Click "+ Add Job Opening" to create one.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($jobs as $j): ?>
                  <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 font-mono text-gray-400 font-semibold"><?= $j['sort_order'] ?></td>
                    <td class="p-4 font-bold text-gray-700">
                      <span class="block text-xs"><?= htmlspecialchars($j['job_title']) ?></span>
                      <span class="block text-[10px] text-gray-400 font-mono font-normal mt-0.5"><?= htmlspecialchars($j['slug']) ?></span>
                    </td>
                    <td class="p-4 text-gray-500"><?= htmlspecialchars($j['department']) ?></td>
                    <td class="p-4 text-gray-500"><?= htmlspecialchars($j['location']) ?></td>
                    <td class="p-4">
                      <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase">
                        <?= htmlspecialchars($j['job_type']) ?>
                      </span>
                    </td>
                    <td class="p-4 text-center">
                      <a href="careers.php?toggle_status=<?= $j['id'] ?>" title="Click to toggle status"
                         class="inline-block px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider <?= $j['is_active'] ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' ?>">
                        <?= $j['is_active'] ? 'Active' : 'Disabled' ?>
                      </a>
                    </td>
                    <td class="p-4 text-right space-x-2">
                      <a href="careers.php?action=edit&id=<?= $j['id'] ?>" class="text-blue-600 hover:text-blue-800 font-bold">Edit</a>
                      <a href="careers.php?delete=<?= $j['id'] ?>" data-confirm="Are you sure you want to delete this job opening?" class="text-red-500 hover:text-red-700 font-bold ml-2">Delete</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

  </div>
</main>

<?php include '../includes/admin-footer.php'; ?>
