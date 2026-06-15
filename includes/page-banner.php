<?php
// Usage: set $pageBannerTitle and $pageBannerBreadcrumb before including
// e.g. $pageBannerTitle = 'Content Moderation Services';
//      $pageBannerBreadcrumb = 'Content Moderation Services';
?>
<section class="page-banner">
  <div class="page-banner__inner">
    <p class="page-banner__kicker">Clevora Global Operations</p>
    <h1><?= htmlspecialchars($pageBannerTitle ?? 'Page Title') ?></h1>
    <p class="page-banner__crumbs">
      <a href="/">Home</a>
      <span>/</span>
      <?= htmlspecialchars($pageBannerBreadcrumb ?? '') ?>
    </p>
  </div>
</section>
