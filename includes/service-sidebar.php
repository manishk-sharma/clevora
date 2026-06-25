<?php
$related_services = [];
$other_categories = [];
$current_category_id = $service['category_id'] ?? 0;
$current_service_slug = $service['slug'] ?? '';

if (isset($pdo) && $pdo) {
    try {
        // Fetch related services in same category
        if ($current_category_id > 0) {
            $stmt_rel = $pdo->prepare("SELECT slug, name, icon_url FROM services WHERE category_id = ? AND is_active = 1 AND slug != ? ORDER BY sort_order ASC, name ASC");
            $stmt_rel->execute([$current_category_id, $current_service_slug]);
            $related_services = $stmt_rel->fetchAll();
        }
        
        // Fetch other active categories with service count
        $other_categories = $pdo->query("
            SELECT c.name, c.slug, c.icon,
            (SELECT COUNT(*) FROM services s WHERE s.category_id = c.id AND s.is_active = 1) AS service_count
            FROM service_categories c
            WHERE c.is_active = 1 AND c.id != " . (int)$current_category_id . "
            ORDER BY c.sort_order ASC, c.name ASC
        ")->fetchAll();
    } catch(Exception $e) {
        error_log('Sidebar query failed: ' . $e->getMessage());
    }
}
?>

<aside style="width:100%;" class="space-y-8">
  
  <!-- Related Services Box -->
  <?php if (!empty($related_services)): ?>
  <div>
    <h3 class="sidebar-header uppercase tracking-wider text-xs font-bold text-gray-400 mb-3">Related Solutions</h3>
    <div style="display: flex; flex-direction: column; gap: 2px;">
      <?php foreach($related_services as $s): ?>
      <a href="/detail-services.php?slug=<?=urlencode($s['slug'])?>"
         class="sidebar-service-card hover:bg-gray-50 flex items-center justify-between p-3 border border-gray-100 rounded-lg bg-white transition duration-200">
         <div style="display:flex; align-items:center; gap:10px;">
           <?php if (!empty($s['icon_url'])): ?>
             <img src="<?=$s['icon_url']?>" style="width:16px; height:16px; object-fit:contain;" alt="">
           <?php else: ?>
             <span style="font-size: 14px;">⚙</span>
           <?php endif; ?>
           <span class="text-xs font-semibold text-gray-700"><?=htmlspecialchars($s['name'])?></span>
         </div>
         <span style="font-size:10px; color:#9ca3af;">➔</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Categories Box -->
  <div>
    <h3 class="sidebar-header uppercase tracking-wider text-xs font-bold text-gray-400 mb-3">Other Service Areas</h3>
    <div style="display: flex; flex-direction: column; gap: 4px;">
      <?php foreach($other_categories as $cat): ?>
      <a href="/services.php?category=<?=urlencode($cat['slug'])?>"
         class="flex items-center justify-between p-3 border border-gray-100 rounded-lg bg-white hover:bg-blue-50/30 transition duration-200 group">
         <div style="display:flex; align-items:center; gap:10px;">
           <span class="text-lg"><?=htmlspecialchars($cat['icon'])?></span>
           <span class="text-xs font-semibold text-gray-700 group-hover:text-brandBlue transition-colors"><?=htmlspecialchars($cat['name'])?></span>
         </div>
         <span class="text-[9px] font-bold text-gray-400 bg-gray-50 group-hover:bg-blue-50 group-hover:text-brandBlue px-2 py-0.5 rounded-full transition-colors">
           <?= (int)$cat['service_count'] ?>
         </span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Contact Widget -->
  <div style="border:1px solid #e8eaf0; border-radius:12px; padding:20px; background:#1a1a2e; color:#fff;" class="shadow-sm">
    <h4 class="font-poppins font-bold text-sm mb-2 text-white">Need Consultation?</h4>
    <p class="text-[11px] text-gray-300 leading-relaxed mb-4">Our project managers are available 24/7 to structure operations for your corporate teams.</p>
    <a href="/contact.php" class="block text-center bg-brandBlue text-white hover:bg-blue-500 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
      Contact Us
    </a>
  </div>
</aside>
