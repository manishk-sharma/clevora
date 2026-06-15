<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';
$edit_t = null;

// Get testimonal to edit
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
            $stmt->execute([$edit_id]);
            $edit_t = $stmt->fetch();
        } catch(Exception $e) {
            $error = 'Error loading item: ' . $e->getMessage();
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT photo_url FROM testimonials WHERE id = ?");
            $stmt->execute([$del_id]);
            $row = $stmt->fetch();
            if ($row && !empty($row['photo_url'])) {
                // Delete photo file
                $localPath = __DIR__ . '/../../' . ltrim($row['photo_url'], '/');
                if (file_exists($localPath)) {
                    @unlink($localPath);
                }
            }
            $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
            $stmt->execute([$del_id]);
            header('Location: testimonials.php?success=Testimonial deleted successfully');
            exit;
        } catch (Exception $e) {
            $error = 'Failed to delete: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

// Handle Add / Edit submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $quote = trim($_POST['quote'] ?? '');
    $active = isset($_POST['is_active']) ? 1 : 0;
    $submit_id = (int)($_POST['id'] ?? 0);

    if (empty($name) || empty($quote)) {
        $error = 'Name and Quote details are required.';
    } else {
        // Handle photo upload
        $photo_url = $edit_t['photo_url'] ?? '';
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed)) {
                $fname = 'testimonial_' . time() . '.' . $ext;
                if (!is_dir(UPLOAD_PATH)) {
                    mkdir(UPLOAD_PATH, 0775, true);
                }
                if (move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_PATH . $fname)) {
                    // Delete old photo if editing and new uploaded
                    if ($submit_id && !empty($edit_t['photo_url'])) {
                        $oldLocal = __DIR__ . '/../../' . ltrim($edit_t['photo_url'], '/');
                        if (file_exists($oldLocal)) {
                            @unlink($oldLocal);
                        }
                    }
                    $photo_url = '/assets/images/uploads/' . $fname;
                } else {
                    $error = 'Failed to save uploaded photo file.';
                }
            } else {
                $error = 'Invalid image type. Allowed: png, jpg, jpeg, webp';
            }
        }

        if (empty($error)) {
            if ($submit_id) {
                try {
                    $stmt = $pdo->prepare("UPDATE testimonials SET name=?, location=?, quote=?, photo_url=?, is_active=? WHERE id=?");
                    $stmt->execute([$name, $location, $quote, $photo_url, $active, $submit_id]);
                    header('Location: testimonials.php?success=Testimonial updated successfully');
                    exit;
                } catch(Exception $e) {
                    $error = 'Database edit failed: ' . $e->getMessage();
                }
            } else {
                try {
                    $stmt = $pdo->prepare("INSERT INTO testimonials (name, location, quote, photo_url, is_active) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $location, $quote, $photo_url, $active]);
                    header('Location: testimonials.php?success=Testimonial added successfully');
                    exit;
                } catch(Exception $e) {
                    $error = 'Database save failed: ' . $e->getMessage();
                }
            }
        }
    }
}

// Fetch testimonials
$testimonials = [];
if ($pdo) {
    try {
        $testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();
    } catch(Exception $e) {
        $error = 'Failed to fetch testimonials list: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Testimonials | Clevora Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="flex bg-gray-50 min-h-screen font-sans">

  <!-- SIDEBAR -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-8 md:p-12">
    <div class="max-w-5xl mx-auto space-y-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Manage Testimonials</h1>
        <p class="text-xs text-gray-400">Add, edit, or delete customer reviews displayed on the homepage slider.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Form Add/Edit -->
        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">
            <?= $edit_t ? 'Edit Testimonial' : 'Create Testimonial' ?>
          </h2>
          <?php if($edit_t): ?>
          <input type="hidden" name="id" value="<?= htmlspecialchars($edit_t['id']) ?>">
          <?php endif; ?>

          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Client Name *</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($edit_t['name'] ?? '') ?>" placeholder="e.g. John Doe"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Location</label>
            <input type="text" name="location" value="<?= htmlspecialchars($edit_t['location'] ?? '') ?>" placeholder="e.g. New York, USA"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Client Photo</label>
            <input type="file" name="photo" class="text-xs w-full bg-gray-50 p-2 rounded border border-gray-200">
            <?php if ($edit_t && !empty($edit_t['photo_url'])): ?>
            <div class="mt-1 text-[10px] text-gray-400">Current: <a href="<?= htmlspecialchars($edit_t['photo_url']) ?>" target="_blank" class="text-blue-500 hover:underline"><?= basename($edit_t['photo_url']) ?></a></div>
            <?php endif; ?>
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Client Quote *</label>
            <textarea name="quote" required rows="4" placeholder="Feedback quote..."
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($edit_t['quote'] ?? '') ?></textarea>
          </div>
          <div class="flex items-center">
            <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 cursor-pointer">
              <input type="checkbox" name="is_active" value="1" <?= (!isset($edit_t['is_active']) || $edit_t['is_active'] == 1) ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              Active & Visible
            </label>
          </div>
          <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition duration-300">
              <?= $edit_t ? 'Save Changes' : 'Add Testimonial' ?>
            </button>
            <?php if($edit_t): ?>
            <a href="testimonials.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-3 py-2.5 rounded text-xs uppercase transition">Cancel</a>
            <?php endif; ?>
          </div>
        </form>

        <!-- Current Testimonials -->
        <div class="lg:col-span-2 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-2">All Testimonials</h2>
          <div class="space-y-4">
            <?php if(empty($testimonials)): ?>
            <div class="bg-white rounded-xl border p-8 text-center text-xs text-gray-400">No testimonials created yet.</div>
            <?php else: ?>
              <?php foreach($testimonials as $t): ?>
              <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm flex gap-4 relative">
                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-50 shrink-0 border">
                  <?php if(!empty($t['photo_url'])): ?>
                  <img src="<?= htmlspecialchars($t['photo_url']) ?>" alt="" class="w-full h-full object-cover">
                  <?php else: ?>
                  <div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                    <?= strtoupper(substr($t['name'], 0, 1)) ?>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="flex-1 space-y-2">
                  <div>
                    <div class="flex items-center justify-between">
                      <h4 class="font-bold text-gray-800 text-xs"><?= htmlspecialchars($t['name']) ?></h4>
                      <div class="flex gap-3 text-xs">
                        <a href="?edit=<?= $t['id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                        <a href="?delete=<?= $t['id'] ?>" class="text-red-500 hover:underline"
                           onclick="return confirm('Delete this testimonial?')">Delete</a>
                      </div>
                    </div>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase"><?= htmlspecialchars($t['location']) ?></p>
                  </div>
                  <p class="text-gray-600 text-xs italic">"<?= htmlspecialchars(substr($t['quote'], 0, 120)) ?>..."</p>
                  <div>
                    <span class="px-2.5 py-0.5 text-[9px] font-bold uppercase rounded-full <?= $t['is_active'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500 border border-gray-200' ?>">
                      <?= $t['is_active'] ? 'Active' : 'Hidden' ?>
                    </span>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
</html>
