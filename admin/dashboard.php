<?php
require_once 'middleware/auth.php';

$adminPageTitle = 'Clevora Admin Dashboard';

// ── Fetch Dashboard Stats ───────────────────────────────
$stats = [
    'leads' => ['count' => 0, 'icon' => '📨', 'color' => '#6366f1', 'label' => 'Website Leads', 'desc' => 'New inquiries received from contact forms.'],
    'careers' => ['count' => 0, 'icon' => '💼', 'color' => '#10b981', 'label' => 'Active Job Openings', 'desc' => 'Active career opportunities listed.'],
    'services' => ['count' => 0, 'icon' => '⚙️', 'color' => '#2563eb', 'label' => 'Active Services', 'desc' => 'Currently published BPO service offerings.'],
    'categories' => ['count' => 0, 'icon' => '📁', 'color' => '#4f46e5', 'label' => 'Service Categories', 'desc' => 'Organized service classification groups.'],
    'technology' => ['count' => 0, 'icon' => '💻', 'color' => '#3b82f6', 'label' => 'Technology Sections', 'desc' => 'Active tech infrastructure cards.'],
    'testimonials' => ['count' => 0, 'icon' => '💬', 'color' => '#f59e0b', 'label' => 'Testimonials', 'desc' => 'Client reviews and success stories.'],
    'gallery' => ['count' => 0, 'icon' => '🖼️', 'color' => '#14b8a6', 'label' => 'Gallery Albums', 'desc' => 'Workspace and team photo albums.'],
    'clients' => ['count' => 0, 'icon' => '👥', 'color' => '#ec4899', 'label' => 'Client Logos', 'desc' => 'Partner organization brand logos.'],
    'hero_slides' => ['count' => 0, 'icon' => '🎠', 'color' => '#3b82f6', 'label' => 'Hero Slides', 'desc' => 'Homepage sliders configured.'],
    'solutions' => ['count' => 0, 'icon' => '💡', 'color' => '#f59e0b', 'label' => 'Solutions', 'desc' => 'Homepage core solution cards.'],
    'industries' => ['count' => 0, 'icon' => '🏭', 'color' => '#14b8a6', 'label' => 'Industries', 'desc' => 'Homepage industry cards.'],
    'faqs' => ['count' => 0, 'icon' => '❓', 'color' => '#8b5cf6', 'label' => 'FAQs', 'desc' => 'Frequently asked questions.'],
    'partners' => ['count' => 0, 'icon' => '🤝', 'color' => '#ec4899', 'label' => 'Partners', 'desc' => 'Partner organization logos.'],
];

$recent_leads = [];
$new_leads_count = 0;

if ($pdo) {
    try {
        $stats['leads']['count'] = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
        $stats['careers']['count'] = $pdo->query("SELECT COUNT(*) FROM careers WHERE is_active = 1")->fetchColumn();
        $stats['services']['count'] = $pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
        $stats['categories']['count'] = $pdo->query("SELECT COUNT(*) FROM service_categories WHERE is_active = 1")->fetchColumn();
        $stats['technology']['count'] = $pdo->query("SELECT COUNT(*) FROM technology_sections WHERE is_active = 1")->fetchColumn();
        $stats['testimonials']['count'] = $pdo->query("SELECT COUNT(*) FROM testimonials WHERE is_active = 1")->fetchColumn();
        $stats['gallery']['count'] = $pdo->query("SELECT COUNT(*) FROM gallery_albums")->fetchColumn();
        $stats['clients']['count'] = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
        $stats['hero_slides']['count'] = $pdo->query("SELECT COUNT(*) FROM hero_sliders")->fetchColumn();
        $stats['solutions']['count'] = $pdo->query("SELECT COUNT(*) FROM home_solutions WHERE is_active = 1")->fetchColumn();
        $stats['industries']['count'] = $pdo->query("SELECT COUNT(*) FROM homepage_sections WHERE section_type='industry' AND status = 1")->fetchColumn();
        $stats['faqs']['count'] = $pdo->query("SELECT COUNT(*) FROM homepage_sections WHERE section_type='faq' AND status = 1")->fetchColumn();
        $stats['partners']['count'] = $pdo->query("SELECT COUNT(*) FROM home_partners WHERE is_active = 1")->fetchColumn();
        $recent_leads = $pdo->query("SELECT * FROM leads ORDER BY id DESC LIMIT 8")->fetchAll();
        $new_leads_count = $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn();
    } catch (Exception $e) {
        error_log('Dashboard stats error: ' . $e->getMessage());
    }
}

$quick_actions = [
    ['Update Homepage',     '/admin/sections/home-about.php',        '🏠', '#eff6ff', '#2563eb'],
    ['Add Service',         '/admin/sections/add-service.php',       '➕', '#f0fdf4', '#16a34a'],
    ['Manage Services',     '/admin/sections/services.php',          '⚙️', '#faf5ff', '#7c3aed'],
    ['View Leads',          '/admin/sections/leads.php',             '📨', '#fef3c7', '#d97706'],
    ['Upload Gallery',      '/admin/sections/gallery.php',           '🖼️', '#f0fdfa', '#0d9488'],
    ['Contact Details',     '/admin/sections/contact-settings.php',  '✉️', '#fce7f3', '#db2777'],
];
?>
<?php include 'includes/admin-header.php'; ?>

  <!-- SIDEBAR -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <main style="flex:1; padding:28px; overflow:auto;">
    <!-- Top bar -->
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:28px;">
      <div>
        <h1 style="font-size:22px; font-weight:700; color:#0f172a; font-family:'Poppins', sans-serif;">Dashboard</h1>
        <p style="font-size:12px; color:#94a3b8; margin-top:2px;">Welcome back, <?= htmlspecialchars($_SESSION['clevora_admin_name'] ?? 'Admin') ?>. Here's your CMS overview.</p>
      </div>
      <div style="display:flex; gap:10px; align-items:center;">
        <?php if($new_leads_count > 0): ?>
        <a href="/admin/sections/leads.php" style="display:flex; align-items:center; gap:6px; background:#fef3c7; border:1px solid #fde68a; color:#92400e; font-size:11px; font-weight:600; padding:6px 12px; border-radius:20px; text-decoration:none;">
          📨 <?= $new_leads_count ?> new lead<?= $new_leads_count > 1 ? 's' : '' ?>
        </a>
        <?php endif; ?>
        <div style="width:34px; height:34px; border-radius:50%;
                    background:linear-gradient(135deg,#2563eb,#1d4ed8);
                    display:flex; align-items:center; justify-content:center;
                    color:#fff; font-size:13px; font-weight:700;">
          <?= strtoupper(substr($_SESSION['clevora_admin_name'] ?? 'A', 0, 1)) ?>
        </div>
      </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
      <?php foreach($stats as $key => $s): ?>
      <div style="background:#fff; border:1px solid #e8eaf0; border-radius:14px; padding:20px; position:relative; overflow:hidden;">
        <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; border-radius:10px;
                    background:<?= $s['color'] ?>12; display:flex; align-items:center; justify-content:center; font-size:16px;">
          <?= $s['icon'] ?>
        </div>
        <p style="font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px;"><?= $s['label'] ?></p>
        <p style="font-size:28px; font-weight:700; color:<?= $s['color'] ?>; margin:6px 0 4px; font-family:'Poppins',sans-serif;"><?= $s['count'] ?></p>
        <p style="font-size:10px; color:#94a3b8; line-height:1.4;"><?= $s['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Recent Leads Table -->
      <div class="lg:col-span-2">
        <div style="background:#fff; border:1px solid #e8eaf0; border-radius:14px; overflow:hidden;">
          <div style="padding:18px 20px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size:14px; font-weight:700; color:#0f172a;">Recent Leads</h2>
            <a href="/admin/sections/leads.php" style="font-size:11px; color:#2563eb; font-weight:600; text-decoration:none;">View All →</a>
          </div>
          <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:12px;">
              <thead>
                <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                  <th style="text-align:left; padding:10px 16px; font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px;">Name</th>
                  <th style="text-align:left; padding:10px 16px; font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px;">Email</th>
                  <th style="text-align:left; padding:10px 16px; font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px;">Interest</th>
                  <th style="text-align:left; padding:10px 16px; font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px;">Date</th>
                  <th style="text-align:left; padding:10px 16px; font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px;">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($recent_leads)): ?>
                <tr><td colspan="5" style="text-align:center; padding:32px 16px; color:#94a3b8;">No leads recorded yet.</td></tr>
                <?php else: ?>
                  <?php foreach($recent_leads as $lead):
                    $status_colors = ['new'=>'#dbeafe;color:#1d4ed8','read'=>'#fef3c7;color:#92400e','contacted'=>'#d1fae5;color:#065f46','closed'=>'#f3f4f6;color:#6b7280'];
                    $sc = $status_colors[$lead['status'] ?? 'new'] ?? $status_colors['new'];
                  ?>
                  <tr style="border-bottom:1px solid #f8fafc;">
                    <td style="padding:12px 16px; font-weight:600; color:#1e293b;"><?= htmlspecialchars($lead['name']) ?></td>
                    <td style="padding:12px 16px; color:#64748b; font-family:monospace; font-size:11px;"><?= htmlspecialchars($lead['email']) ?></td>
                    <td style="padding:12px 16px; color:#64748b;"><?= htmlspecialchars($lead['interest'] ?: 'General') ?></td>
                    <td style="padding:12px 16px; color:#94a3b8; font-size:11px;"><?= htmlspecialchars(substr($lead['created_at'], 0, 10)) ?></td>
                    <td style="padding:12px 16px;">
                      <span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; text-transform:uppercase; background:<?= $sc ?>">
                        <?= htmlspecialchars($lead['status'] ?? 'new') ?>
                      </span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div>
        <div style="background:#fff; border:1px solid #e8eaf0; border-radius:14px; padding:20px;">
          <h2 style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:16px;">Quick Actions</h2>
          <div style="display:grid; gap:8px;">
            <?php foreach($quick_actions as [$label, $href, $icon, $bg, $color]): ?>
            <a href="<?= $href ?>"
               style="display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius:10px;
                      text-decoration:none; background:<?= $bg ?>; border:1px solid transparent;
                      transition:all .15s;"
               onmouseover="this.style.borderColor='<?= $color ?>30';this.style.transform='translateX(4px)'"
               onmouseout="this.style.borderColor='transparent';this.style.transform='none'">
              <span style="width:32px; height:32px; border-radius:8px; background:#fff;
                           display:flex; align-items:center; justify-content:center;
                           font-size:14px; box-shadow:0 1px 3px rgba(0,0,0,.06);"><?= $icon ?></span>
              <span style="font-size:12px; font-weight:600; color:<?= $color ?>;"><?= $label ?></span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

<?php include 'includes/admin-footer.php'; ?>
