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
        ['image_url' => '/assets/images/gallery-3.jpg', 'caption' => 'Team Collaboration Session']
    ];
}
?>

<div style="max-width:1200px; margin:0 auto; padding:48px 24px;" class="space-y-16">
  <!-- Management Profile Card -->
  <div style="background:#f8f9fc; border:1px solid #e8eaf0; border-radius:14px; padding:32px;">
    <!-- Section header -->
    <div style="text-align:center; margin-bottom:40px;">
      <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                   font-size:11px; font-weight:600; padding:4px 14px;
                   border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
        EXECUTIVE LEADERSHIP
      </span>
      <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
        Our Management
      </h2>
      <div style="width:48px; height:3px; background:#2563eb;
                  border-radius:2px; margin:12px auto 0;"></div>
    </div>

    <div style="display:flex; gap:48px; align-items:center; flex-wrap:wrap;">
      <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;" class="w-full md:w-auto">
        <div style="width:160px; height:160px; border-radius:50%; overflow:hidden; border:4px solid #fff; box-shadow:var(--shadow-card);">
          <img src="/assets/images/founder.jpg" alt="<?= htmlspecialchars($founder_name) ?>" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin-top:14px; font-family:'Poppins',sans-serif;"><?= htmlspecialchars($founder_name) ?></h3>
        <p style="font-size:10px; color:#f97316; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-top:2px;"><?= htmlspecialchars($founder_role) ?></p>
      </div>
      <div style="flex:1; min-width:300px;" class="space-y-4">
        <h4 style="font-size:16px; font-weight:700; color:#0f172a; font-family:'Poppins',sans-serif; border-bottom:1px solid #e8eaf0; padding-bottom:8px;">Biography & Vision</h4>
        <p style="font-size:13px; color:#4b5563; line-height:1.9; whitespace: pre-line;">
          <?= htmlspecialchars($founder_bio) ?>
        </p>
      </div>
    </div>
  </div>

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
        Photo Gallery Archive
      </h2>
      <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
        Have a look at our operations floors and data server rooms.
      </p>
      <div style="width:48px; height:3px; background:#2563eb;
                  border-radius:2px; margin:12px auto 0;"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
      <?php foreach($gallery as $g): ?>
      <div style="border-radius:12px; overflow:hidden; position:relative;
                  border:1px solid #e8eaf0; aspect-ratio:4/3; background:#f0f4ff;
                  display:flex; align-items:center; justify-content:center;"
           onmouseover="this.querySelector('.gallery-overlay').style.opacity='1'"
           onmouseout="this.querySelector('.gallery-overlay').style.opacity='0'">
        <img src="<?=htmlspecialchars($g['image_url'])?>"
             alt="<?=htmlspecialchars($g['caption']??'')?>"
             style="width:100%; height:100%; object-fit:cover;">
        <div class="gallery-overlay"
             style="position:absolute; inset:0; background:rgba(37,99,235,.65);
                    display:flex; flex-direction:column; align-items:center;
                    justify-content:center; gap:8px; opacity:0; transition:opacity .25s;">
          <?php if(!empty($g['caption'])): ?>
          <p style="color:#fff; font-size:12px; font-weight:600;">
            <?=htmlspecialchars($g['caption'])?>
          </p>
          <?php endif; ?>
          <a href="<?=htmlspecialchars($g['image_url'])?>" target="_blank"
             style="background:#fff; color:#2563eb; font-size:11px; font-weight:700;
                    padding:6px 14px; border-radius:6px; text-decoration:none;">
            View More
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
