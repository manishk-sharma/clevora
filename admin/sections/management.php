<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

$keys = ['management_founder_name', 'management_founder_role', 'management_founder_bio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pdo) {
        try {
            $pdo->beginTransaction();
            // Update text fields
            foreach ($keys as $k) {
                $v = trim($_POST[$k] ?? '');
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$k, $v, $v]);
            }

            // Handle image upload
            if (!empty($_FILES['founder_photo']['name'])) {
                $ext = strtolower(pathinfo($_FILES['founder_photo']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $destPath = __DIR__ . '/../../assets/images/founder.jpg';
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0775, true);
                    }
                    if (!move_uploaded_file($_FILES['founder_photo']['tmp_name'], $destPath)) {
                        $error = 'Failed to save uploaded photo file.';
                    }
                } else {
                    $error = 'Invalid image format. Allowed: png, jpg, jpeg, webp';
                }
            }

            if (empty($error)) {
                $pdo->commit();
                $msg = 'Management details updated successfully.';
            } else {
                $pdo->rollBack();
            }
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = 'Database update failed: ' . $e->getMessage();
        }
    }
}

// Fetch current values
$settings = [];
foreach ($keys as $k) {
    $settings[$k] = setting($k, $pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Leadership Page | Clevora Admin</title>
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
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Edit Our Management</h1>
        <p class="text-xs text-gray-400">Modify Clevora's leadership profile page details, biographies, and photos.</p>
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
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Founder Name</label>
            <input type="text" name="management_founder_name" required value="<?= htmlspecialchars($settings['management_founder_name']) ?>"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Founder Designation / Role</label>
            <input type="text" name="management_founder_role" required value="<?= htmlspecialchars($settings['management_founder_role']) ?>"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Founder Biography & Message</label>
          <textarea name="management_founder_bio" rows="6" required placeholder="Describe founder biography..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($settings['management_founder_bio']) ?></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Founder Photo</label>
          <input type="file" name="founder_photo" class="text-xs w-full bg-gray-50 p-2.5 rounded border border-gray-200">
          <p class="text-[10px] text-gray-400 mt-1">Recommended size: 400x400 square. Will overwrite assets/images/founder.jpg.</p>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          Save Management Details
        </button>
      </form>
    </div>
  </main>

</body>
</html>
