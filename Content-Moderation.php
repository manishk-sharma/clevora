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

<div style="max-width:1200px; margin:0 auto; padding: 24px;" class="px-4">
  <div style="display:flex; gap:32px; align-items:start; flex-wrap: wrap;" class="flex-col md:flex-row">
    <!-- Left: content -->
    <main style="flex:1; min-width:0; background:#fff;" class="w-full space-y-6">

      <!-- Service image -->
      <img src="/assets/images/content-mod.jpg" alt="Content Moderation Team"
           style="width:100%; max-width:480px; border-radius:14px;
                  border:1px solid #e8eaf0; margin-bottom:22px;">

      <h2 style="font-size:22px; font-weight:700; color:#0f172a;
                 margin-bottom:12px; font-family:'Poppins',sans-serif;">
        Content Moderation & Brand Protection Solutions
      </h2>

      <!-- Long form content from DB -->
      <div style="font-size:13px; color:#4b5563; line-height:1.9; whitespace: pre-line;">
        <?=nl2br(htmlspecialchars($cm['full_content']))?>
      </div>

      <!-- Bottom CTA -->
      <div style="background:linear-gradient(135deg,#1a1a2e,#1e3a5f);
                  border-radius:12px; padding:22px; text-align:center;">
        <p style="font-size:13px; color:#93c5fd; margin-bottom:12px;">
          Need a custom solution? Contact our experts today.
        </p>
        <a href="/contact.php"
           style="display:inline-block; background:#2563eb; color:#fff;
                  padding:10px 24px; border-radius:8px; font-size:13px;
                  font-weight:600; text-decoration:none; transition:background .2s;"
           onmouseover="this.style.background='#1d4ed8'"
           onmouseout="this.style.background='#2563eb'">
          Get a Free Consultation
        </a>
      </div>
    </main>

    <!-- Right: sidebar -->
    <?php require_once 'includes/service-sidebar.php'; ?>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
