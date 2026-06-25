<?php
require_once '../middleware/auth.php';

$msg = '';
$error = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("DELETE FROM services WHERE id=?");
            $stmt->execute([$id]);
            header('Location: services.php?success=Service deleted successfully');
            exit;
        } catch(Exception $e) {
            $error = 'Failed to delete service: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

$services = [];
if ($pdo) {
    try {
        $services = $pdo->query("SELECT s.*, c.name AS category_name FROM services s LEFT JOIN service_categories c ON s.category_id = c.id ORDER BY s.sort_order")->fetchAll();
    } catch(Exception $e) {
        $error = 'Error fetching services: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Services | Clevora Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="flex bg-gray-50 min-h-screen font-sans">

  <!-- SIDEBAR -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-8 md:p-12">
    <div class="max-w-5xl mx-auto space-y-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 font-poppins">Manage Services</h1>
          <p class="text-xs text-gray-400">Add, edit, or remove services from the homepage and sidebar.</p>
        </div>
        <a href="add-service.php" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg text-xs tracking-wider uppercase transition">
          + Add New Service
        </a>
      </div>

      <?php if(!empty($msg)): ?>
      <p class="bg-green-50 text-green-700 text-xs px-3 py-2 rounded-lg border border-green-100"><?= htmlspecialchars($msg) ?></p>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <p class="bg-red-50 text-red-700 text-xs px-3 py-2 rounded-lg border border-red-100"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm border-collapse text-left text-gray-600">
          <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider border-b border-gray-100">
            <tr>
              <th class="px-6 py-4">Sort</th>
              <th class="px-6 py-4">Service Name</th>
              <th class="px-6 py-4">Category</th>
              <th class="px-6 py-4">Slug</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <?php if(empty($services)): ?>
            <tr>
              <td colspan="6" class="px-6 py-8 text-center text-xs text-gray-400">No services found in database.</td>
            </tr>
            <?php else: ?>
              <?php foreach($services as $s): ?>
              <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-xs font-semibold text-gray-400">#<?= htmlspecialchars($s['sort_order']) ?></td>
                <td class="px-6 py-4 font-semibold text-gray-800 text-xs uppercase"><?= htmlspecialchars($s['name']) ?></td>
                <td class="px-6 py-4 text-xs text-gray-500 uppercase font-semibold"><?= htmlspecialchars($s['category_name'] ?? 'Unassigned') ?></td>
                <td class="px-6 py-4 text-xs text-gray-400 font-mono"><?= htmlspecialchars($s['slug']) ?></td>
                <td class="px-6 py-4">
                  <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full <?= $s['is_active'] ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-500 border border-gray-200' ?>">
                    <?= $s['is_active'] ? 'Active' : 'Hidden' ?>
                  </span>
                </td>
                <td class="px-6 py-4 text-right flex justify-end gap-3 text-xs">
                  <a href="add-service.php?id=<?= $s['id'] ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Edit</a>
                  <a href="?delete=<?= $s['id'] ?>" class="text-red-500 hover:text-red-600 font-semibold"
                     onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>
</html>
