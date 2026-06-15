<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT logo_url FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if ($row) {
                $localPath = __DIR__ . '/../../' . ltrim($row['logo_url'], '/');
                if (file_exists($localPath)) {
                    @unlink($localPath);
                }
            }
            $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: clients.php?success=Client logo deleted successfully');
            exit;
        } catch(Exception $e) {
            $error = 'Failed to delete logo: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

// Handle Add/Upload logo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    
    if (empty($_FILES['logo']['name'])) {
        $error = 'Please select a logo file to upload.';
    } else {
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
        if (in_array($ext, $allowed)) {
            $fname = 'client_logo_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (!is_dir(UPLOAD_PATH)) {
                mkdir(UPLOAD_PATH, 0775, true);
            }
            if (move_uploaded_file($_FILES['logo']['tmp_name'], UPLOAD_PATH . $fname)) {
                $logo_url = '/assets/images/uploads/' . $fname;
                try {
                    $stmt = $pdo->prepare("INSERT INTO clients (logo_url, name) VALUES (?, ?)");
                    $stmt->execute([$logo_url, $name]);
                    header('Location: clients.php?success=Client logo added successfully');
                    exit;
                } catch(Exception $e) {
                    $error = 'Database save failed: ' . $e->getMessage();
                }
            } else {
                $error = 'Failed to save logo file.';
            }
        } else {
            $error = 'Invalid image type. Allowed: png, jpg, jpeg, svg, webp';
        }
    }
}

// Fetch clients
$clients = [];
if ($pdo) {
    try {
        $clients = $pdo->query("SELECT * FROM clients ORDER BY id DESC")->fetchAll();
    } catch(Exception $e) {
        $error = 'Failed to load clients: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Clients | Clevora Admin</title>
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
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Manage Clients & Partners</h1>
        <p class="text-xs text-gray-400">Upload or delete partner logos displayed on the clients grid section.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Add Form -->
        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2">Add Partner Logo</h2>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Client/Partner Name</label>
            <input type="text" name="name" placeholder="e.g. Partner Corporation"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Select Logo File *</label>
            <input type="file" name="logo" required class="text-xs w-full bg-gray-50 p-2.5 rounded border border-gray-200">
            <p class="text-[9px] text-gray-400 mt-1">Prefer transparent PNG logos. Allowed: png, jpg, jpeg, svg, webp</p>
          </div>
          <button type="submit"
                  class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition duration-300">
            Upload Logo
          </button>
        </form>

        <!-- Current Logos Grid -->
        <div class="lg:col-span-2 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-2">Current Client Logos</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <?php if(empty($clients)): ?>
            <div class="col-span-3 bg-white rounded-xl border p-8 text-center text-xs text-gray-400">No partner logos uploaded yet.</div>
            <?php else: ?>
              <?php foreach($clients as $c): ?>
              <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm flex flex-col justify-between items-center group h-36">
                <div class="h-20 flex items-center justify-center overflow-hidden w-full">
                  <img src="<?= htmlspecialchars($c['logo_url']) ?>" alt="" class="max-h-full max-w-full object-contain">
                </div>
                <div class="w-full pt-2 border-t border-gray-50 flex justify-between items-center text-[10px] mt-2">
                  <span class="text-gray-400 font-semibold line-clamp-1 max-w-[70px]"><?= htmlspecialchars($c['name'] ?: 'Partner') ?></span>
                  <a href="?delete=<?= $c['id'] ?>" class="text-red-500 hover:text-red-600 font-bold"
                     onclick="return confirm('Delete this logo?')">Delete</a>
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
