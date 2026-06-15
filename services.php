<?php
$pageTitle = 'Our Services | Clevora | Global Outsourcing & Call Center Solutions';
$metaDesc = 'Explore Clevora\'s comprehensive suite of outsourcing services, including database management, content moderation, multilingual customer support, and IT solutions.';

$pageBannerTitle = 'OUR SERVICES';
$pageBannerBreadcrumb = 'Services';

require_once 'includes/header.php';
include 'includes/page-banner.php';

$services = [];
if ($pdo) {
    try {
        $services = $pdo->query("SELECT * FROM services WHERE is_active=1 ORDER BY sort_order")->fetchAll();
    } catch (Exception $e) {
        error_log('Services query error: ' . $e->getMessage());
    }
}

if (empty($services)) {
    $services = [
        ['slug' => 'database-management', 'name' => 'Database Management', 'icon_url' => '/assets/images/service-db.svg', 'intro' => 'Preventing Oversized Non-Standard Data Formats, Address Verification, Postal Code Correction, NCOA and Standardization.'],
        ['slug' => 'content-moderation', 'name' => 'Content Moderation', 'icon_url' => '/assets/images/service-moderation.svg', 'intro' => 'Protect your brand reputation and build user trust with our global content moderation services.'],
        ['slug' => 'digital-marketing', 'name' => 'Digital Marketing', 'icon_url' => '/assets/images/service-marketing.svg', 'intro' => 'Enhance your digital footprint with SEO, PPC, and social media campaigns designed to generate high-quality leads.'],
        ['slug' => 'software-solutions', 'name' => 'Software Solutions', 'icon_url' => '/assets/images/service-software.svg', 'intro' => 'Custom software development, web applications, and enterprise solutions built to scale with your growing business.']
    ];
}
?>

<section class="section section--soft">
  <div class="container">
  <div class="section-head">
    <span class="section-kicker">What We Provide</span>
    <h2 class="section-title">Comprehensive BPO & Outsourcing Solutions</h2>
    <p class="section-copy">Tailored services designed to streamline operational workflows, improve response times, and keep your teams focused on growth.</p>
  </div>

  <div class="card-grid">
    <?php foreach($services as $s): ?>
    <article class="card feature-card">
      <div class="feature-card__icon">
        <?php if($s['icon_url']): ?>
        <img src="<?=htmlspecialchars($s['icon_url'])?>" alt="">
        <?php else: ?>
        <span style="color:#2563eb; font-weight:700; font-size:18px;">*</span>
        <?php endif; ?>
      </div>
      <h3><?=htmlspecialchars($s['name'])?></h3>
      <p><?=htmlspecialchars($s['intro'])?></p>
      <a href="/detail-services.php?slug=<?=urlencode($s['slug'])?>">Explore Solution &rarr;</a>
    </article>
    <?php endforeach; ?>
  </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
