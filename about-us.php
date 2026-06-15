<?php
$pageTitle = 'About Us | Clevora | Global Outsourcing Services';
$metaDesc = 'Learn about Clevora Global Outsourcing Services, established in 2011. Discover our mission, vision, history, and founder.';

$pageBannerTitle = 'ABOUT US';
$pageBannerBreadcrumb = 'About Us';

require_once 'includes/header.php';
include 'includes/page-banner.php';

$history = setting('about_full_history', $pdo);
$mission = setting('about_mission', $pdo);
$vision  = setting('about_vision', $pdo);
$founder_name = setting('management_founder_name', $pdo);
$founder_role = setting('management_founder_role', $pdo);
$founder_bio  = setting('management_founder_bio', $pdo);
?>

<section class="section">
<div class="container">
  <!-- Section 1: Company Profile -->
  <div class="content-grid">
    <div>
      <span class="section-kicker">Our Journey</span>
      <h2 class="section-title">Our History & Growth</h2>
      <p class="section-copy">
        <?= nl2br(htmlspecialchars($history)) ?>
      </p>
    </div>
    <div class="media-frame">
      <img src="/assets/images/about-home.jpg" alt="Clevora Growth">
    </div>
  </div>
</div>
</section>

<section class="section section--soft">
<div class="container">
  <!-- Section 2: Mission & Vision -->
  <div class="content-grid">
    <!-- Mission Card -->
    <div class="card feature-card">
      <div class="feature-card__icon">M</div>
      <h3>Our Mission</h3>
      <p><?= htmlspecialchars($mission) ?></p>
    </div>
    <!-- Vision Card -->
    <div class="card feature-card">
      <div class="feature-card__icon">V</div>
      <h3>Our Vision</h3>
      <p><?= htmlspecialchars($vision) ?></p>
    </div>
  </div>
</div>
</section>

<section class="section">
<div class="container">
  <!-- Section 3: Founder & Management -->
  <div class="card leadership-card">
    <!-- Section header -->
    <div class="section-head">
      <span class="section-kicker">Executive Leadership</span>
      <h2 class="section-title">Our Management Team</h2>
    </div>

    <div class="leadership-card__body">
      <!-- Founder Photo -->
      <div class="leadership-card__profile">
        <div class="leadership-card__avatar">
          <img src="/assets/images/founder.jpg" alt="<?= htmlspecialchars($founder_name) ?>">
        </div>
        <h4><?= htmlspecialchars($founder_name) ?></h4>
        <p><?= htmlspecialchars($founder_role) ?></p>
      </div>
      <!-- Founder Bio -->
      <div class="leadership-card__message">
        <h4>Message from Founder</h4>
        <p>
          <?= htmlspecialchars($founder_bio) ?>
        </p>
      </div>
    </div>
  </div>
</div>
</section>

<?php require_once 'includes/footer.php'; ?>
