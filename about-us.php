<?php
$pageTitle = 'About Clevora | Trusted Outsourcing Partner Since 2011';
$metaDesc = 'Learn about Clevora, a Delhi-based global outsourcing company helping businesses scale operations with skilled teams, secure processes and technology-driven solutions.';

$pageBannerTitle = 'ABOUT US';
$pageBannerBreadcrumb = 'About Us';

require_once 'includes/header.php';

// Fetch about page record
$about_page = null;
if ($pdo) {
    try {
        $about_page = $pdo->query("SELECT * FROM about_page WHERE status=1 LIMIT 1")->fetch();
    } catch (Exception $e) {
        error_log('About page fetch failed: ' . $e->getMessage());
    }
}

$history = !empty($about_page['company_story']) ? $about_page['company_story'] : setting('about_full_history', $pdo);
$mission = !empty($about_page['mission']) ? $about_page['mission'] : setting('about_mission', $pdo);
$vision  = !empty($about_page['vision']) ? $about_page['vision'] : setting('about_vision', $pdo);

// Fetch founder details
$founder = null;
if ($pdo) {
    try {
        $founder = $pdo->query("SELECT * FROM founder WHERE status=1 LIMIT 1")->fetch();
    } catch (Exception $e) {
        error_log('Founder fetch failed: ' . $e->getMessage());
    }
}

$founder_name = $founder ? $founder['name'] : setting('management_founder_name', $pdo);
$founder_role = $founder ? $founder['role'] : setting('management_founder_role', $pdo);
$founder_bio  = $founder ? $founder['bio'] : setting('management_founder_bio', $pdo);
$founder_image = ($founder && !empty($founder['image'])) ? $founder['image'] : '/assets/images/founder.jpg';

// Fetch values
$values = [];
if ($pdo) {
    try {
        $values = $pdo->query("SELECT * FROM about_values WHERE status=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
    } catch (Exception $e) {
        error_log('Values fetch failed: ' . $e->getMessage());
    }
}
if (empty($values)) {
    $values = [
        [
            'title' => 'Client-First Delivery',
            'description' => 'Every engagement starts with your goals. We build teams, processes, and workflows around your business - not the other way around.',
            'icon' => 'fa-shield-halved'
        ],
        [
            'title' => 'Operational Excellence',
            'description' => 'We obsess over accuracy, speed, and consistency. ISO-certified processes, daily QA checks, and continuous improvement are built into every team.',
            'icon' => 'fa-circle-check'
        ],
        [
            'title' => 'Transparency & Trust',
            'description' => 'No hidden fees, no surprise invoices, no black-box operations. You get full visibility into your team, their output, and your costs.',
            'icon' => 'fa-eye'
        ],
        [
            'title' => 'People Matter',
            'description' => 'We invest in our team members with training, career growth, and fair compensation. Happy teams build better outcomes for our clients.',
            'icon' => 'fa-users'
        ]
    ];
}
?>
<?php include 'includes/page-banner.php'; ?>


<!-- ─── OUR STORY ────────────────────────────────────── -->
<section style="background:#fff; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Our Story</span>
      <h2 class="section-title">Built to solve the operations bottleneck</h2>
      <p class="section-copy"><?= htmlspecialchars($about_page['intro'] ?? 'Clevora was founded with a simple observation: growing businesses were spending too much time and money on operational tasks that didn\'t need to be done in-house. Data entry, bookkeeping, admin work - critical tasks, but not core competencies.') ?></p>
    </div>

    <!-- The Problem vs Solution Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Problem Card -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01);">
        <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          The Problem
        </h3>
        <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
          <?= htmlspecialchars($about_page['problem_section'] ?? 'Businesses were hiring expensive local staff for repetitive operational work, or worse, pulling their skilled employees away from high-value tasks. The result: burnout, bloated costs, and stalled growth.') ?>
        </p>
      </div>

      <!-- Solution Card -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01);">
        <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          The Solution
        </h3>
        <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
          <?= htmlspecialchars($about_page['solution_section'] ?? 'We built a model that puts US-based management with India-based operations teams. Clients get dedicated, pre-trained specialists who integrate directly into their workflows - at 70% lower cost than hiring locally.') ?>
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ─── STATS STRIP ──────────────────────────────────── -->
<section style="background:#eff6ff; padding:60px 24px;">
  <div style="max-width:1100px; margin:0 auto;">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
      <div>
        <h3 class="stat-count" data-target="500" data-suffix="+" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">500+</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Team Members</p>
      </div>
      <div>
        <h3 class="stat-count" data-target="70" data-suffix="%" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">70%</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Average Cost Savings</p>
      </div>
      <div>
        <h3 class="stat-count" data-target="99.5" data-suffix="%" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">99.5%</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Accuracy SLA</p>
      </div>
      <div>
        <h3 class="stat-count" data-target="200" data-suffix="+" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">200+</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Clients Served</p>
      </div>
    </div>
  </div>
</section>

<!-- ─── OUR VALUES ───────────────────────────────────── -->
<section style="background:#f8f9fc; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Our Values</span>
      <h2 class="section-title">What drives us every day</h2>
      <p class="section-copy">Our values aren't wall posters. They're the operating principles that shape how we hire, how we deliver, and how we grow.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php foreach ($values as $v): ?>
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:32px; display:flex; gap:20px; align-items:start;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:#3b82f6; flex-shrink:0;">
          <?php if (str_starts_with($v['icon'], '<')): ?>
            <?= $v['icon'] ?>
          <?php elseif (str_starts_with($v['icon'], 'fa-')): ?>
            <i class="fa-solid <?= htmlspecialchars($v['icon']) ?>" style="font-size:20px;"></i>
          <?php else: ?>
            <span style="font-size: 20px;"><?= htmlspecialchars($v['icon']) ?></span>
          <?php endif; ?>
        </div>
        <div>
          <h3 style="font-size:17px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            <?= htmlspecialchars($v['title']) ?>
          </h3>
          <p style="font-size:13.5px; color:#4b5563; line-height:1.6; margin:0;">
            <?= htmlspecialchars($v['description']) ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── GLOBAL PRESENCE ──────────────────────────────── -->
<section style="background:#fff; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Global Presence</span>
      <h2 class="section-title">Managed Delivery, India-operated</h2>
      <p class="section-copy">Our hybrid model combines the best of both worlds: dedicated account management for communication and quality oversight, with India-based operations teams for cost-efficient delivery.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Card 1 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01); display:flex; flex-direction:column; gap:20px;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:#3b82f6;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Dedicated Account Management
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
            Our dedicated account managers handle client relationships, onboarding oversight, service level agreements (SLAs), and strategic consultation, ensuring seamless communication.
          </p>
        </div>
      </div>

      <!-- Card 2 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01); display:flex; flex-direction:column; gap:20px;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(244,63,94,0.1); display:flex; align-items:center; justify-content:center; color:#f43f5e;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            India Delivery Operations
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
            Based in state-of-the-art facilities in Delhi NCR, India. Our teams run 24/7/365 operations, executing support, data operations, and database solutions.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ─── FOUNDER & MANAGEMENT ────────────────────────── -->
<section style="background:#f8f9fc; padding:100px 24px;">
  <div style="max-width:900px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Founder</span>
      <h2 class="section-title">Led by our leadership team</h2>
    </div>

    <!-- Leader Profile Card -->
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:24px; padding:48px; box-shadow:0 4px 30px rgba(0,0,0,0.02); display:flex; flex-direction:column; md:flex-row; gap:40px; align-items:center;">
      <div style="text-align:center; flex-shrink:0; width:160px;">
        <img src="<?= htmlspecialchars($founder_image) ?>" alt="<?= htmlspecialchars($founder_name) ?>" loading="lazy" style="width:140px; height:140px; border-radius:50%; object-fit:cover; margin-bottom:16px; border:4px solid #f1f5f9;">
        <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:4px; font-family:'Poppins', sans-serif;"><?= htmlspecialchars($founder_name) ?></h3>
        <p style="font-size:12px; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin:0;"><?= htmlspecialchars($founder_role) ?></p>
      </div>
      
      <div style="flex:1; border-top:1px solid #e2e8f0; md:border-top:none; md:border-left:1px solid #e2e8f0; padding-top:24px; md:padding-top:0; md:padding-left:40px;">
        <p style="font-size:14.5px; color:#4b5563; line-height:1.8; margin:0; white-space:pre-line;">
          <?= htmlspecialchars($founder_bio) ?>
        </p>
      </div>
    </div>
  </div>
</section>


<?php require_once 'includes/footer.php'; ?>
