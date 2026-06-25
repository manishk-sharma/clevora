<?php
require_once 'includes/db.php';
$slug = $_GET['slug'] ?? '';

$service = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT s.*, c.name AS category_name, c.slug AS category_slug 
            FROM services s 
            LEFT JOIN service_categories c ON s.category_id = c.id 
            WHERE s.slug = ? AND s.is_active = 1
        ");
        $stmt->execute([$slug]);
        $service = $stmt->fetch();
    } catch(Exception $e) {
        error_log('Detail service fetch error: ' . $e->getMessage());
    }
}

if (!$service) {
    header('Location: /services.php');
    exit;
}

$pageTitle = htmlspecialchars($service['name']) . ' | ' . htmlspecialchars($service['category_name'] ?? 'Services') . ' | Clevora';
$metaDesc = htmlspecialchars(substr($service['intro'], 0, 150));
$currentSlug = $slug;

// Layout variables for page banner
$pageBannerTitle = htmlspecialchars($service['name']);
$pageBannerBreadcrumb = htmlspecialchars($service['name']);

require_once 'includes/header.php';
include 'includes/page-banner.php';
?>

<div class="content-wrapper py-16 px-4 bg-gray-50/50">
  <div class="container mx-auto max-w-6xl">
    
    <div class="flex flex-col lg:flex-row gap-12 items-start">
      
      <!-- LEFT MAIN CONTENT COLUMN -->
      <div class="flex-1 w-full space-y-10">
        
        <!-- Service Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 md:p-10 space-y-6">
          <div class="flex flex-wrap items-center gap-2">
            <?php if (!empty($service['category_name'])): ?>
              <a href="/services.php?category=<?= urlencode($service['category_slug']) ?>" class="text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-brandBlue px-3 py-1 rounded-full hover:bg-brandBlue hover:text-white transition-colors">
                <?= htmlspecialchars($service['category_name']) ?>
              </a>
            <?php endif; ?>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 px-3 py-1 rounded-full">
              SLA Guaranteed
            </span>
          </div>

          <h2 class="text-3xl font-bold text-navy font-poppins mt-2">
            <?= htmlspecialchars($service['name']) ?>
          </h2>

          <p class="text-sm text-gray-500 leading-relaxed font-semibold">
            <?= nl2br(htmlspecialchars($service['intro'])) ?>
          </p>

          <?php if (!empty($service['challenge_solved'])): ?>
          <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-navy mb-3">Business Challenge Solved</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
              <?= nl2br(htmlspecialchars($service['challenge_solved'])) ?>
            </p>
          </div>
          <?php endif; ?>

          <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-navy mb-3">Service Operations Overview</h3>
            <p class="text-xs text-gray-500 leading-relaxed space-y-4">
              <?= nl2br(htmlspecialchars(!empty($service['detailed_description']) ? $service['detailed_description'] : $service['full_content'])) ?>
            </p>
          </div>

          <!-- Features Section (Grid of Cards) -->
          <?php $features = json_decode($service['features']??'[]', true); ?>
          <?php if(!empty($features)): ?>
          <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-navy mb-4">Key Operations Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <?php foreach($features as $f): ?>
              <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100/60">
                <span class="w-5 h-5 rounded-full bg-brandBlue/10 text-brandBlue flex items-center justify-center text-xs font-bold">✓</span>
                <span class="text-xs font-semibold text-gray-700"><?=htmlspecialchars($f)?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Key Benefits Section -->
        <?php $benefits = json_decode($service['benefits']??'[]', true); ?>
        <?php if(!empty($benefits)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 md:p-10 space-y-6">
          <h3 class="text-lg font-bold text-navy font-poppins">Strategic Process Benefits</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach($benefits as $b): ?>
            <div class="p-5 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100/70 shadow-sm flex flex-col justify-between">
              <div class="w-8 h-8 rounded-lg bg-orange-50 text-brandOrange flex items-center justify-center font-bold mb-4">✓</div>
              <div>
                <h4 class="text-xs font-bold text-navy mb-2"><?=htmlspecialchars($b)?></h4>
                <p class="text-[11px] text-gray-400 leading-relaxed">Engineered to secure performance, compliance, and cost efficiency across systems.</p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Bottom CTA Banner Card -->
        <div class="bg-gradient-to-r from-navy to-navyMid rounded-xl p-8 shadow-md text-white flex flex-col md:flex-row items-center justify-between gap-6">
          <div class="space-y-2 text-center md:text-left">
            <h4 class="font-poppins font-bold text-lg text-white">Ready to improve your operations?</h4>
            <p class="text-xs text-gray-300">Partner with Clevora and build a reliable outsourcing solution designed around your business.</p>
          </div>
          <a href="/contact.php?interest=<?= urlencode($service['name']) ?>" class="bg-brandOrange text-white hover:bg-orange-600 transition px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-wider text-center shrink-0">
            Get a Free Quote &nbsp;➔
          </a>
        </div>

      </div>

      <!-- RIGHT SIDEBAR COLUMN -->
      <div class="w-full lg:w-[300px] shrink-0">
        <?php require_once 'includes/service-sidebar.php'; ?>
      </div>

    </div>

  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
