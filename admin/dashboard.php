<?php
require_once 'middleware/auth.php';

$count_services = 0;
$count_gallery = 0;
$count_testimonials = 0;
$count_leads = 0;

if ($pdo) {
    try {
        $count_services = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
        $count_gallery = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
        $count_testimonials = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
        $count_leads = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
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
</head>
<body style="display:flex; font-family:'Inter',sans-serif;
             background:#f8f9fc; min-height:100vh; margin:0;">

  <!-- SIDEBAR -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <main style="flex:1; padding:28px; overflow:auto;">
    <!-- Top bar -->
    <div style="display:flex; align-items:center; justify-content:space-between;
                margin-bottom:24px;">
      <h1 style="font-size:20px; font-weight:700; color:#0f172a; font-family:'Poppins', sans-serif;">Dashboard</h1>
      <div style="display:flex; gap:10px; align-items:center;">
        <div style="position:relative; cursor:pointer;">
          <div style="width:32px; height:32px; border-radius:50%; background:#eff6ff;
                      border:1px solid #dbeafe; display:flex; align-items:center;
                      justify-content:center; font-size:15px;">🔔</div>
          <span style="position:absolute; top:-2px; right:-2px; width:8px; height:8px;
                       background:#f97316; border-radius:50%; border:2px solid #fff;"></span>
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
