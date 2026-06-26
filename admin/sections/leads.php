<?php
require_once '../middleware/auth.php';
$adminPageTitle = 'Manage Leads | Clevora Admin';
$msg = '';
$error = '';

if (isset($_GET['success'])) {
    $msg = $_GET['success'];
}

// ── Handle CSV Export ───────────────────────────────────
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Clear any output
    if (ob_get_level()) ob_end_clean();
    
    // Set headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=clevora_leads_' . date('Y-m-d') . '.csv');
    
    // Fetch filtered list of leads
    $status_filter = $_GET['status'] ?? '';
    $search = trim($_GET['search'] ?? '');
    
    $query = "SELECT * FROM leads WHERE 1=1";
    $params = [];
    
    if ($status_filter !== '') {
        $query .= " AND status = ?";
        $params[] = $status_filter;
    }
    if ($search !== '') {
        $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR message LIKE ?)";
        $search_term = "%$search%";
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
    }
    
    $query .= " ORDER BY id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $leads_export = $stmt->fetchAll();
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Date', 'Name', 'Email', 'Phone', 'Interest', 'Message', 'Status']);
    
    foreach ($leads_export as $l) {
        fputcsv($output, [
            $l['id'],
            $l['created_at'],
            $l['name'],
            $l['email'],
            $l['phone'],
            $l['interest'],
            $l['message'],
            $l['status']
        ]);
    }
    fclose($output);
    exit;
}

// ── Handle Actions ──────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_status') {
        $id = (int)($_POST['lead_id'] ?? 0);
        $new_status = $_POST['status'] ?? '';
        $allowed_statuses = ['new', 'read', 'contacted', 'closed'];
        
        if ($id && in_array($new_status, $allowed_statuses)) {
            try {
                $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
                $stmt->execute([$new_status, $id]);
                header('Location: leads.php?success=Lead status updated successfully.');
                exit;
            } catch (Exception $e) {
                $error = 'Failed to update status: ' . $e->getMessage();
            }
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: leads.php?success=Lead deleted successfully.');
        exit;
    } catch (Exception $e) {
        $error = 'Failed to delete lead: ' . $e->getMessage();
    }
}

// ── Fetch Leads with Filters ────────────────────────────
$status_filter = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');

$query = "SELECT * FROM leads WHERE 1=1";
$params = [];

if ($status_filter !== '') {
    $query .= " AND status = ?";
    $params[] = $status_filter;
}
if ($search !== '') {
    $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR message LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

$query .= " ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$leads = $stmt->fetchAll();

// Count stats
$total_count = 0;
$new_count = 0;
$read_count = 0;
$contacted_count = 0;
$closed_count = 0;

if ($pdo) {
    $total_count = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
    $new_count = $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn();
    $read_count = $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'read'")->fetchColumn();
    $contacted_count = $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'contacted'")->fetchColumn();
    $closed_count = $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'closed'")->fetchColumn();
}
?>
<?php include '../includes/admin-header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="flex-1 p-6 md:p-10 overflow-auto" x-data="{ activeLead: null }">
    <div class="max-w-6xl mx-auto space-y-6">
      
      <!-- HEADER -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 font-poppins">Leads & Inquiries</h1>
          <p class="text-xs text-gray-400 mt-1">Review contact form submissions and update their progress.</p>
        </div>
        <a href="?export=csv&status=<?= urlencode($status_filter) ?>&search=<?= urlencode($search) ?>" class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-4 py-2.5 rounded-lg text-[10px] tracking-wider uppercase transition">
          Export Filtered to CSV
        </a>
      </div>

      <?php if(!empty($msg)): ?>
      <div class="toast bg-green-50 text-green-700 text-xs px-4 py-3 rounded-lg border border-green-100 font-semibold"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
      <div class="toast bg-red-50 text-red-700 text-xs px-4 py-3 rounded-lg border border-red-100 font-semibold"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- STAT CARDS -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-3">
          <div class="text-2xl">📨</div>
          <div>
            <div class="text-xs text-gray-400 font-semibold">Total</div>
            <div class="text-lg font-bold text-gray-800"><?= $total_count ?></div>
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-3">
          <div class="text-2xl">✨</div>
          <div>
            <div class="text-xs text-gray-400 font-semibold">New</div>
            <div class="text-lg font-bold text-blue-600"><?= $new_count ?></div>
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-3">
          <div class="text-2xl">👀</div>
          <div>
            <div class="text-xs text-gray-400 font-semibold">Read</div>
            <div class="text-lg font-bold text-purple-600"><?= $read_count ?></div>
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-3">
          <div class="text-2xl">📞</div>
          <div>
            <div class="text-xs text-gray-400 font-semibold">Contacted</div>
            <div class="text-lg font-bold text-yellow-600"><?= $contacted_count ?></div>
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-3">
          <div class="text-2xl">✅</div>
          <div>
            <div class="text-xs text-gray-400 font-semibold">Closed</div>
            <div class="text-lg font-bold text-green-600"><?= $closed_count ?></div>
          </div>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
          <div class="flex flex-wrap gap-3 items-center w-full md:w-auto">
            <span class="text-xs font-semibold text-gray-500 uppercase">Filter Status:</span>
            <div class="flex rounded-lg border border-gray-200 overflow-hidden text-xs">
              <a href="?status=&search=<?= urlencode($search) ?>" class="px-3 py-1.5 <?= $status_filter === '' ? 'bg-blue-600 text-white font-bold' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' ?>">All</a>
              <a href="?status=new&search=<?= urlencode($search) ?>" class="px-3 py-1.5 <?= $status_filter === 'new' ? 'bg-blue-600 text-white font-bold' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' ?>">New</a>
              <a href="?status=read&search=<?= urlencode($search) ?>" class="px-3 py-1.5 <?= $status_filter === 'read' ? 'bg-blue-600 text-white font-bold' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' ?>">Read</a>
              <a href="?status=contacted&search=<?= urlencode($search) ?>" class="px-3 py-1.5 <?= $status_filter === 'contacted' ? 'bg-blue-600 text-white font-bold' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' ?>">Contacted</a>
              <a href="?status=closed&search=<?= urlencode($search) ?>" class="px-3 py-1.5 <?= $status_filter === 'closed' ? 'bg-blue-600 text-white font-bold' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' ?>">Closed</a>
            </div>
          </div>
          
          <div class="flex gap-2 w-full md:w-80">
            <input type="hidden" name="status" value="<?= htmlspecialchars($status_filter) ?>">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name, email, message..."
                   class="flex-1 bg-gray-50 border border-gray-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs outline-none focus:bg-white transition-all">
            <button type="submit" class="bg-gray-800 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-gray-700 transition">
              Search
            </button>
          </div>
        </form>
      </div>

      <!-- LEADS TABLE -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm border-collapse text-left text-gray-600">
          <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider border-b border-gray-100">
            <tr>
              <th class="px-6 py-4">Date</th>
              <th class="px-6 py-4">Name</th>
              <th class="px-6 py-4">Contact Detail</th>
              <th class="px-6 py-4">Interest</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <?php if(empty($leads)): ?>
            <tr>
              <td colspan="6" class="px-6 py-8 text-center text-xs text-gray-400">No leads match the specified criteria.</td>
            </tr>
            <?php else: ?>
              <?php foreach($leads as $l): ?>
              <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-xs font-medium text-gray-400 whitespace-nowrap"><?= date('M d, Y h:i A', strtotime($l['created_at'])) ?></td>
                <td class="px-6 py-4 font-semibold text-gray-800 text-xs uppercase"><?= htmlspecialchars($l['name']) ?></td>
                <td class="px-6 py-4 text-xs space-y-0.5">
                  <div class="font-medium text-gray-700 font-mono"><?= htmlspecialchars($l['email']) ?></div>
                  <?php if($l['phone']): ?><div class="text-[10px] text-gray-400 font-semibold">📞 <?= htmlspecialchars($l['phone']) ?></div><?php endif; ?>
                </td>
                <td class="px-6 py-4">
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100">
                    <?= htmlspecialchars($l['interest'] ?: 'General Inquiry') ?>
                  </span>
                </td>
                <td class="px-6 py-4">
                  <?php
                    $colors = [
                      'new' => 'bg-blue-50 text-blue-700 border border-blue-100',
                      'read' => 'bg-purple-50 text-purple-700 border border-purple-100',
                      'contacted' => 'bg-yellow-50 text-yellow-700 border border-yellow-100',
                      'closed' => 'bg-green-50 text-green-700 border border-green-100'
                    ];
                    $badge = $colors[$l['status']] ?? 'bg-gray-100 text-gray-500 border';
                  ?>
                  <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full <?= $badge ?>">
                    <?= htmlspecialchars($l['status']) ?>
                  </span>
                </td>
                <td class="px-6 py-4 text-right flex justify-end gap-3 text-xs items-center">
                  <button type="button" @click="activeLead = <?= htmlspecialchars(json_encode($l)) ?>; if (activeLead.status === 'new') { activeLead.status = 'read'; }" class="text-blue-600 hover:text-blue-700 font-semibold">View Detail</button>
                  <a href="?delete=<?= $l['id'] ?>" class="text-red-500 hover:text-red-600 font-semibold" data-confirm="Are you sure you want to delete this lead log permanently?">Delete</a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- VIEW MODAL (Alpine.js powered) -->
      <template x-if="activeLead">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
          <div class="bg-white rounded-xl shadow-xl border border-gray-100 max-w-lg w-full overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="p-5 border-b flex justify-between items-center bg-gray-50">
              <div>
                <h3 class="font-bold text-gray-800 text-sm">Lead Details</h3>
                <span class="text-[10px] text-gray-400 font-medium" x-text="activeLead.created_at"></span>
              </div>
              <button type="button" @click="activeLead = null" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-4 text-xs overflow-auto max-h-[400px]">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <span class="block text-[10px] font-bold text-gray-400 uppercase">Sender Name</span>
                  <span class="font-semibold text-gray-800 text-sm" x-text="activeLead.name"></span>
                </div>
                <div>
                  <span class="block text-[10px] font-bold text-gray-400 uppercase">Service Interest</span>
                  <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100 mt-1" x-text="activeLead.interest || 'General'"></span>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <span class="block text-[10px] font-bold text-gray-400 uppercase">Email</span>
                  <a :href="'mailto:' + activeLead.email" class="text-blue-600 hover:underline font-mono font-semibold" x-text="activeLead.email"></a>
                </div>
                <div>
                  <span class="block text-[10px] font-bold text-gray-400 uppercase">Phone</span>
                  <a :href="'tel:' + activeLead.phone" class="text-gray-700 hover:underline font-semibold" x-text="activeLead.phone || 'N/A'"></a>
                </div>
              </div>

              <div class="border-t pt-3">
                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Message Body</span>
                <p class="bg-gray-50 border p-3 rounded-lg text-gray-700 whitespace-pre-line leading-relaxed font-sans" x-text="activeLead.message"></p>
              </div>

              <!-- Status Update Form -->
              <form method="POST" action="leads.php" class="border-t pt-3 flex items-center justify-between">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="lead_id" :value="activeLead.id">
                
                <div>
                  <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Set Progress Status</label>
                  <select name="status" :value="activeLead.status" class="bg-gray-50 border border-gray-200 rounded px-2.5 py-1 text-xs outline-none focus:bg-white transition-all">
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="contacted">Contacted</option>
                    <option value="closed">Closed</option>
                  </select>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-1.5 px-4 rounded text-[10px] uppercase tracking-wider transition">
                  Update Status
                </button>
              </form>
            </div>
          </div>
        </div>
      </template>
    </div>
  </main>

<?php include '../includes/admin-footer.php'; ?>
