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

// Fetch repeatable details
$features = [];
$benefits = [];
$process = [];
$industries = [];
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM service_features WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$service['id']]);
        $features = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT * FROM service_benefits WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$service['id']]);
        $benefits = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT * FROM service_process WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$service['id']]);
        $process = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT * FROM service_industries WHERE service_id=? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$service['id']]);
        $industries = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log('Detail repeaters fetch failed: ' . $e->getMessage());
    }
}

// Fallback to JSON columns if repeaters are empty
if (empty($features) && !empty($service['features'])) {
    $features_decoded = json_decode($service['features'], true);
    if (is_array($features_decoded)) {
        foreach ($features_decoded as $f) {
            $features[] = ['title' => $f, 'description' => ''];
        }
    }
}
if (empty($benefits) && !empty($service['benefits'])) {
    $benefits_decoded = json_decode($service['benefits'], true);
    if (is_array($benefits_decoded)) {
        foreach ($benefits_decoded as $b) {
            $benefits[] = ['title' => $b, 'description' => ''];
        }
    }
}

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
            <?php foreach ($industries as $ind): ?>
              <span class="text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 px-3 py-1 rounded-full">
                <?= htmlspecialchars($ind['name']) ?>
              </span>
            <?php endforeach; ?>
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
            <div class="text-xs text-gray-500 leading-relaxed">
              <?= $service['challenge_solved'] ?>
            </div>
          </div>
          <?php endif; ?>

          <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-navy mb-3">Service Operations Overview</h3>
            <div class="text-xs text-gray-500 leading-relaxed">
              <?= !empty($service['detailed_description']) ? $service['detailed_description'] : nl2br(htmlspecialchars($service['full_content'])) ?>
            </div>
          </div>

          <!-- Features Section (Grid of Cards) -->
          <?php if(!empty($features)): ?>
          <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-navy mb-4">Key Operations Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <?php foreach($features as $f): ?>
              <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100/60">
                <span class="w-5 h-5 rounded-full bg-brandBlue/10 text-brandBlue flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                <div>
                  <span class="text-xs font-semibold text-gray-700 block font-bold"><?=htmlspecialchars($f['title'])?></span>
                  <?php if (!empty($f['description'])): ?>
                    <p class="text-[11px] text-gray-400 mt-1"><?=htmlspecialchars($f['description'])?></p>
                  <?php endif; ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Process Steps Section -->
          <?php if(!empty($process)): ?>
          <div class="border-t border-gray-100 pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-navy mb-4">Onboarding & Execution Process</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <?php foreach($process as $proc): ?>
              <div class="p-4 bg-gray-50 rounded-lg border border-gray-100/60">
                <span class="text-xs font-bold text-navy block mb-1"><?= htmlspecialchars($proc['title']) ?></span>
                <p class="text-[11px] text-gray-400 leading-relaxed"><?= htmlspecialchars($proc['description']) ?></p>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

        </div>

        <!-- Key Benefits Section -->
        <?php if(!empty($benefits)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 md:p-10 space-y-6">
          <h3 class="text-lg font-bold text-navy font-poppins">Strategic Process Benefits</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach($benefits as $b): ?>
            <div class="p-5 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100/70 shadow-sm flex flex-col justify-between">
              <div class="w-8 h-8 rounded-lg bg-orange-50 text-brandOrange flex items-center justify-center font-bold mb-4">✓</div>
              <div>
                <h4 class="text-xs font-bold text-navy mb-2"><?=htmlspecialchars($b['title'])?></h4>
                <p class="text-[11px] text-gray-400 leading-relaxed"><?=htmlspecialchars(!empty($b['description']) ? $b['description'] : 'Engineered to secure performance, compliance, and cost efficiency across systems.')?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Bottom CTA Banner Card -->
        <div class="bg-gradient-to-r from-navy to-navyMid rounded-xl p-8 shadow-md text-white flex flex-col md:flex-row items-center justify-between gap-6">
          <div class="space-y-2 text-center md:text-left">
            <h4 class="font-poppins font-bold text-lg text-white"><?= htmlspecialchars($service['cta_heading'] ?: 'Ready to improve your operations?') ?></h4>
            <p class="text-xs text-gray-300"><?= htmlspecialchars($service['cta_text'] ?: 'Partner with Clevora and build a reliable outsourcing solution designed around your business.') ?></p>
          </div>
          <a href="/contact.php?interest=<?= urlencode($service['name']) ?>" class="bg-brandOrange text-white hover:bg-orange-600 transition px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-wider text-center shrink-0">
            <?= htmlspecialchars($service['cta_button'] ?: 'Get a Free Quote') ?> &nbsp;➔
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
