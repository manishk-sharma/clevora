<?php
$all_services = [];
if (isset($pdo) && $pdo) {
    try {
        $all_services = $pdo->query(
          "SELECT slug, name FROM services WHERE is_active=1 ORDER BY sort_order"
        )->fetchAll();
    } catch(Exception $e) {
        error_log('Sidebar query failed: ' . $e->getMessage());
    }
}
if (empty($all_services)) {
    $all_services = [
        ['slug' => 'database-management', 'name' => 'Database Management'],
        ['slug' => 'content-moderation', 'name' => 'Content Moderation'],
        ['slug' => 'digital-marketing', 'name' => 'Digital Marketing'],
        ['slug' => 'business-outsourcing', 'name' => 'Business Outsourcing'],
        ['slug' => 'mortgage-services', 'name' => 'Mortgage Services'],
        ['slug' => 'foreign-language-support', 'name' => 'Foreign Language Support'],
        ['slug' => 'data-validation', 'name' => 'Data Validation'],
        ['slug' => 'inbound-outbound', 'name' => 'Inbound & Outbound Call Center'],
        ['slug' => 'conversion-catalyst', 'name' => 'Conversion Catalyst'],
        ['slug' => 'back-office', 'name' => 'Back Office Support'],
        ['slug' => 'publishing-solutions', 'name' => 'Publishing Solutions']
    ];
}

if (!function_exists('getServiceIcon')) {
    function getServiceIcon($slug) {
        $mapping = [
            'database-management' => 'service-db.svg',
            'content-moderation' => 'service-moderation.svg',
            'digital-marketing' => 'service-marketing.svg',
            'business-outsourcing' => 'service-bpo.svg',
            'mortgage-services' => 'service-mortgage.svg',
            'foreign-language-support' => 'service-language.svg',
            'data-validation' => 'service-validation.svg',
            'inbound-outbound' => 'service-callcenter.svg',
            'conversion-catalyst' => 'service-catalyst.svg',
            'back-office' => 'service-backoffice.svg',
            'publishing-solutions' => 'service-publishing.svg'
        ];
        return '/assets/images/' . ($mapping[$slug] ?? 'service-db.svg');
    }
}
?>

<aside style="width:100%;">
  <!-- Services box -->
  <h3 class="sidebar-header">OUR SERVICES</h3>
  <div style="display: flex; flex-direction: column; gap: 2px;">
    <?php
    foreach($all_services as $s):
      $isActive = ($s['slug'] === ($currentSlug ?? ''));
      $iconUrl = getServiceIcon($s['slug']);
    ?>
    <a href="/detail-services.php?slug=<?=urlencode($s['slug'])?>"
       class="sidebar-service-card <?= $isActive ? 'active' : '' ?>">
       <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
         <div style="display:flex; align-items:center; gap:12px;">
           <img src="<?=$iconUrl?>" style="width:18px; height:18px; object-fit:contain; transition: filter 0.2s;" alt="">
           <span><?=htmlspecialchars($s['name'])?></span>
         </div>
         <span style="font-size:12px;">➔</span>
       </div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Follow Us box -->
  <h3 class="sidebar-header" style="margin-top: 32px;">FOLLOW US</h3>
  <div style="border:1px solid #e8eaf0; border-radius:12px; padding:18px; background:#fff; box-shadow: 0 2px 4px rgba(0,0,0,.02);">
    <div style="display:flex; gap:8px;">
      <?php
      $socials = [['f','#1877f2'],['X','#374151'],['in','#0a66c2'],['🌳','#f97316']];
      foreach($socials as [$l,$c]):
      ?>
      <a href="#"
         style="width:34px; height:34px; border-radius:8px; background:<?=$c?>;
                display:flex; align-items:center; justify-content:center;
                color:#fff; font-size:12px; font-weight:700; text-decoration:none;
                transition: transform 0.2s;"
         onmouseover="this.style.transform='translateY(-2px)'"
         onmouseout="this.style.transform='none'">
        <?=$l?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</aside>
