<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Media Library | Clevora Admin';
$msg = '';
$error = '';

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_files']) && verify_csrf()) {
    $files = $_FILES['media_files'];
    $success_count = 0;
    
    // Support uploading multiple files
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['name'][$i] === '') continue;
        
        $single_file = [
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        ];
        
        $v = validate_upload($single_file, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'pdf', 'docx', 'xlsx', 'txt'], 10);
        if ($v === true) {
            $url = save_upload($single_file, 'media');
            if ($url) {
                $success_count++;
            } else {
                $error .= "Failed to save: " . htmlspecialchars($single_file['name']) . ". ";
            }
        } else {
            $error .= htmlspecialchars($single_file['name']) . ": " . $v . ". ";
        }
    }
    
    if ($success_count > 0) {
        $msg = "$success_count file(s) uploaded successfully.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT filename FROM media_library WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row) {
            $p = __DIR__ . '/../../assets/images/uploads/' . $row['filename'];
            if (file_exists($p)) {
                @unlink($p);
            }
            
            $pdo->prepare("DELETE FROM media_library WHERE id = ?")->execute([$id]);
            header('Location: media.php?success=File deleted successfully.');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Failed to delete file: ' . $e->getMessage();
    }
}

// ── Search & Pagination ──────────────────────────────────
$search = trim($_GET['search'] ?? '');
$query = "SELECT * FROM media_library WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (original_name LIKE ? OR filename LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
}

$query .= " ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$media = $stmt->fetchAll();
?>
<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-6xl mx-auto space-y-6">
      
      <!-- HEADER -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 font-poppins">Media Library</h1>
          <p class="text-xs text-gray-400 mt-1">Upload and manage images, PDFs and business resources for editor uploads.</p>
        </div>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- LEFT: UPLOADER -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-2 border-b pb-2">Upload Files</h2>
          <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            
            <div class="border-2 border-dashed border-gray-200 hover:border-blue-500 rounded-xl p-8 text-center bg-gray-50/50 transition cursor-pointer relative group">
              <input type="file" name="media_files[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
              <div class="text-4xl mb-2 text-gray-300 group-hover:text-blue-500 transition">📂</div>
              <span class="block text-xs font-bold text-gray-600">Select Files to Upload</span>
              <span class="block text-[9px] text-gray-400 mt-1">Images (PNG, JPG, SVG, WebP) or docs up to 10MB</span>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded-lg text-xs uppercase tracking-wider transition">
              Upload Selected Files
            </button>
          </form>
        </div>

        <!-- RIGHT: LIBRARY GRID -->
        <div class="lg:col-span-2 space-y-4">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <h2 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Browse Media (<?= count($media) ?>)</h2>
            <form method="GET" class="flex gap-2 w-full sm:w-60">
              <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search files..."
                     class="flex-1 bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-1.5 text-xs outline-none focus:bg-white transition-all">
              <button type="submit" class="bg-gray-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-gray-700 transition">Search</button>
            </form>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <?php if (empty($media)): ?>
              <div class="col-span-3 bg-white rounded-xl border p-12 text-center text-xs text-gray-400">
                No files found in the media library.
              </div>
            <?php else: foreach ($media as $item): ?>
              <?php
                $is_img = in_array(strtolower(pathinfo($item['filename'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                $url = SITE_URL . $item['url'];
              ?>
              <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between group relative h-48">
                <!-- Preview area -->
                <div class="h-28 flex items-center justify-center bg-gray-50 overflow-hidden border-b border-gray-100 p-2 relative">
                  <?php if ($is_img): ?>
                    <img src="<?= htmlspecialchars($item['url']) ?>" class="max-h-full max-w-full object-contain transition-transform group-hover:scale-105 duration-300">
                  <?php else: ?>
                    <div class="text-3xl text-gray-400 font-bold uppercase font-mono">
                      <?= htmlspecialchars(pathinfo($item['filename'], PATHINFO_EXTENSION)) ?>
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Footer description & Actions -->
                <div class="p-3 bg-white space-y-1 flex-1 flex flex-col justify-between">
                  <div class="space-y-0.5">
                    <p class="text-[10px] font-bold text-gray-700 truncate" title="<?= htmlspecialchars($item['original_name']) ?>">
                      <?= htmlspecialchars($item['original_name']) ?>
                    </p>
                    <p class="text-[8px] text-gray-400 font-semibold font-mono">
                      <?= round($item['size'] / 1024, 1) ?> KB
                    </p>
                  </div>

                  <div class="flex gap-2 justify-between items-center text-[9px] pt-1.5 border-t border-gray-50">
                    <button type="button" onclick="copyToClipboard('<?= addslashes($url) ?>', this)" 
                            class="text-blue-600 font-bold uppercase tracking-wider hover:text-blue-500 flex items-center gap-1 transition">
                      Copy Link
                    </button>
                    <a href="?delete=<?= $item['id'] ?>" class="text-red-500 font-bold uppercase tracking-wider hover:text-red-600 transition"
                       data-confirm="Are you sure you want to delete this media file permanently? Any content referencing it might display broken links.">
                      Delete
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>

      </div>

    </div>
  </main>

  <script>
    function copyToClipboard(text, btn) {
      navigator.clipboard.writeText(text).then(() => {
        const originalText = btn.innerText;
        btn.innerText = 'Copied!';
        btn.style.color = '#10B981'; // Emerald color
        setTimeout(() => {
          btn.innerText = originalText;
          btn.style.color = '';
        }, 1500);
      }).catch(err => {
        alert('Could not copy link: ' + err);
      });
    }
  </script>

<?php include '../includes/admin-footer.php'; ?>
