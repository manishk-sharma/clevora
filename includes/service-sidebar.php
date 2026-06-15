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
        ['slug' => 'software-solutions', 'name' => 'Software Solutions'],
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
?>

<aside style="width:220px; flex-shrink:0; padding:0 0 0 4px;" class="w-full md:w-[220px]">
  <!-- Services box -->
  <div style="border:1px solid #e8eaf0; border-radius:10px;
              overflow:hidden; margin-bottom:16px; background:#fff;">
    <div style="background:#1a1a2e; color:#fff; font-size:11px;
                font-weight:700; padding:11px 14px; letter-spacing:.5px;
                text-transform:uppercase; position:relative;">
      SERVICES
      <!-- Decorative corner cut -->
      <span style="position:absolute; right:0; top:0; width:0; height:0;
                   border-left:32px solid transparent;
                   border-top:36px solid #2563eb;"></span>
    </div>
    <?php
    foreach($all_services as $s):
      $isActive = ($s['slug'] === ($currentSlug ?? ''));
    ?>
    <a href="/detail-services.php?slug=<?=urlencode($s['slug'])?>"
       style="display:block; padding:10px 14px; font-size:11px;
              border-bottom:1px solid #f0f2f8; text-decoration:none;
              transition:all .15s;
              <?= $isActive
                ? 'background:#2563eb; color:#fff; font-weight:600;'
                : 'color:#4b5563;' ?>"
       onmouseover="<?=$isActive?'':'this.style.background=\"#f8f9fc\";this.style.color=\"#2563eb\"'?>"
       onmouseout="<?=$isActive?'':'this.style.background=\"\";this.style.color=\"#4b5563\"'?>">
      <?=htmlspecialchars($s['name'])?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Follow Us box -->
  <div style="border:1px solid #e8eaf0; border-radius:10px; overflow:hidden; background:#fff;">
    <div style="background:#1a1a2e; color:#fff; font-size:11px;
                font-weight:700; padding:11px 14px; letter-spacing:.5px;
                text-transform:uppercase; position:relative;">
      FOLLOW US
      <span style="position:absolute; right:0; top:0; width:0; height:0;
                   border-left:32px solid transparent;
                   border-top:36px solid #2563eb;"></span>
    </div>
    <div style="display:flex; gap:8px; padding:12px 14px;">
      <?php
      $socials = [['f','#1877f2'],['X','#374151'],['in','#0a66c2'],['🌳','#f97316']];
      foreach($socials as [$l,$c]):
      ?>
      <a href="#"
         style="width:30px; height:30px; border-radius:6px; background:<?=$c?>;
                display:flex; align-items:center; justify-content:center;
                color:#fff; font-size:10px; font-weight:700; text-decoration:none;">
        <?=$l?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</aside>
