<?php
require_once 'includes/db.php';
$slug = $_GET['slug'] ?? '';

$service = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE slug=? AND is_active=1");
        $stmt->execute([$slug]);
        $service = $stmt->fetch();
    } catch(Exception $e) {
        error_log('Detail service fetch error: ' . $e->getMessage());
    }
}

// Fallback logic for local testing without DB
if (!$service) {
    $fallbacks = [
        'database-management' => [
            'name' => 'Database Management',
            'icon_url' => '/assets/images/service-db.svg',
            'intro' => "Address Verification, Postal Code Correction, NCOA and Standardization.\nCleaning Databases, Address Checking, Output to Printer, E-Mailer, or Printed Mailing Catalog.",
            'features' => '["Address Verification", "Database Cleaning", "Postal Code Standardization"]',
            'benefits' => '["Increased mail deliverability", "Lower database processing costs", "Clean list profiles"]'
        ],
        'content-moderation' => [
            'name' => 'Content Moderation',
            'icon_url' => '/assets/images/service-moderation.svg',
            'intro' => "Protect your brand reputation and build user trust with our global content moderation services. We monitor and filter text, images, and videos 24/7.",
            'features' => '["Image Moderation", "Video Moderation", "Text and Review moderation"]',
            'benefits' => '["Community safety", "Brand shield", "Full 24/7 coverage"]'
        ]
    ];
    if (isset($fallbacks[$slug])) {
        $service = $fallbacks[$slug];
        $service['slug'] = $slug;
    } else {
        header('Location: /services.php');
        exit;
    }
}

$pageTitle = htmlspecialchars($service['name']) . ' Services | Clevora';
$metaDesc = htmlspecialchars(substr($service['intro'], 0, 150));
$currentSlug = $slug;

// Layout variables for page banner
$pageBannerTitle = htmlspecialchars($service['name']) . ' Services';
$pageBannerBreadcrumb = htmlspecialchars($service['name']);

require_once 'includes/header.php';
include 'includes/page-banner.php';
?>

<div class="content-wrapper" style="padding: 60px 20px;">
  
  <div style="display: flex; gap: 48px; align-items: start; flex-wrap: wrap;" class="flex-col lg:flex-row">
    
    <!-- LEFT MAIN CONTENT COLUMN -->
    <div style="flex: 1; min-width: 0;" class="w-full">
      
      <!-- 2-column Inner Layout: Image Card on left, Details on right -->
      <div class="service-content" style="margin-top: 0; margin-bottom: 48px;">
        
        <!-- Left Sub-column: Image Card -->
        <div class="service-image-card">
          <!-- Optional Dot patterns -->
          <div style="position: absolute; top: 18px; left: 18px; width: 40px; height: 40px; background-image: radial-gradient(#cbd5e1 2px, transparent 2px); background-size: 8px 8px; opacity: 0.5;"></div>
          <div style="position: absolute; bottom: 18px; right: 18px; width: 40px; height: 40px; background-image: radial-gradient(#cbd5e1 2px, transparent 2px); background-size: 8px 8px; opacity: 0.5;"></div>
          
          <?php if (!empty($service['icon_url'])): ?>
          <img src="<?=htmlspecialchars($service['icon_url'])?>"
               alt="<?=htmlspecialchars($service['name'])?>"
               style="width: 220px; height: auto; object-fit: contain;">
          <?php else: ?>
          <div style="width:220px; height:220px; display:flex; align-items:center; justify-content:center; font-size:64px; color:var(--blue);">⚙</div>
          <?php endif; ?>
        </div>

        <!-- Right Sub-column: Details -->
        <div class="service-details">
          <span style="display: inline-block; color: var(--blue); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            Reliable. Accurate. Actionable.
          </span>
          <h2 style="font-size: 28px; font-weight: 700; color: var(--text-dark); margin-bottom: 16px; font-family: 'Poppins', sans-serif;">
            <?=htmlspecialchars($service['name'])?> Solutions
          </h2>
          
          <p style="font-size: 14px; color: #64748b; line-height: 1.8; margin-bottom: 24px;">
            <?=nl2br(htmlspecialchars($service['intro']))?>
          </p>

          <!-- Features Section (Grid of Cards) -->
          <?php $features = json_decode($service['features']??'[]', true); ?>
          <?php if(!empty($features)): ?>
          <h3 style="font-size: 13px; font-weight: 800; color: var(--text-dark); margin-bottom: 12px; text-transform: uppercase; letter-spacing: .06em;">Key Features</h3>
          <div class="features-grid">
            <?php foreach($features as $f): ?>
            <div class="feature-item-card">
              <span style="color: var(--blue); font-weight: 700;">✓</span>
              <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);"><?=htmlspecialchars($f)?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

      </div>

      <!-- Key Benefits Section (Horizontal 3-column cards grid) -->
      <?php $benefits = json_decode($service['benefits']??'[]', true); ?>
      <?php if(!empty($benefits)): ?>
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 20px; font-family: 'Poppins', sans-serif;">Key Benefits</h2>
        
        <?php
        $benefit_details = [
          'High data accuracy' => 'Improve data quality and reduce database processing errors.',
          'Reduced bounce rates' => 'Ensure your marketing campaigns reach the right target audience.',
          'Optimized marketing spend' => 'Better validated data translates to higher ROI on marketing lists.',
          'Safe community environment' => 'Protect your user base from toxic content and spam 24/7.',
          'Stronger brand protection' => 'Shield your reputation by filtering user submissions in real-time.',
          '24/7 moderation coverage' => 'Continuous moderation across multiple time zones and languages.',
          'Higher conversion' => 'Turn prospects into customers with targeted campaigns.',
          'Increased visibility' => 'Gain brand traction and rank higher on organic search results.',
          'Better ROI' => 'Maximize conversion value from advertising and SEO budgets.',
          'Custom designs' => 'Custom-coded platforms tailored specifically to your workflows.',
          'Scalable architecture' => 'Robust framework setups ready to support millions of active users.',
          'Dedicated support' => 'Round-the-clock maintenance to resolve issues instantly.'
        ];
        ?>
        
        <div class="benefits-grid">
          <?php foreach($benefits as $b): 
            $desc = $benefit_details[trim($b)] ?? 'Unlock operational efficiencies and high quality delivery standards.';
          ?>
          <div class="benefit-card">
            <div class="benefit-icon">
              <span>✓</span>
            </div>
            <div>
              <h4 style="font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px;"><?=htmlspecialchars($b)?></h4>
              <p style="font-size: 12px; color: #64748b; line-height: 1.6; margin: 0;"><?=htmlspecialchars($desc)?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Bottom CTA Banner Card -->
      <div class="cta-banner-card">
        <div class="cta-banner-text">
          <div class="cta-banner-icon">
            <span>📞</span>
          </div>
          <div>
            <h4 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px;">Need a custom solution? Contact our experts today.</h4>
            <p style="font-size: 13px; color: #64748b; margin: 0;">Our directors are ready to draft a custom operations strategy for your business.</p>
          </div>
        </div>
        <a href="/contact.php" class="cta-btn" style="margin-top: 0; padding: 12px 28px;">
          Get a Free Consultation &nbsp;➔
        </a>
      </div>

    </div>

    <!-- RIGHT SIDEBAR COLUMN -->
    <div style="width: 300px; flex-shrink: 0;" class="w-full lg:w-[300px]">
      <?php require_once 'includes/service-sidebar.php'; ?>
    </div>

  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
