<?php
require_once 'includes/db.php';

$cm = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE slug='content-moderation'");
        $stmt->execute();
        $cm = $stmt->fetch();
    } catch(Exception $e) {
        error_log('Content moderation fetch error: ' . $e->getMessage());
    }
}
if (!$cm) {
    $cm = [
        'name' => 'Content Moderation',
        'intro' => "Protect your brand reputation and build user trust with our global content moderation services. We monitor and filter text, images, and videos 24/7.",
        'full_content' => "Clevora takes its technological capabilities very seriously and has acquired all the technologies that are required for running a successful call center in this competitive market segment.\n\nOur Content Moderation services ensure your website or application remains safe, clean, and welcoming for all users. We monitor and filter user-generated text, image, and video content 24 hours a day, 7 days a week. We combine automated systems with experienced human moderators to check compliance, block toxic or illegal materials, and shield your brand from unwanted exposure.\n\nWe provide global moderation across languages to help platforms monitor comments, check reviews, verify profiles, and watch live streams. Our teams operate in real-time, working closely with your operations directors to enforce guidelines consistently."
    ];
}

$pageTitle = 'Content Moderation Services | Clevora';
$metaDesc = 'Secure, compliant, and scaleable content moderation services. Clean user-generated text, images, and videos 24/7.';
$currentSlug = 'content-moderation';

// Layout variables for page banner
$pageBannerTitle = 'Content Moderation';
$pageBannerBreadcrumb = 'Content Moderation';

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
          
          <img src="/assets/images/content-mod.jpg" alt="Content Moderation Team" style="width: 220px; height: auto; object-fit: contain;">
        </div>

        <!-- Right Sub-column: Details -->
        <div class="service-details">
          <span style="display: inline-block; color: var(--blue); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            Safe. Clean. Trusted.
          </span>
          <h2 style="font-size: 28px; font-weight: 700; color: var(--text-dark); margin-bottom: 16px; font-family: 'Poppins', sans-serif;">
            Content Moderation Solutions
          </h2>
          
          <p style="font-size: 14px; color: #64748b; line-height: 1.8; margin-bottom: 24px;">
            <?=nl2br(htmlspecialchars($cm['full_content'] ?? $cm['intro']))?>
          </p>

          <!-- Features Section (Grid of Cards) -->
          <?php 
          $features = [];
          if (!empty($cm['features'])) {
              $features = json_decode($cm['features'], true);
          }
          if (empty($features)) {
              $features = ["Image Moderation", "Video Moderation", "Text & Comment Filtering", "Profile Verification"];
          }
          ?>
          <h3 style="font-size: 13px; font-weight: 800; color: var(--text-dark); margin-bottom: 12px; text-transform: uppercase; letter-spacing: .06em;">Key Features</h3>
          <div class="features-grid">
            <?php foreach($features as $f): ?>
            <div class="feature-item-card">
              <span style="color: var(--blue); font-weight: 700;">✓</span>
              <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);"><?=htmlspecialchars($f)?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Key Benefits Section (Horizontal 3-column cards grid) -->
      <?php 
      $benefits = [];
      if (!empty($cm['benefits'])) {
          $benefits = json_decode($cm['benefits'], true);
      }
      if (empty($benefits)) {
          $benefits = ["Safe community environment", "Stronger brand protection", "24/7 moderation coverage"];
      }
      ?>
      <div style="margin-bottom: 48px;">
        <h2 style="font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 20px; font-family: 'Poppins', sans-serif;">Key Benefits</h2>
        
        <?php
        $benefit_details = [
          'Safe community environment' => 'Protect your user base from toxic content, hate speech, and spam 24/7.',
          'Stronger brand protection' => 'Shield your brand reputation by filtering offensive user submissions in real-time.',
          '24/7 moderation coverage' => 'Continuous global moderation across multiple time zones and languages.',
          'Community safety' => 'Ensure a healthy online forum by enforcing strict community standard guidelines.',
          'Brand shield' => 'Maintain user trust and safeguard your brand name from toxic publications.',
          'Full 24/7 coverage' => 'Constant automated and manual review cycles operating round-the-clock.'
        ];
        ?>
        
        <div class="benefits-grid">
          <?php foreach($benefits as $b): 
            $desc = $benefit_details[trim($b)] ?? 'Protect your user experience and maintain community guidelines.';
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
