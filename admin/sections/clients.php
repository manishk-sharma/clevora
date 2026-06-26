<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Manage Clients | Clevora Admin';
$msg = ''; $error = '';
if (isset($_GET['success'])) $msg = $_GET['success'];

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT logo_url FROM clients WHERE id = ?"); $stmt->execute([$id]); $row = $stmt->fetch();
        if ($row) { $p = __DIR__ . '/../../' . ltrim($row['logo_url'], '/'); if (file_exists($p)) @unlink($p); }
        $pdo->prepare("DELETE FROM clients WHERE id = ?")->execute([$id]);
        header('Location: clients.php?success=Client deleted.'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $id = (int)($_POST['client_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    
    $logo_url = $_POST['existing_logo'] ?? '';
    if (!empty($_FILES['logo']['name'])) {
        $v = validate_upload($_FILES['logo']);
        if ($v === true) {
            $url = save_upload($_FILES['logo'], 'client');
            if ($url) $logo_url = $url;
        } else { $error = $v; }
    }
    
    if (empty($error)) {
        try {
            if ($id > 0) {
                $pdo->prepare("UPDATE clients SET name=?, logo_url=?, website=?, sort_order=?, status=? WHERE id=?")->execute([$name, $logo_url, $website, $sort, $status, $id]);
                header('Location: clients.php?success=Client updated.'); exit;
            } else {
                if (empty($logo_url)) { $error = 'Please upload a logo.'; }
                else {
                    $pdo->prepare("INSERT INTO clients (name, logo_url, website, sort_order, status) VALUES (?,?,?,?,?)")->execute([$name, $logo_url, $website, $sort, $status]);
                    header('Location: clients.php?success=Client added.'); exit;
                }
            }
        } catch (Exception $e) { $error = $e->getMessage(); }
    }
}

$clients = $pdo ? $pdo->query("SELECT * FROM clients ORDER BY sort_order ASC, id DESC")->fetchAll() : [];
$edit_client = null;
if (isset($_GET['edit']) && $pdo) { $s = $pdo->prepare("SELECT * FROM clients WHERE id=?"); $s->execute([(int)$_GET['edit']]); $edit_client = $s->fetch(); }
?>
<?php include '../includes/admin-header.php'; ?>
  <?php include '../includes/sidebar.php'; ?>
  <main class="flex-1 p-6 md:p-10 overflow-auto">
    <div class="max-w-5xl mx-auto space-y-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">Manage Clients & Partners</h1>
        <p class="text-xs text-gray-400 mt-1">Upload, edit or delete partner logos displayed on the clients grid.</p>
      </div>
      <?php if($msg): ?><div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if($error): ?><div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
          <?= csrf_field() ?>
          <input type="hidden" name="client_id" value="<?= $edit_client['id'] ?? 0 ?>">
          <input type="hidden" name="existing_logo" value="<?= htmlspecialchars($edit_client['logo_url'] ?? '') ?>">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-2 border-b pb-2"><?= $edit_client ? 'Edit Client' : 'Add Partner Logo' ?></h2>
          <?php if($edit_client && $edit_client['logo_url']): ?>
          <div class="h-16 flex items-center justify-center bg-gray-50 rounded-lg border p-2"><img src="<?= htmlspecialchars($edit_client['logo_url']) ?>" class="max-h-full max-w-full object-contain"></div>
          <?php endif; ?>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Client/Partner Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_client['name'] ?? '') ?>" placeholder="e.g. Partner Corporation"
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Website URL</label>
            <input type="url" name="website" value="<?= htmlspecialchars($edit_client['website'] ?? '') ?>" placeholder="https://..."
                   class="w-full bg-gray-50 border border-gray-200 focus:border-blue-500 rounded px-2.5 py-2 text-xs outline-none focus:bg-white transition font-mono">
          </div>
          <div>
            <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5"><?= $edit_client ? 'Replace Logo' : 'Logo File *' ?></label>
            <input type="file" name="logo" <?= $edit_client ? '' : 'required' ?> class="text-xs w-full bg-gray-50 p-2.5 rounded border border-gray-200">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1.5">Sort Order</label>
              <input type="number" name="sort_order" value="<?= $edit_client['sort_order'] ?? 0 ?>"
                     class="w-full bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-xs outline-none">
            </div>
            <div class="flex items-end pb-1">
              <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                <input type="checkbox" name="status" value="1" <?= (!$edit_client||($edit_client['status']??1))?'checked':'' ?> class="rounded text-blue-600"> Active
              </label>
            </div>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded text-xs uppercase tracking-wider transition"><?= $edit_client ? 'Update Client' : 'Upload Logo' ?></button>
            <?php if($edit_client): ?><a href="clients.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-3 rounded text-xs uppercase transition">Cancel</a><?php endif; ?>
          </div>
        </form>

        <div class="lg:col-span-2 space-y-4">
          <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b pb-2">Current Client Logos (<?= count($clients) ?>)</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <?php if(empty($clients)): ?>
            <div class="col-span-3 bg-white rounded-xl border p-8 text-center text-xs text-gray-400">No partner logos uploaded yet.</div>
            <?php else: foreach($clients as $c): ?>
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm flex flex-col justify-between items-center group h-36 <?= ($c['status']??1)?'':'opacity-50' ?>">
              <div class="h-20 flex items-center justify-center overflow-hidden w-full">
                <img src="<?= htmlspecialchars($c['logo_url']) ?>" alt="" class="max-h-full max-w-full object-contain">
              </div>
              <div class="w-full pt-2 border-t border-gray-50 flex justify-between items-center text-[10px] mt-2">
                <span class="text-gray-400 font-semibold line-clamp-1 max-w-[80px]"><?= htmlspecialchars($c['name'] ?: 'Partner') ?></span>
                <div class="flex gap-2">
                  <a href="?edit=<?= $c['id'] ?>" class="text-blue-500 font-bold">Edit</a>
                  <a href="?delete=<?= $c['id'] ?>" class="text-red-500 font-bold" onclick="return confirm('Delete?')">Delete</a>
                </div>
              </div>
            </div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>
<?php include '../includes/admin-footer.php'; ?>
