<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Manage Gallery Albums | Clevora Admin';
$msg = ''; $error = '';
if (isset($_GET['success'])) $msg = $_GET['success'];

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Fetch cover image and all images in the album to delete from disk
        $stmtAlbum = $pdo->prepare("SELECT cover_image FROM gallery_albums WHERE id=?");
        $stmtAlbum->execute([$id]);
        $album = $stmtAlbum->fetch();
        
        $stmtImages = $pdo->prepare("SELECT image FROM gallery_images WHERE album_id=?");
        $images = [];
        if ($stmtImages) {
            $stmtImages->execute([$id]);
            $images = $stmtImages->fetchAll();
        }
        
        // Delete cover image
        if ($album && !empty($album['cover_image'])) {
            $p = __DIR__ . '/../../' . ltrim($album['cover_image'], '/');
            if (file_exists($p)) @unlink($p);
        }
        
        // Delete all images in album
        foreach ($images as $img) {
            if (!empty($img['image'])) {
                $p = __DIR__ . '/../../' . ltrim($img['image'], '/');
                if (file_exists($p)) @unlink($p);
            }
        }
        
        // Delete album (DB cascade deletes images records)
        $pdo->prepare("DELETE FROM gallery_albums WHERE id=?")->execute([$id]);
        header('Location: gallery.php?success=Album and its photos deleted.'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $id = (int)($_POST['album_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Auto-generate slug if empty
    if (empty($slug)) {
        // Replace non-letters/digits by -
        $slug = preg_replace('~[^\pL\d]+~u', '-', $title);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('~-+~', '-', $slug);
        $slug = strtolower($slug);
        if (empty($slug)) {
            $slug = 'album-' . rand(100, 999);
        }
    }
    
    // Validate fields
    if (empty($title)) {
        $error = 'Album title is required.';
    } else {
        // Check slug uniqueness
        $check = $pdo->prepare("SELECT COUNT(*) FROM gallery_albums WHERE slug = ? AND id != ?");
        $check->execute([$slug, $id]);
        if ($check->fetchColumn() > 0) {
            // Slug exists, append random suffix to make it unique
            $slug .= '-' . rand(10, 99);
        }
        
        $cover_image = $_POST['existing_cover'] ?? '';
        if (!empty($_FILES['cover_image']['name'])) {
            $v = validate_upload($_FILES['cover_image']); 
            if ($v === true) {
                $url = save_upload($_FILES['cover_image'], 'gallery_cover');
                if ($url) {
                    $cover_image = $url;
                    // Delete old cover if replaced
                    $old_cover = $_POST['existing_cover'] ?? '';
                    if (!empty($old_cover)) {
                        $p = __DIR__ . '/../../' . ltrim($old_cover, '/');
                        if (file_exists($p)) @unlink($p);
                    }
                }
            } else {
                $error = $v;
            }
        }
    }
    
    if (empty($error)) {
        try {
            if ($id > 0) {
                $pdo->prepare("UPDATE gallery_albums SET title=?, slug=?, description=?, cover_image=?, sort_order=?, is_active=?, updated_at=NOW() WHERE id=?")
                    ->execute([$title, $slug, $description, $cover_image, $sort_order, $is_active, $id]);
                header('Location: gallery.php?success=Album updated.'); exit;
            } else {
                $pdo->prepare("INSERT INTO gallery_albums (title, slug, description, cover_image, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW())")
                    ->execute([$title, $slug, $description, $cover_image, $sort_order, $is_active]);
                header('Location: gallery.php?success=Album created.'); exit;
            }
        } catch (Exception $e) { $error = $e->getMessage(); }
    }
}

$albums = [];
if ($pdo) {
    $albums = $pdo->query("
        SELECT a.*, COUNT(i.id) as photo_count 
        FROM gallery_albums a 
        LEFT JOIN gallery_images i ON a.id = i.album_id 
        GROUP BY a.id 
        ORDER BY a.sort_order ASC, a.id DESC
    ")->fetchAll();
}

$edit_album = null;
if (isset($_GET['edit']) && $pdo) {
    $s = $pdo->prepare("SELECT * FROM gallery_albums WHERE id=?");
    $s->execute([(int)$_GET['edit']]);
    $edit_album = $s->fetch();
}
?>
<?php include '../includes/admin-header.php'; ?>
  <?php include '../includes/sidebar.php'; ?>
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-6xl mx-auto space-y-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Manage Gallery Albums</h1>
        <p class="text-xs text-gray-400 mt-1">Refactor single-image gallery sections into organized albums containing multiple photos.</p>
      </div>
      <?php if($msg): ?><div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if($error): ?><div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Add/Edit Form -->
        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <?= csrf_field() ?>
          <input type="hidden" name="album_id" value="<?= $edit_album['id'] ?? 0 ?>">
          <input type="hidden" name="existing_cover" value="<?= htmlspecialchars($edit_album['cover_image'] ?? '') ?>">
          
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">
            <?= $edit_album ? 'Edit Album' : 'Create Album' ?>
          </h2>
          
          <?php if($edit_album && $edit_album['cover_image']): ?>
          <div class="h-24 bg-gray-50 rounded-lg border flex items-center justify-center overflow-hidden">
            <img src="<?= htmlspecialchars($edit_album['cover_image']) ?>" class="max-h-full max-w-full object-cover">
          </div>
          <?php endif; ?>
          
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Album Name *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($edit_album['title'] ?? '') ?>" placeholder="e.g. Server Room Infrastructure" required
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition"
                   oninput="syncSlug(this)">
          </div>
          
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Slug (URL friendly) *</label>
            <input type="text" name="slug" value="<?= htmlspecialchars($edit_album['slug'] ?? '') ?>" placeholder="e.g. server-room-infrastructure" required
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition"
                   id="album-slug" oninput="this.dataset.manual = 'true'">
          </div>
          
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Cover Image <?= $edit_album ? '' : '*' ?></label>
            <input type="file" name="cover_image" <?= $edit_album ? '' : 'required' ?> class="text-xs w-full bg-gray-50 p-2.5 rounded border border-gray-200">
            <p class="text-[9px] text-gray-400 mt-1">Recommended: 16:9 ratio landscape image</p>
          </div>
          
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Description</label>
            <textarea name="description" rows="3" placeholder="Brief details about this album..."
                      class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition"><?= htmlspecialchars($edit_album['description'] ?? '') ?></textarea>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Sort Order</label>
              <input type="number" name="sort_order" value="<?= $edit_album['sort_order'] ?? 0 ?>"
                     class="w-full bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
            </div>
            <div class="flex items-end pb-2">
              <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?= (!$edit_album||($edit_album['is_active']??1))?'checked':'' ?> class="rounded text-blue-600"> Active
              </label>
            </div>
          </div>
          
          <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition">
              <?= $edit_album ? 'Update Album' : 'Create Album' ?>
            </button>
            <?php if($edit_album): ?>
            <a href="gallery.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-3 rounded text-xs uppercase transition">Cancel</a>
            <?php endif; ?>
          </div>
        </form>

        <!-- Albums List -->
        <div class="lg:col-span-2 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-2">Albums List (<?= count($albums) ?>)</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php if(empty($albums)): ?>
            <div class="sm:col-span-2 bg-white rounded-xl border p-8 text-center text-xs text-gray-400">No albums created yet.</div>
            <?php else: foreach($albums as $alb): ?>
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm flex flex-col justify-between <?= ($alb['is_active']??1)?'':'opacity-60' ?>">
              <div class="space-y-3">
                <div class="h-32 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center border border-gray-100 relative">
                  <?php if($alb['cover_image']): ?>
                  <img src="<?= htmlspecialchars($alb['cover_image']) ?>" class="w-full h-full object-cover">
                  <?php else: ?>
                  <span class="text-xs text-gray-300">No Cover</span>
                  <?php endif; ?>
                  <span class="absolute top-2 right-2 bg-black/60 text-white px-2 py-0.5 rounded text-[10px] font-semibold">
                    <?= $alb['photo_count'] ?> <?= $alb['photo_count'] === 1 ? 'Photo' : 'Photos' ?>
                  </span>
                </div>
                <div>
                  <h3 class="text-xs font-bold text-gray-800 line-clamp-1"><?= htmlspecialchars($alb['title']) ?></h3>
                  <p class="text-[10px] text-gray-400 line-clamp-2 mt-1"><?= htmlspecialchars($alb['description'] ?: '(No description)') ?></p>
                  <p class="text-[9px] text-gray-400 font-semibold mt-1">Slug: /<?= htmlspecialchars($alb['slug']) ?> · Order: <?= $alb['sort_order'] ?></p>
                </div>
              </div>
              <div class="pt-3 border-t border-gray-50 flex justify-between items-center text-[11px] mt-4">
                <div class="flex gap-3">
                  <a href="?edit=<?= $alb['id'] ?>" class="text-blue-500 hover:underline font-semibold">Edit Info</a>
                  <a href="manage-photos.php?album_id=<?= $alb['id'] ?>" class="text-indigo-600 hover:underline font-bold flex items-center gap-0.5">
                    📷 Manage Photos
                  </a>
                </div>
                <a href="?delete=<?= $alb['id'] ?>" class="text-red-500 font-semibold hover:underline" onclick="return confirm('Are you sure you want to delete this album and all its pictures? This action cannot be undone.')">Delete</a>
              </div>
            </div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
  function syncSlug(titleInput) {
      const slugInput = document.getElementById('album-slug');
      if (slugInput && slugInput.dataset.manual !== 'true') {
          slugInput.value = titleInput.value
              .toLowerCase()
              .replace(/[^a-z0-9]+/g, '-')
              .replace(/(^-|-$)/g, '');
      }
  }
  </script>
<?php include '../includes/admin-footer.php'; ?>
