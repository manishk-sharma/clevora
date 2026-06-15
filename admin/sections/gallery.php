<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT image_url FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if ($row) {
                // Delete file from disk
                $localPath = $_SERVER['DOCUMENT_ROOT'] . $row['image_url'];
                if (file_exists($localPath)) {
                    @unlink($localPath);
                } else {
                    // Try relative path from clevora root
                    $relativePath = __DIR__ . '/../../' . ltrim($row['image_url'], '/');
                    if (file_exists($relativePath)) {
                        @unlink($relativePath);
                    }
                }
            }
            $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: gallery.php?success=Image deleted successfully');
            exit;
        } catch (Exception $e) {
            $error = 'Failed to delete image: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

// Handle Add/Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = trim($_POST['caption'] ?? '');
    $order = (int)($_POST['sort_order'] ?? 0);

    if (empty($_FILES['image']['name'])) {
        $error = 'Please select an image file to upload.';
    } else {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
        if (in_array($ext, $allowed)) {
            $fname = 'gallery_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (!is_dir(UPLOAD_PATH)) {
                mkdir(UPLOAD_PATH, 0775, true);
            }
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $fname)) {
                $image_url = '/assets/images/uploads/' . $fname;
                try {
                    $stmt = $pdo->prepare("INSERT INTO gallery (image_url, caption, sort_order) VALUES (?, ?, ?)");
                    $stmt->execute([$image_url, $caption, $order]);
                    header('Location: gallery.php?success=Image uploaded successfully');
                    exit;
                } catch(Exception $e) {
                    $error = 'Database save failed: ' . $e->getMessage();
                }
            } else {
                $error = 'Failed to move uploaded file to destination.';
            }
        } else {
            $error = 'Invalid image type. Allowed: jpg, jpeg, png, svg, webp';
        }
    }
}

// Fetch images
$images = [];
if ($pdo) {
    try {
        $images = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, id DESC")->fetchAll();
    } catch(Exception $e) {
        $error = 'Failed to fetch gallery: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Gallery | Clevora Admin</title>
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
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Manage Album Gallery</h1>
        <p class="text-xs text-gray-400">Upload photos of Clevora workspaces, activities, and compliance milestones.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Upload Form -->
        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">Upload Photo</h2>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Select Image File *</label>
            <input type="file" name="image" required class="text-xs w-full bg-gray-50 p-2.5 rounded border border-gray-200">
            <p class="text-[9px] text-gray-400 mt-1">Accepts: png, jpg, jpeg, svg, webp</p>
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Caption</label>
            <input type="text" name="caption" placeholder="e.g. Workspace Facilities"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" value="0"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <button type="submit"
                  class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition duration-300">
            Upload to Gallery
          </button>
        </form>

        <!-- Current Images Grid -->
        <div class="lg:col-span-2 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-2">Active Gallery Images</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php if(empty($images)): ?>
            <div class="sm:col-span-2 bg-white rounded-xl border p-8 text-center text-xs text-gray-400">No images uploaded yet.</div>
            <?php else: ?>
              <?php foreach($images as $img): ?>
              <div class="bg-white border border-gray-100 rounded-xl p-3 shadow-sm flex flex-col justify-between group">
                <div class="space-y-2">
                  <div class="h-32 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center border border-gray-100">
                    <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="" class="max-h-full max-w-full object-cover">
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-800 line-clamp-1"><?= htmlspecialchars($img['caption'] ?: '(No Caption)') ?></p>
                    <p class="text-[10px] text-gray-400 font-semibold font-mono">Sort Order: <?= htmlspecialchars($img['sort_order']) ?></p>
                  </div>
                </div>
                <div class="pt-3 border-t border-gray-50 flex justify-between items-center text-xs mt-3">
                  <a href="<?= htmlspecialchars($img['image_url']) ?>" target="_blank" class="text-blue-500 hover:underline">View File</a>
                  <a href="?delete=<?= $img['id'] ?>" class="text-red-500 hover:text-red-600 font-semibold"
                     onclick="return confirm('Delete this image from gallery?')">Delete</a>
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
