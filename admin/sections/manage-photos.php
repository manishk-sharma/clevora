<?php
require_once '../middleware/auth.php';

$album_id = (int)($_GET['album_id'] ?? 0);
if ($album_id <= 0) {
    header('Location: gallery.php?error=Invalid album ID.');
    exit;
}

// Fetch album details
$stmt = $pdo->prepare("SELECT * FROM gallery_albums WHERE id = ?");
$stmt->execute([$album_id]);
$album = $stmt->fetch();

if (!$album) {
    header('Location: gallery.php?error=Album not found.');
    exit;
}

$adminPageTitle = 'Manage Album Photos | Clevora Admin';
$msg = ''; $error = '';
if (isset($_GET['success'])) $msg = $_GET['success'];
if (isset($_GET['error'])) $error = $_GET['error'];

// 1. Delete Photo Handler
if (isset($_GET['delete_image'])) {
    $img_id = (int)$_GET['delete_image'];
    try {
        $stmtImg = $pdo->prepare("SELECT image FROM gallery_images WHERE id = ? AND album_id = ?");
        $stmtImg->execute([$img_id, $album_id]);
        $img = $stmtImg->fetch();
        if ($img) {
            $p = __DIR__ . '/../../' . ltrim($img['image'], '/');
            if (file_exists($p)) @unlink($p);
            
            $pdo->prepare("DELETE FROM gallery_images WHERE id = ? AND album_id = ?")->execute([$img_id, $album_id]);
            header("Location: manage-photos.php?album_id=$album_id&success=Photo deleted successfully.");
            exit;
        } else {
            $error = "Photo not found.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// 2. Multiple Images Upload Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images']) && verify_csrf()) {
    $uploaded = 0;
    $failed = 0;
    $files = $_FILES['images'];
    $count = count($files['name']);
    
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
        
        $fileObj = [
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        ];
        
        $v = validate_upload($fileObj);
        if ($v === true) {
            $url = save_upload($fileObj, 'gallery_photo');
            if ($url) {
                // Get next sort order
                $sort_stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM gallery_images WHERE album_id = ?");
                $sort_stmt->execute([$album_id]);
                $next_sort = $sort_stmt->fetchColumn();
                
                $stmtIns = $pdo->prepare("INSERT INTO gallery_images (album_id, image, caption, sort_order, is_active, created_at, updated_at) VALUES (?, ?, '', ?, 1, NOW(), NOW())");
                $stmtIns->execute([$album_id, $url, $next_sort]);
                $uploaded++;
            } else {
                $failed++;
            }
        } else {
            $failed++;
        }
    }
    
    if ($uploaded > 0) {
        header("Location: manage-photos.php?album_id=$album_id&success=" . urlencode("$uploaded photo(s) uploaded successfully." . ($failed > 0 ? " ($failed failed)" : "")));
        exit;
    } else if ($failed > 0) {
        $error = "Failed to upload photos. Please check file formats and size.";
    }
}

// 3. Save Captions & Sorting Order Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes']) && verify_csrf()) {
    try {
        $captions = $_POST['captions'] ?? [];
        $sort_orders = $_POST['sort_orders'] ?? [];
        
        foreach ($captions as $img_id => $caption) {
            $img_id = (int)$img_id;
            $caption = trim($caption);
            $sort = (int)($sort_orders[$img_id] ?? 0);
            
            $pdo->prepare("UPDATE gallery_images SET caption = ?, sort_order = ? WHERE id = ? AND album_id = ?")
                ->execute([$caption, $sort, $img_id, $album_id]);
        }
        header("Location: manage-photos.php?album_id=$album_id&success=Photo captions and sorting updated.");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch existing photos
$photos = [];
if ($pdo) {
    $stmtPhotos = $pdo->prepare("SELECT * FROM gallery_images WHERE album_id = ? ORDER BY sort_order ASC, id ASC");
    $stmtPhotos->execute([$album_id]);
    $photos = $stmtPhotos->fetchAll();
}
?>
<?php include '../includes/admin-header.php'; ?>
  <?php include '../includes/sidebar.php'; ?>
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-6xl mx-auto space-y-8">
      <!-- Breadcrumb & Back navigation -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-xs text-gray-400">
            <a href="gallery.php" class="hover:underline">Gallery Albums</a>
            <span>/</span>
            <span class="text-gray-600 font-semibold"><?= htmlspecialchars($album['title']) ?></span>
          </div>
          <h1 class="text-2xl font-bold text-gray-800 font-poppins mt-2">Manage Photos</h1>
          <p class="text-xs text-gray-400 mt-1">Upload multiple photos, drag-and-drop to reorder, and update captions.</p>
        </div>
        <div>
          <a href="gallery.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded text-xs uppercase tracking-wider transition inline-flex items-center gap-1.5 shadow-sm">
            ← Back to Albums
          </a>
        </div>
      </div>

      <?php if($msg): ?><div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if($error): ?><div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Photo Upload Sidebar -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">Upload Photos</h2>
          
          <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Select Image Files *</label>
              <input type="file" name="images[]" multiple required accept="image/*"
                     class="text-xs w-full bg-gray-50 p-2.5 rounded border border-gray-200 cursor-pointer">
              <p class="text-[9px] text-gray-400 mt-1.5">You can select multiple images at once (JPEG, PNG, WEBP).</p>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition">
              🚀 Upload Selected
            </button>
          </form>
          
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 mt-4">
            <h4 class="text-[10px] font-bold text-gray-500 uppercase mb-1.5">Album Info</h4>
            <div class="space-y-2 text-xs">
              <div class="flex justify-between"><span class="text-gray-400">Total Photos:</span> <span class="font-bold text-gray-700"><?= count($photos) ?></span></div>
              <div class="flex justify-between"><span class="text-gray-400">Slug:</span> <code class="bg-white px-1 py-0.5 rounded border text-[10px]"><?= htmlspecialchars($album['slug']) ?></code></div>
            </div>
          </div>
        </div>

        <!-- Photos Ordering & Caption Editing -->
        <div class="lg:col-span-2 space-y-4">
          <form method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="save_changes" value="1">
            
            <div class="flex items-center justify-between border-b pb-2">
              <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider">
                Album Photos Grid (<?= count($photos) ?>)
              </h2>
              <?php if(!empty($photos)): ?>
              <button type="submit" id="save-changes-btn" class="bg-gray-400 hover:bg-blue-700 text-white font-bold py-1.5 px-4 rounded text-xs uppercase tracking-wider transition shadow-sm">
                💾 Save Changes
              </button>
              <?php endif; ?>
            </div>
            
            <?php if(empty($photos)): ?>
            <div class="bg-white rounded-xl border p-12 text-center text-xs text-gray-400 shadow-sm">
              <span class="text-3xl block mb-2">📸</span>
              No photos uploaded to this album yet.<br>Select images on the left to populate the album.
            </div>
            <?php else: ?>
            <p class="text-[10px] text-gray-400">💡 Tip: Drag and drop cards to reorder the photos. Click "Save Changes" to commit captions and sorting.</p>
            
            <div id="photo-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <?php foreach($photos as $p): ?>
              <div class="photo-item bg-white border border-gray-200 rounded-xl p-3 shadow-sm flex flex-col justify-between cursor-move hover:shadow-md transition duration-150 relative border-l-4 border-l-blue-400"
                   draggable="true">
                
                <input type="hidden" name="sort_orders[<?= $p['id'] ?>]" value="<?= $p['sort_order'] ?>" class="sort-order-input">
                
                <div class="space-y-3">
                  <!-- Image Thumbnail -->
                  <div class="h-32 rounded bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100 select-none">
                    <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover" draggable="false">
                  </div>
                  
                  <!-- Drag Handle & Caption Input -->
                  <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1">Caption</label>
                    <input type="text" name="captions[<?= $p['id'] ?>]" value="<?= htmlspecialchars($p['caption'] ?? '') ?>" placeholder="Enter photo caption..."
                           class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2 py-1 text-xs outline-none focus:bg-white transition">
                  </div>
                </div>
                
                <!-- Footer Action -->
                <div class="pt-3 border-t border-gray-100 flex justify-between items-center text-[10px] mt-3">
                  <span class="text-gray-400 font-semibold flex items-center gap-1 select-none">
                    <span>☰</span> Drag to sort
                  </span>
                  <a href="?album_id=<?= $album_id ?>&delete_image=<?= $p['id'] ?>" class="text-red-500 hover:text-red-700 font-bold uppercase transition"
                     onclick="return confirm('Delete this photo from the album?')">
                    🗑️ Delete
                  </a>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
      let dragSource = null;
      const container = document.getElementById('photo-grid');
      
      if (container) {
          const items = container.querySelectorAll('.photo-item');
          
          items.forEach(item => {
              item.addEventListener('dragstart', handleDragStart);
              item.addEventListener('dragover', handleDragOver);
              item.addEventListener('dragenter', handleDragEnter);
              item.addEventListener('dragleave', handleDragLeave);
              item.addEventListener('drop', handleDrop);
              item.addEventListener('dragend', handleDragEnd);
          });
      }
      
      function handleDragStart(e) {
          dragSource = this;
          e.dataTransfer.effectAllowed = 'move';
          this.classList.add('opacity-40', 'border-blue-500', 'shadow-lg');
      }
      
      function handleDragOver(e) {
          if (e.preventDefault) {
              e.preventDefault();
          }
          e.dataTransfer.dropEffect = 'move';
          return false;
      }
      
      function handleDragEnter(e) {
          this.classList.add('scale-[1.02]', 'bg-blue-50/50');
      }
      
      function handleDragLeave(e) {
          this.classList.remove('scale-[1.02]', 'bg-blue-50/50');
      }
      
      function handleDrop(e) {
          if (e.stopPropagation) {
              e.stopPropagation();
          }
          if (dragSource !== this) {
              const allItems = Array.from(container.querySelectorAll('.photo-item'));
              const srcIdx = allItems.indexOf(dragSource);
              const destIdx = allItems.indexOf(this);
              
              if (srcIdx < destIdx) {
                  container.insertBefore(dragSource, this.nextSibling);
              } else {
                  container.insertBefore(dragSource, this);
              }
              updateSortOrders();
          }
          return false;
      }
      
      function handleDragEnd(e) {
          const allItems = container.querySelectorAll('.photo-item');
          allItems.forEach(item => {
              item.classList.remove('opacity-40', 'border-blue-500', 'shadow-lg', 'scale-[1.02]', 'bg-blue-50/50');
          });
      }
      
      function updateSortOrders() {
          const allItems = container.querySelectorAll('.photo-item');
          allItems.forEach((item, index) => {
              const sortInput = item.querySelector('.sort-order-input');
              if (sortInput) {
                  sortInput.value = index + 1;
              }
          });
          
          const saveBtn = document.getElementById('save-changes-btn');
          if (saveBtn) {
              saveBtn.classList.remove('bg-gray-400');
              saveBtn.classList.add('bg-blue-600', 'animate-pulse');
          }
      }
  });
  </script>
<?php include '../includes/admin-footer.php'; ?>
