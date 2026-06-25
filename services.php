<?php
$pageTitle = 'BPO & Outsourcing Services | Clevora Solutions';
$metaDesc = 'Explore customer support, content moderation, data operations, HR, finance, KPO, e-commerce and call center outsourcing solutions.';

$pageBannerTitle = 'OUR SOLUTIONS';
$pageBannerBreadcrumb = 'Solutions';

require_once 'includes/header.php';
include 'includes/page-banner.php';

$category_slug = $_GET['category'] ?? '';
$category = null;
$items = [];
$categories = [];

if ($pdo) {
    try {
        if (!empty($category_slug)) {
            // Fetch single category
            $stmt = $pdo->prepare("SELECT * FROM service_categories WHERE slug = ? AND is_active = 1");
            $stmt->execute([$category_slug]);
            $category = $stmt->fetch();
            
            if ($category) {
                $pageBannerTitle = htmlspecialchars($category['name']);
                $pageBannerBreadcrumb = htmlspecialchars($category['name']);
                
                // Fetch services under this category
                $stmt_svc = $pdo->prepare("SELECT * FROM services WHERE category_id = ? AND is_active = 1 ORDER BY sort_order ASC, name ASC");
                $stmt_svc->execute([$category['id']]);
                $items = $stmt_svc->fetchAll();
            }
        }
        
        if (!$category) {
            // Fetch all categories with service counts
            $categories = $pdo->query("
                SELECT c.*, 
                (SELECT COUNT(*) FROM services s WHERE s.category_id = c.id AND s.is_active = 1) AS service_count 
                FROM service_categories c 
                WHERE c.is_active = 1 
                ORDER BY c.sort_order ASC, c.name ASC
            ")->fetchAll();
        }
    } catch (Exception $e) {
        error_log('Error loading services page: ' . $e->getMessage());
    }
}
?>

<section class="section section--soft min-h-[60vh] py-16">
  <div class="container mx-auto px-4 max-w-6xl">
    
    <?php if ($category): ?>
      <!-- Category Services View -->
      <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200/60 pb-6">
        <div>
          <a href="/services.php" class="inline-flex items-center text-xs font-semibold text-brandBlue hover:underline mb-2">
            &larr; Back to All Categories
          </a>
          <h2 class="text-3xl font-bold text-navy font-poppins flex items-center gap-3">
            <span class="text-4xl"><?= htmlspecialchars($category['icon']) ?></span>
            <?= htmlspecialchars($category['name']) ?>
          </h2>
          <p class="text-sm text-gray-500 mt-1 max-w-2xl"><?= htmlspecialchars($category['description']) ?></p>
        </div>
        <a href="/contact.php?interest=<?= urlencode($category['name']) ?>" class="bg-brandBlue text-white hover:bg-navyMid transition px-6 py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider text-center shrink-0">
          Request Consultation
        </a>
      </div>

      <?php if (empty($items)): ?>
        <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
          <p class="text-gray-400 text-sm">No services currently listed under this category.</p>
          <a href="/services.php" class="inline-block mt-4 text-xs font-semibold text-brandBlue hover:underline">Return to Categories</a>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php foreach ($items as $s): ?>
            <article class="bg-white rounded-xl shadow-sm border border-gray-100/80 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
              <div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-content-center mb-4">
                  <?php if ($s['icon_url']): ?>
                    <img src="<?= htmlspecialchars($s['icon_url']) ?>" class="w-6 h-6 object-contain" alt="" loading="lazy">
                  <?php else: ?>
                    <span class="text-xl text-brandBlue font-bold">⚙</span>
                  <?php endif; ?>
                </div>
                <h3 class="text-base font-bold text-navy mb-2 font-poppins"><?= htmlspecialchars($s['name']) ?></h3>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-3 mb-4"><?= htmlspecialchars($s['intro']) ?></p>
              </div>
              <a href="/detail-services.php?slug=<?= urlencode($s['slug']) ?>" class="text-xs font-semibold text-brandBlue hover:underline flex items-center gap-1 mt-auto">
                Explore Solution <span>&rarr;</span>
              </a>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <!-- Categories Grid View (Default) -->
      <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-xs font-bold text-brandBlue uppercase tracking-wider bg-blue-50 px-3 py-1 rounded-full">Our Solutions Structure</span>
        <h2 class="text-3xl md:text-4xl font-bold text-navy mt-4 mb-4 font-poppins">Dynamic BPO & Process Management</h2>
        <p class="text-sm text-gray-500 leading-relaxed">Choose from our specialized operation categories below. We integrate trained personnel, security guardrails, and quality check systems to deliver outstanding process operations.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($categories as $cat): ?>
          <a href="/services.php?category=<?= urlencode($cat['slug']) ?>" class="group bg-white rounded-xl border border-gray-100/90 p-8 shadow-sm hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
            <div>
              <div class="w-14 h-14 rounded-xl bg-orange-50/50 group-hover:bg-brandBlue/5 flex items-center justify-center text-3xl mb-6 transition-colors">
                <?= htmlspecialchars($cat['icon']) ?>
              </div>
              <h3 class="text-lg font-bold text-navy group-hover:text-brandBlue transition-colors font-poppins mb-3">
                <?= htmlspecialchars($cat['name']) ?>
              </h3>
              <p class="text-xs text-gray-400 leading-relaxed line-clamp-3 mb-6">
                <?= htmlspecialchars($cat['description']) ?>
              </p>
            </div>
            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-auto">
              <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full group-hover:bg-blue-50 group-hover:text-brandBlue transition-colors">
                <?= (int)$cat['service_count'] ?> <?= $cat['service_count'] == 1 ? 'Solution' : 'Solutions' ?>
              </span>
              <span class="text-brandBlue font-bold text-xs group-hover:translate-x-1.5 transition-transform duration-200">
                View Solutions &rarr;
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
