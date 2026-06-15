<?php
$pageTitle = 'Our Clients | Clevora | Global Outsourcing Services';
$metaDesc = 'See our partner client logos and discover the organizations that trust Clevora Global Outsourcing Services for their backend BPO needs.';

$pageBannerTitle = 'OUR CLIENTS';
$pageBannerBreadcrumb = 'Clients';

require_once 'includes/header.php';
include 'includes/page-banner.php';

$clients = [];
if ($pdo) {
    try {
        $clients = $pdo->query("SELECT * FROM clients")->fetchAll();
    } catch (Exception $e) {
        error_log('Clients query error: ' . $e->getMessage());
    }
}

if (empty($clients)) {
    $clients = [
        ['logo_url' => '/assets/images/client-1.png', 'name' => 'Client One'],
        ['logo_url' => '/assets/images/client-2.png', 'name' => 'Client Two'],
        ['logo_url' => '/assets/images/client-3.png', 'name' => 'Client Three'],
        ['logo_url' => '/assets/images/client-4.png', 'name' => 'Client Four'],
        ['logo_url' => '/assets/images/client-5.png', 'name' => 'Client Five'],
        ['logo_url' => '/assets/images/client-6.png', 'name' => 'Client Six']
    ];
}
?>

<div style="max-width:1200px; margin:0 auto; padding:48px 24px;" class="space-y-12">
  <!-- Section header -->
  <div style="text-align:center; margin-bottom:40px;">
    <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                 font-size:11px; font-weight:600; padding:4px 14px;
                 border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
      PARTNERSHIPS
    </span>
    <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
      Trusted By Organizations Globally
    </h2>
    <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
      We provide backend processes and calling solutions to industry leaders.
    </p>
    <div style="width:48px; height:3px; background:#2563eb;
                border-radius:2px; margin:12px auto 0;"></div>
  </div>

  <div style="display:grid; grid-template-columns:repeat(6,1fr); gap:20px;" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 justify-center items-center">
    <?php foreach($clients as $c): ?>
    <div style="background:#fff; border:1px solid #e8eaf0; border-radius:12px; padding:20px;
                display:flex; align-items:center; justify-content:center; height:100px;
                box-shadow:var(--shadow-card); transition:all .2s;"
         onmouseover="this.style.boxShadow='var(--shadow-hover)';this.style.transform='translateY(-2px)'"
         onmouseout="this.style.boxShadow='var(--shadow-card)';this.style.transform='none'">
      <?php if (!empty($c['logo_url'])): ?>
      <img src="<?= htmlspecialchars($c['logo_url']) ?>" alt="<?= htmlspecialchars($c['name'] ?? 'Client') ?>" style="max-height:100%; max-width:100%; object-fit:contain; filter:grayscale(100%); transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(100%)'">
      <?php else: ?>
      <span style="color:#6b7280; font-weight:600; font-size:11px; text-transform:uppercase;"><?= htmlspecialchars($c['name'] ?? 'Client') ?></span>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
