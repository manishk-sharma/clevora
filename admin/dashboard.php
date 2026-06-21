<?php
require_once 'middleware/auth.php';

$count_services = 0;
$count_gallery = 0;
$count_testimonials = 0;
$count_leads = 0;
$recent_leads = [];

if ($pdo) {
    try {
        $count_services = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
        $count_gallery = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
        $count_testimonials = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
        $count_leads = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
        $recent_leads = $pdo->query("SELECT * FROM leads ORDER BY id DESC LIMIT 5")->fetchAll();
    } catch(Exception $e) {
        error_log('Dashboard stats error: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clevora Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="display:flex; font-family:'Inter',sans-serif;
             background:#f8f9fc; min-height:100vh; margin:0;"
      x-data="{ openNotifications: false }">

  <!-- SIDEBAR -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <main style="flex:1; padding:28px; overflow:auto;">
    <!-- Top bar -->
    <div style="display:flex; align-items:center; justify-content:space-between;
                margin-bottom:24px;">
      <h1 style="font-size:20px; font-weight:700; color:#0f172a; font-family:'Poppins', sans-serif;">Dashboard</h1>
      <div style="display:flex; gap:10px; align-items:center;">
        
        <!-- Notification Bell with Dropdown -->
        <div style="position:relative; cursor:pointer;">
          <div @click="openNotifications = !openNotifications" style="width:32px; height:32px; border-radius:50%; background:#eff6ff;
                      border:1px solid #dbeafe; display:flex; align-items:center;
                      justify-content:center; font-size:15px; user-select:none;">🔔</div>
          <?php if ($count_leads > 0): ?>
          <span style="position:absolute; top:-2px; right:-2px; width:8px; height:8px;
                       background:#f97316; border-radius:50%; border:2px solid #fff;"></span>
          <?php endif; ?>

          <!-- Dropdown list -->
          <div x-show="openNotifications" @click.away="openNotifications = false" 
               style="position:absolute; right:0; top:40px; width:300px; background:#fff; border:1px solid #e8eaf0; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.08); padding:16px; z-index:100; text-align:left; display:none;" x-transition>
            <p style="font-size:11px; font-weight:700; color:#475569; border-bottom:1px solid #f1f5f9; padding-bottom:8px; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">Recent Inquiries</p>
            <?php if (empty($recent_leads)): ?>
              <p style="font-size:11px; color:#94a3b8; text-align:center; padding:12px 0; margin:0;">No submission records logged yet.</p>
            <?php else: ?>
              <div style="max-height:240px; overflow-y:auto;" class="space-y-2">
                <?php foreach($recent_leads as $rl): ?>
                  <a href="/admin/sections/contact-settings.php" style="display:block; text-decoration:none; color:inherit; padding:8px; border-radius:6px; background:#f8fafc; border:1px solid #f1f5f9; transition:all 0.2s;" onmouseover="this.style.background='#eff6ff';this.style.borderColor='#dbeafe'" onmouseout="this.style.background='#f8fafc';this.style.borderColor='#f1f5f9'">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2px;">
                      <span style="font-size:11px; font-weight:700; color:#1e293b;"><?= htmlspecialchars($rl['name']) ?></span>
                      <span style="font-size:9px; color:#94a3b8;"><?= htmlspecialchars(substr($rl['created_at'], 5, 11)) ?></span>
                    </div>
                    <p style="font-size:10px; color:#64748b; margin:0; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;"><?= htmlspecialchars($rl['message']) ?></p>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div style="width:32px; height:32px; border-radius:50%;
                    background:linear-gradient(135deg,#2563eb,#1d4ed8);
                    display:flex; align-items:center; justify-content:center;
                    color:#fff; font-size:13px; font-weight:700; cursor:pointer;">
          A
        </div>
      </div>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mb-7">
      <div style="background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:20px; box-shadow:var(--shadow-card);">
        <p style="font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase;">Services</p>
        <p style="font-size:24px; font-weight:700; color:#2563eb; margin-top:4px;"><?= $count_services ?></p>
      </div>
      <div style="background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:20px; box-shadow:var(--shadow-card);">
        <p style="font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase;">Gallery Images</p>
        <p style="font-size:24px; font-weight:700; color:#f97316; margin-top:4px;"><?= $count_gallery ?></p>
      </div>
      <div style="background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:20px; box-shadow:var(--shadow-card);">
        <p style="font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase;">Testimonials</p>
        <p style="font-size:24px; font-weight:700; color:#10b981; margin-top:4px;"><?= $count_testimonials ?></p>
      </div>
      <div style="background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:20px; box-shadow:var(--shadow-card);">
        <p style="font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase;">Prospect Leads</p>
        <p style="font-size:24px; font-weight:700; color:#6366f1; margin-top:4px;"><?= $count_leads ?></p>
      </div>
    </div>

    <!-- Welcome -->
    <div style="background:#fff; border:1px solid #e8eaf0; border-radius:14px; padding:48px; text-align:center; box-shadow:var(--shadow-card);">
      <p style="font-size:12px; font-weight:600; color:#9ca3af; uppercase tracking-wider mb-2">Clevora Portal</p>
      <h2 style="font-size:26px; font-weight:700; color:#0f172a; font-family:'Poppins', sans-serif;">WELCOME TO CLEVORA CMS</h2>
      <p style="font-size:13px; color:#6b7280; max-width:400px; margin:12px auto 0; line-height:1.7;">Manage BPO services, client logos, testimonials, corporate settings, and incoming leads dynamically.</p>
    </div>
  </main>

</body>
</html>
