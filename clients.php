<?php
$pageTitle = 'Client Success Stories & Reviews | Clevora';
$metaDesc = 'Read reviews from global teams we support across customer experience, e-commerce, data management, and back-office operations.';

$pageBannerTitle = 'OUR CLIENTS';
$pageBannerBreadcrumb = 'Clients';

require_once 'includes/header.php';
include 'includes/page-banner.php';

// Fetch clients from DB if available
$clients = [];
if ($pdo) {
    try {
        $clients = $pdo->query("SELECT * FROM clients")->fetchAll();
    } catch (Exception $e) {
        error_log('Clients query error: ' . $e->getMessage());
    }
}
if (empty($clients)) {
    $clients = [
        ['logo_url' => '/assets/images/client-1.png', 'name' => 'Client One'],
        ['logo_url' => '/assets/images/client-2.png', 'name' => 'Client Two'],
        ['logo_url' => '/assets/images/client-3.png', 'name' => 'Client Three'],
        ['logo_url' => '/assets/images/client-4.png', 'name' => 'Client Four'],
        ['logo_url' => '/assets/images/client-5.png', 'name' => 'Client Five'],
        ['logo_url' => '/assets/images/client-6.png', 'name' => 'Client Six']
    ];
}

// Testimonials database / fallback list
$testimonials = [
    [
        'name' => 'Eric Martindale',
        'role' => 'Founder',
        'company' => 'Elite Commerce Group',
        'category' => 'E-Commerce Support',
        'rating' => '5.0',
        'quote' => 'Clevora helped us organize our support operations with a professional team and structured approach.',
        'photo' => '/assets/images/testimonial-1.jpg'
    ],
    [
        'name' => 'Carol Swan',
        'role' => 'Marketing Manager',
        'company' => 'On Air Dining',
        'category' => 'Data Management',
        'rating' => '5.0',
        'quote' => 'Their ability to understand our workflow and scale resources made them a valuable operations partner.',
        'photo' => '/assets/images/testimonial-2.jpg'
    ],
    [
        'name' => 'Rahul Kumar Gupta',
        'role' => 'Operations Lead',
        'company' => 'Karnal Logistics',
        'category' => 'BPO Services',
        'rating' => '5.0',
        'quote' => 'We appreciate their communication, reliability and commitment toward quality delivery.',
        'photo' => '/assets/images/testimonial-1.jpg'
    ]
];
?>

<!-- ─── PARTNERS / CLIENTS GRID (Below page banner) ────── -->
<section style="background:#fff; padding:80px 24px;">
  <div style="max-width:1200px; margin:0 auto;" class="space-y-12">
    <!-- Header -->
    <div style="text-align:center; margin-bottom:40px;">
      <span style="display:inline-block; background:#eff6ff; color:#3b82f6;
                   font-size:11px; font-weight:700; padding:6px 16px;
                   border-radius:9999px; letter-spacing:1px; margin-bottom:16px; text-transform:uppercase;">
        Partnerships
      </span>
      <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
        Trusted By Organizations Globally
      </h2>
      <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
        Businesses choose Clevora for reliable teams, structured processes and consistent operational support.
      </p>
    </div>

    <!-- Logos Infinite Marquee -->
    <div style="width:100%; overflow:hidden; position:relative; padding:10px 0;">
      <div class="marquee-track" style="display:flex; gap:24px; width:max-content;" onmouseover="this.style.animationPlayState='paused'" onmouseout="this.style.animationPlayState='running'">
        <?php 
        // Duplicate the logo list to create a seamless looping effect
        $marquee_items = array_merge($clients, $clients);
        foreach($marquee_items as $c): 
        ?>
        <div style="background:#fff; border:1px solid #e8eaf0; border-radius:16px; padding:24px;
                    display:flex; align-items:center; justify-content:center; height:100px; width:180px; flex-shrink:0;
                    box-shadow:0 4px 20px rgba(0,0,0,0.01); transition:all 0.3s;"
             onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.05)';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.01)';this.style.transform='none'">
          <?php if (!empty($c['logo_url'])): ?>
            <img src="<?= htmlspecialchars($c['logo_url']) ?>" alt="<?= htmlspecialchars($c['name'] ?? 'Client') ?>" loading="lazy" style="max-height:100%; max-width:100%; object-fit:contain; filter:grayscale(100%); transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(100%)'">
          <?php else: ?>
            <span style="color:#6b7280; font-weight:600; font-size:11px; text-transform:uppercase;"><?= htmlspecialchars($c['name'] ?? 'Client') ?></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<!-- ─── REVIEWS HERO & FILTER & LISTINGS (Below Partnerships) ─── -->
<section style="background:#f8f9fc; padding:80px 24px;">
  <div style="max-width:1200px; margin:0 auto;" class="space-y-8">
    
    <!-- Reviews Header: Title and Stats Card side-by-side -->
    <div style="display:flex; gap:48px; align-items:center; flex-wrap:wrap; margin-bottom:48px;">
      <!-- Left side -->
      <div style="flex:1.2; min-width:320px;" class="space-y-6">
        <span style="display:inline-block; background:#eff6ff; color:#3b82f6;
                     font-size:11px; font-weight:700; padding:6px 16px;
                     border-radius:9999px; letter-spacing:1.5px; text-transform:uppercase;">
          Client Stories
        </span>
        <h2 style="font-size:clamp(32px, 5vw, 44px); font-weight:700; color:#0f172a; line-height:1.2; font-family:'Poppins', sans-serif; letter-spacing:-0.02em; margin:0;">
          Clients love Clevora
        </h2>
        <p style="font-size:15px; color:#4b5563; line-height:1.7; max-width:580px; margin:0;">
          Honest reviews from the operations teams we support across e-commerce, real estate, accounting, gaming, and more.
        </p>
      </div>

      <!-- Right side: Stats Card -->
      <div style="flex:1; min-width:320px; display:flex; justify-content:center;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:24px; padding:32px; width:100%; max-width:440px; box-shadow:0 10px 30px rgba(0,0,0,0.03); display:flex; justify-content:space-between; align-items:center; text-align:center;">
          <div style="flex:1;">
            <h3 class="stat-count" data-target="41" style="font-size:36px; font-weight:700; color:#2563eb; margin:0 0 4px 0; font-family:'Poppins',sans-serif;">41</h3>
            <p style="font-size:10px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin:0;">5-Star Reviews</p>
          </div>
          <div style="width:1px; height:60px; background:#e2e8f0;"></div>
          <div style="flex:1;">
            <h3 class="stat-count" data-target="22" style="font-size:36px; font-weight:700; color:#2563eb; margin:0 0 4px 0; font-family:'Poppins',sans-serif;">22</h3>
            <p style="font-size:10px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin:0;">Industries Served</p>
          </div>
          <div style="width:1px; height:60px; background:#e2e8f0;"></div>
          <div style="flex:1;">
            <h3 style="font-size:36px; font-weight:700; color:#2563eb; margin:0 0 4px 0; font-family:'Poppins',sans-serif;"><span class="stat-count" data-target="5" data-suffix=".0">5</span><span style="font-size:16px; color:#64748b; font-weight:400;">/5</span></h3>
            <p style="font-size:10px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin:0;">Avg Rating</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Two-Column Review Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php foreach($testimonials as $t): ?>
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:32px; display:flex; flex-direction:column; justify-content:space-between; box-shadow:0 4px 20px rgba(0,0,0,0.01);">
        <div>
          <!-- Star ratings & Categories -->
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="display:flex; align-items:center; gap:6px; color:#f59e0b; font-size:13px; font-weight:700;">
              ★★★★★ <span style="color:#0f172a; font-size:12px; font-weight:600; margin-left:4px;"><?=htmlspecialchars($t['rating'])?></span>
            </div>
            <div style="display:flex; gap:6px;">
              <span style="font-size:10px; font-weight:700; color:#ef4444; background:#fef2f2; padding:2px 8px; border-radius:4px; letter-spacing:0.5px; text-transform:uppercase;">Featured</span>
              <span style="font-size:10px; font-weight:700; color:#2563eb; background:#eff6ff; padding:2px 8px; border-radius:4px; letter-spacing:0.5px; text-transform:uppercase;"><?=htmlspecialchars($t['category'])?></span>
            </div>
          </div>
          
          <!-- Testimonial Quote -->
          <p style="font-size:14.5px; color:#374151; line-height:1.7; margin-bottom:24px; font-style:italic;">
            "<?=htmlspecialchars($t['quote'])?>"
          </p>
        </div>

        <!-- Reviewer Info -->
        <div style="border-top:1px solid #f1f5f9; padding-top:20px; display:flex; align-items:center; gap:12px;">
          <div style="width:40px; height:40px; border-radius:50%; background:#2563eb; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; overflow:hidden;">
            <?php if($t['photo']): ?>
              <img src="<?=htmlspecialchars($t['photo'])?>" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
              <?=strtoupper(substr($t['name'], 0, 1))?>
            <?php endif; ?>
          </div>
          <div>
            <h4 style="font-size:14px; font-weight:700; color:#0f172a; margin:0 0 2px 0;"><?=htmlspecialchars($t['name'])?></h4>
            <p style="font-size:12px; color:#6b7280; margin:0;"><?=htmlspecialchars($t['role'])?> at <?=htmlspecialchars($t['company'])?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
