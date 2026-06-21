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
        ['slug' => 'database-management', 'name' => 'Database Management', 'icon_url' => '/assets/images/service-db.svg', 'intro' => 'Preventing Oversized Non-Standard Data Formats, Multiple Sourcing and Non-Standard Data systems, Address Verification, Postal Code Correction, NCOA and Standardization. Cleaning Databases, Address Checking, Output to Printer, E-Mailer, or Printed Mailing Catalog.'],
        ['slug' => 'content-moderation', 'name' => 'Content Moderation', 'icon_url' => '/assets/images/service-moderation.svg', 'intro' => 'Protect your brand reputation and build user trust with our global content moderation services. We monitor and filter text, images, and videos 24/7.'],
        ['slug' => 'digital-marketing', 'name' => 'Digital Marketing', 'icon_url' => '/assets/images/service-marketing.svg', 'intro' => 'Enhance your digital footprint with SEO, PPC, and social media campaigns designed to generate high-quality leads.'],
        ['slug' => 'business-outsourcing', 'name' => 'Business Outsourcing', 'icon_url' => '/assets/images/service-bpo.svg', 'intro' => 'Streamline your operations with our business process outsourcing (BPO) solutions, from front-office to back-office tasks.'],
        ['slug' => 'mortgage-services', 'name' => 'Mortgage Services', 'icon_url' => '/assets/images/service-mortgage.svg', 'intro' => 'Accurate and fast mortgage processing support, document indexing, and validation for lenders and brokers.'],
        ['slug' => 'foreign-language-support', 'name' => 'Foreign Language Support', 'icon_url' => '/assets/images/service-language.svg', 'intro' => 'Connect with global clients through multilingual customer support, translation, and localized services.'],
        ['slug' => 'data-validation', 'name' => 'Data Validation', 'icon_url' => '/assets/images/service-validation.svg', 'intro' => 'Maintain a high-quality database with real-time validation, address verification, and database scrubbing.'],
        ['slug' => 'inbound-outbound', 'name' => 'Inbound & Outbound Call Center', 'icon_url' => '/assets/images/service-callcenter.svg', 'intro' => 'Drive sales and support customers with professional inbound and outbound tele-calling services.'],
        ['slug' => 'conversion-catalyst', 'name' => 'Conversion Catalyst', 'icon_url' => '/assets/images/service-catalyst.svg', 'intro' => 'Boost your website\'s conversion rate through user experience design auditing and conversion rate optimization (CRO).'],
        ['slug' => 'back-office', 'name' => 'Back Office Support', 'icon_url' => '/assets/images/service-backoffice.svg', 'intro' => 'Efficient data entry, bookkeeping, processing invoices, and document classification services for your backend teams.'],
        ['slug' => 'publishing-solutions', 'name' => 'Publishing Solutions', 'icon_url' => '/assets/images/service-publishing.svg', 'intro' => 'Professional formatting, layout typesetting, proofreading, and e-book conversion services.']
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
        <img src="<?=htmlspecialchars($s['icon_url'])?>" alt="" loading="lazy">
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
