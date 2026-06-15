<?php
require_once 'includes/db.php';
$slug = $_GET['slug'] ?? '';

$service = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE slug=? AND is_active=1");
        $stmt->execute([$slug]);
        $service = $stmt->fetch();
    } catch(Exception $e) {
        error_log('Detail service fetch error: ' . $e->getMessage());
    }
}

// Fallback logic for local testing without DB
if (!$service) {
    $fallbacks = [
        'database-management' => [
            'name' => 'Database Management',
            'icon_url' => '/assets/images/service-db.png',
            'intro' => "Address Verification, Postal Code Correction, NCOA and Standardization.\nCleaning Databases, Address Checking, Output to Printer, E-Mailer, or Printed Mailing Catalog.",
            'features' => '["Address Verification", "Database Cleaning", "Postal Code Standardization"]',
            'benefits' => '["Increased mail deliverability", "Lower database processing costs", "Clean list profiles"]'
        ],
        'content-moderation' => [
            'name' => 'Content Moderation',
            'icon_url' => '/assets/images/service-moderation.png',
            'intro' => "Protect your brand reputation and build user trust with our global content moderation services. We monitor and filter text, images, and videos 24/7.",
            'features' => '["Image Moderation", "Video Moderation", "Text and Review moderation"]',
            'benefits' => '["Community safety", "Brand shield", "Full 24/7 coverage"]'
        ]
    ];
    if (isset($fallbacks[$slug])) {
        $service = $fallbacks[$slug];
        $service['slug'] = $slug;
    } else {
        header('Location: /services.php');
        exit;
    }
}

$pageTitle = htmlspecialchars($service['name']) . ' Services | Clevora';
$metaDesc = htmlspecialchars(substr($service['intro'], 0, 150));
$currentSlug = $slug;

// Layout variables for page banner
$pageBannerTitle = htmlspecialchars($service['name']) . ' Services';
$pageBannerBreadcrumb = htmlspecialchars($service['name']);

require_once 'includes/header.php';
include 'includes/page-banner.php';
?>

<div style="max-width:1200px; margin:0 auto; padding: 24px;" class="px-4">
  <div style="display:flex; gap:32px; align-items:start; flex-wrap: wrap;" class="flex-col md:flex-row">
    <!-- Left: content -->
    <main style="flex:1; min-width:0; background:#fff;" class="w-full">

      <!-- Service image -->
      <?php if (!empty($service['icon_url'])): ?>
      <img src="<?=htmlspecialchars($service['icon_url'])?>"
           alt="<?=htmlspecialchars($service['name'])?>"
           style="width:100%; max-width:380px; border-radius:14px;
                  border:1px solid #e8eaf0; margin-bottom:22px;">
      <?php else: ?>
      <div style="width:100%; max-width:380px; height:200px; border-radius:14px; background:#eff6ff;
                  display:flex; align-items:center; justify-content:center; font-size:32px; margin-bottom:22px; border:1px solid #e8eaf0;">⚙</div>
      <?php endif; ?>

      <h2 style="font-size:22px; font-weight:700; color:#0f172a;
                 margin-bottom:12px; font-family:'Poppins',sans-serif;">
        <?=htmlspecialchars($service['name'])?> Solutions
      </h2>

      <p style="font-size:13px; color:#4b5563; line-height:1.9; margin-bottom:20px; whitespace: pre-line;">
        <?=nl2br(htmlspecialchars($service['intro']))?>
      </p>

      <!-- Features 2-column grid -->
      <?php $features = json_decode($service['features']??'[]', true); ?>
      <?php if(!empty($features)): ?>
      <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:12px;">
        Key Features
      </h3>
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:8px;
                  margin-bottom:22px;">
        <?php foreach($features as $f): ?>
        <div style="display:flex; align-items:center; gap:8px;
                    background:#f8f9fc; border-radius:8px; padding:10px 12px;
                    font-size:12px; color:#374151; border:1px solid #e8eaf0;">
          <span style="color:#2563eb; font-weight:700; flex-shrink:0;">✓</span>
          <?=htmlspecialchars($f)?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Benefits list -->
      <?php $benefits = json_decode($service['benefits']??'[]', true); ?>
      <?php if(!empty($benefits)): ?>
      <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:10px;">
        Key Benefits
      </h3>
      <ul style="list-style:none; padding:0; margin-bottom:22px;">
        <?php foreach($benefits as $b): ?>
        <li style="display:flex; align-items:center; gap:8px;
                   font-size:12px; color:#4b5563; margin-bottom:9px;">
          <span style="color:#2563eb; font-weight:700; flex-shrink:0;">→</span>
          <?=htmlspecialchars($b)?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <!-- Bottom CTA -->
      <div style="background:linear-gradient(135deg,#1a1a2e,#1e3a5f);
                  border-radius:12px; padding:22px; text-align:center;">
        <p style="font-size:13px; color:#93c5fd; margin-bottom:12px;">
          Need a custom solution? Contact our experts today.
        </p>
        <a href="/contact.php"
           style="display:inline-block; background:#2563eb; color:#fff;
                  padding:10px 24px; border-radius:8px; font-size:13px;
                  font-weight:600; text-decoration:none; transition:background .2s;"
           onmouseover="this.style.background='#1d4ed8'"
           onmouseout="this.style.background='#2563eb'">
          Get a Free Consultation
        </a>
      </div>
    </main>

    <!-- Right: sidebar -->
    <?php require_once 'includes/service-sidebar.php'; ?>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
