<?php
$pageTitle = 'Our Album & Management | Clevora | Global Outsourcing Services';
$metaDesc = 'View our work gallery, corporate training workshops, call center facilities, and learn about the leadership driving Clevora.';

$pageBannerTitle = 'OUR ALBUM';
$pageBannerBreadcrumb = 'Album';

require_once 'includes/header.php';
include 'includes/page-banner.php';

$founder_name = setting('management_founder_name', $pdo);
$founder_role = setting('management_founder_role', $pdo);
$founder_bio  = setting('management_founder_bio', $pdo);

$gallery = [];
if ($pdo) {
    try {
        $gallery = $pdo->query("SELECT * FROM gallery ORDER BY sort_order")->fetchAll();
    } catch (Exception $e) {
        error_log('Gallery query error: ' . $e->getMessage());
    }
}

if (empty($gallery)) {
    $gallery = [
        ['image_url' => '/assets/images/gallery-1.jpg', 'caption' => 'Our Modern Workspace'],
        ['image_url' => '/assets/images/gallery-2.jpg', 'caption' => 'Server Room & Infrastructure'],
        ['image_url' => '/assets/images/gallery-3.jpg', 'caption' => 'Team Collaboration Session'],
        ['image_url' => '/assets/images/gallery-4.jpg', 'caption' => 'Modern Operations Workspace'],
        ['image_url' => '/assets/images/gallery-5.jpg', 'caption' => 'Corporate Team Training'],
        ['image_url' => '/assets/images/gallery-6.jpg', 'caption' => 'Network Infrastructure & IT Support']
    ];
}
?>

<div style="max-width:1200px; margin:0 auto; padding:48px 24px;" class="space-y-16">


  <!-- Gallery Grid -->
  <div class="space-y-8">
    <!-- Section header -->
    <div style="text-align:center; margin-bottom:40px;">
      <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                   font-size:11px; font-weight:600; padding:4px 14px;
                   border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
        OUR WORKPLACE
      </span>
      <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
        Our Workplace Facilities
      </h2>
      <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
        Have a look at our operations floors and data server rooms.
      </p>
      <div style="width:48px; height:3px; background:#2563eb;
                  border-radius:2px; margin:12px auto 0;"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php foreach($gallery as $idx => $g): 
        // Determine category tag and sub-description based on captions
        $tag = 'Workplace';
        $desc = 'A glimpse inside our day-to-day operations and high-end infrastructure.';
        
        if (stripos($g['caption'], 'workspace') !== false) {
            $tag = 'Workspace';
            $desc = 'Explore our state-of-the-art office layout built for high productivity and employee collaboration.';
        } elseif (stripos($g['caption'], 'server') !== false || stripos($g['caption'], 'infrastructure') !== false) {
            $tag = 'Infrastructure';
            $desc = 'Equipped with high-speed backup connections, clean server racks, and advanced failover systems.';
        } elseif (stripos($g['caption'], 'collaboration') !== false || stripos($g['caption'], 'team') !== false) {
            $tag = 'Collaboration';
            $desc = 'Our regular training modules and collaborative team sessions in action.';
        }
        
        // Mock a date based on index for a realistic blog-roll feel
        $dates = ['Jun 18, 2026', 'Jun 12, 2026', 'May 28, 2026', 'May 14, 2026'];
        $date = $dates[$idx % count($dates)];
      ?>
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 4px 20px rgba(0,0,0,0.01); transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.01)';">
        <!-- Card Image -->
        <div style="aspect-ratio:16/9; overflow:hidden; background:#f1f5f9; border-bottom:1px solid #f1f5f9; position:relative;">
          <img src="<?=htmlspecialchars($g['image_url'])?>"
               alt="<?=htmlspecialchars($g['caption']??'')?>"
               loading="lazy"
               style="width:100%; height:100%; object-fit:cover;">
        </div>
        
        <!-- Card Content -->
        <div style="padding:24px; flex:1; display:flex; flex-direction:column; justify-content:space-between; gap:16px;">
          <div class="space-y-3">
            <!-- Category Tag -->
            <div>
              <span style="font-size:11px; font-weight:700; color:#4b5563; background:#f1f5f9; padding:4px 12px; border-radius:9999px; letter-spacing:0.5px;">
                <?=htmlspecialchars($tag)?>
              </span>
            </div>
            
            <!-- Title -->
            <h3 style="font-size:18px; font-weight:700; color:#0f172a; line-height:1.4; font-family:'Poppins', sans-serif; margin:0;">
              <?=htmlspecialchars($g['caption'])?>
            </h3>
            
            <!-- Description -->
            <p style="font-size:13.5px; color:#6b7280; line-height:1.6; margin:0;">
              <?=htmlspecialchars($desc)?>
            </p>
          </div>
          
          <!-- Card Footer -->
          <div style="border-top:1px solid #f1f5f9; padding-top:16px; display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:12px; color:#94a3b8; font-weight:500;">
              <?=$date?> | Facility Tour
            </span>
            <a href="<?=htmlspecialchars($g['image_url'])?>" target="_blank" style="font-size:12.5px; font-weight:600; color:#2563eb; text-decoration:none;">
              View Full Size &rarr;
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
