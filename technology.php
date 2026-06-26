<?php
require_once 'includes/db.php';

function tech_setting(string $key, ?PDO $pdo): string {
    if (!$pdo) {
        return get_default_tech_setting($key);
    }
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM technology_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : get_default_tech_setting($key);
    } catch (Exception $e) {
        return get_default_tech_setting($key);
    }
}

function get_default_tech_setting(string $key): string {
    $defaults = [
        'hero_small_text' => 'Clevora Global Operations',
        'hero_title' => 'Technology',
        'breadcrumb_title' => 'Technology',
        'main_label' => 'Our Capabilities',
        'main_heading' => 'Secure Operations & Technology Infrastructure',
        'main_description' => "Clevora combines skilled teams, modern infrastructure, secure processes, and advanced operational systems to deliver reliable outsourcing services worldwide.\n\nOur production servers are housed in a secure data center environment with high redundancy, including backup power generation, multi-layered cooling systems, and isolated network routers. We partner with tier-1 bandwidth carriers to support secure BPO client environments.",
        'security_title' => 'Data Protection & Security',
        'security_description' => 'We implement controlled access, secure workflows, confidentiality practices, and operational safeguards to protect client information.',
        'security_badge' => 'Security & Compliance Assured'
    ];
    return $defaults[$key] ?? '';
}

// Fetch general settings
$hero_small_text = tech_setting('hero_small_text', $pdo);
$hero_title      = tech_setting('hero_title', $pdo);
$breadcrumb_title = tech_setting('breadcrumb_title', $pdo);
$main_label      = tech_setting('main_label', $pdo);
$main_heading    = tech_setting('main_heading', $pdo);
$main_description = tech_setting('main_description', $pdo);
$security_title   = tech_setting('security_title', $pdo);
$security_description = tech_setting('security_description', $pdo);
$security_badge   = tech_setting('security_badge', $pdo);

$pageTitle = 'Technology & Secure Operations Infrastructure | Clevora';
$metaDesc = 'Discover Clevora’s secure infrastructure, workflow systems, quality monitoring and scalable outsourcing technology.';

$pageBannerTitle = strtoupper($hero_title ?: 'Technology');
$pageBannerBreadcrumb = $breadcrumb_title ?: 'Technology';

require_once 'includes/header.php';
include 'includes/page-banner.php';

// Fetch technology cards
$tech_cards = [];
if ($pdo) {
    try {
        $tech_cards = $pdo->query("SELECT * FROM technology_sections WHERE is_active = 1 ORDER BY sort_order ASC, id DESC")->fetchAll();
    } catch (Exception $e) {
        error_log('Tech sections fetch failed: ' . $e->getMessage());
    }
}
if (empty($tech_cards)) {
    $tech_cards = [
        [
            'section_title' => 'Infrastructure',
            'description' => 'Enterprise-ready infrastructure designed to support secure, stable, and scalable global outsourcing operations.'
        ],
        [
            'section_title' => 'Security & Compliance',
            'description' => 'Structured security practices, controlled access, confidentiality processes, and responsible data handling standards.'
        ],
        [
            'section_title' => 'Workflow Systems',
            'description' => 'Organized workflow management systems designed for productivity tracking, process visibility, and consistent delivery.'
        ],
        [
            'section_title' => 'Quality Monitoring',
            'description' => 'Performance reviews, quality checks, reporting, and improvement processes ensure reliable service delivery.'
        ],
        [
            'section_title' => 'Backup & Business Continuity',
            'description' => 'Reliable backup practices and continuity planning help maintain smooth business operations.'
        ],
        [
            'section_title' => 'Operations Technology',
            'description' => 'Modern communication platforms and operational tools enable efficient customer support and business management.'
        ]
    ];
}
?>

<!-- ─── TOP INFRASTRUCTURE HERO ───────────────────────── -->
<section style="background:#fff; padding:80px 24px;">
  <div style="max-width:1200px; margin:0 auto; display:flex; gap:48px; align-items:center; flex-wrap:wrap;">
    
    <div style="flex:1.2; min-width:320px;" class="space-y-4">
      <div style="margin-bottom:16px;">
        <span class="section-kicker"><?= htmlspecialchars($main_label) ?></span>
        <h2 class="section-title" style="margin-top:8px;"><?= htmlspecialchars($main_heading) ?></h2>
      </div>
      <p class="section-copy" style="white-space:pre-line;"><?= htmlspecialchars($main_description) ?></p>
    </div>

    <!-- Right Column: Interactive Tech Mockup -->
    <div style="flex:1; min-width:320px; display:flex; justify-content:center;">
      <div style="background:#f8f9fc; border:1px solid #e2e8f0; border-radius:24px; padding:32px; width:100%; max-width:480px; box-shadow:0 10px 30px rgba(0,0,0,0.02); position:relative; overflow:hidden;">
        <!-- Glowing decoration orb -->
        <div style="position:absolute; -top:50px; -right:50px; width:150px; height:150px; border-radius:50%; background:rgba(59,130,246,0.1); filter:blur(40px); z-index:0;"></div>
        
        <div style="position:relative; z-index:1; text-align:center;" class="space-y-6">
          <div style="width:72px; height:72px; border-radius:50%; background:rgba(37,99,235,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto; color:#2563eb; font-size:32px;">
            <i class="fa-solid fa-lock"></i>
          </div>
          <h3 style="font-size:20px; font-weight:700; color:#0f172a; font-family:'Poppins', sans-serif; margin-bottom:8px;"><?= htmlspecialchars($security_title) ?></h3>
          <p style="font-size:13.5px; color:#64748b; line-height:1.7; margin:0 auto; max-width:340px;">
            <?= htmlspecialchars($security_description) ?>
          </p>
          <div style="display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; padding:8px 16px; border-radius:9999px; font-size:12px; color:#10b981; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.02);">
            <span style="width:8px; height:8px; border-radius:50%; background:#10b981; display:inline-block; animation:pulse 2s infinite;"></span>
            <?= htmlspecialchars($security_badge) ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ─── TECHNOLOGY STANDARDS (Dark Background) ───────── -->
<section style="background:#0f172a; padding:100px 24px; color:#fff;">
  <div style="max-width:1200px; margin:0 auto;">
    
    <div class="section-head">
      <span class="section-kicker" style="color:#60a5fa;">Quality & Operations</span>
      <h2 class="section-title" style="color:#fff;">Operational Technology Standards</h2>
      <p class="section-copy" style="color:#94a3b8;">Integrating secure networks, structured workflows, and quality monitoring channels for enterprise delivery.</p>
    </div>

    <!-- 3-Column Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
      <?php foreach ($tech_cards as $sec): ?>
      <!-- Metric Card -->
      <div style="background:#1e293b; border:1px solid #334155; border-radius:24px; padding:48px 32px; display:flex; flex-direction:column; gap:20px; justify-content:center;">
        <div>
          <h4 style="font-size:16px; font-weight:600; color:#cbd5e1; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            <?= htmlspecialchars($sec['section_title']) ?>
          </h4>
          <p style="font-size:13.5px; color:#94a3b8; line-height:1.6; margin:0;">
            <?= htmlspecialchars($sec['description']) ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
