<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';
$currentContent = '';

// Load current content
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT full_content FROM services WHERE slug = 'content-moderation'");
        $stmt->execute();
        $dbContent = $stmt->fetchColumn();
        $default_content = "Clevora takes its technological capabilities very seriously and has acquired all the technologies that are required for running a successful call center in this competitive market segment.\n\nOur Content Moderation services ensure your website or application remains safe, clean, and welcoming for all users. We monitor and filter user-generated text, image, and video content 24 hours a day, 7 days a week. We combine automated systems with experienced human moderators to check compliance, block toxic or illegal materials, and shield your brand from unwanted exposure.";
        $currentContent = $dbContent !== false && $dbContent !== '' ? $dbContent : $default_content;
    } catch(Exception $e) {
        $error = 'Failed to fetch content moderation data: ' . $e->getMessage();
    }
}

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE services SET full_content = ? WHERE slug = 'content-moderation'");
            $stmt->execute([$content]);
            $msg = 'Content moderation deep-dive content updated successfully.';
            $currentContent = $content;
        } catch(Exception $e) {
            $error = 'Database update failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Content Moderation Page | Clevora Admin</title>
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
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Edit Content Moderation Page</h1>
        <p class="text-xs text-gray-400">Edit the detailed, long-form information text shown on the /detail-services.php?slug=content-moderation deep-dive page.</p>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Detailed Content (detail-services.php?slug=content-moderation)</label>
          <textarea name="content" rows="12" placeholder="Write full page HTML or body text..."
                    class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2.5 text-xs outline-none focus:bg-white transition-all"><?= htmlspecialchars($currentContent) ?></textarea>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider transition-all duration-300">
          Save Deep-Dive Content
        </button>
      </form>
    </div>
  </main>

</body>
</html>
