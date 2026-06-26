<?php
require_once __DIR__ . '/../includes/db.php';

// Detect current page slug
$cur_file = basename($_SERVER['PHP_SELF']);
$slug_map = [
    'index.php' => 'home',
    'about-us.php' => 'about',
    'services.php' => 'services',
    'detail-services.php' => 'services',
    'technology.php' => 'technology',
    'album.php' => 'gallery',
    'clients.php' => 'clients',
    'career.php' => 'careers',
    'contact.php' => 'contact'
];
$detected_slug = $slug_map[$cur_file] ?? '';

$db_seo = null;
if ($detected_slug && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM seo_settings WHERE page_slug = ?");
        $stmt->execute([$detected_slug]);
        $db_seo = $stmt->fetch();
    } catch (Exception $e) {
        error_log('SEO fetch failed: ' . $e->getMessage());
    }
}

// Fallback logic
$title_val = !empty($db_seo['meta_title']) ? $db_seo['meta_title'] : ($pageTitle ?? 'Clevora | Global BPO & Outsourcing Solutions');
$desc_val = !empty($db_seo['meta_description']) ? $db_seo['meta_description'] : ($metaDesc ?? 'Clevora provides BPO, content moderation, digital marketing and outsourcing services from Delhi, India.');
$keywords_val = !empty($db_seo['keywords']) ? $db_seo['keywords'] : ($metaKeywords ?? '');
$og_image_val = !empty($db_seo['og_image']) ? $db_seo['og_image'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title_val) ?></title>
  <meta name="description" content="<?= htmlspecialchars($desc_val) ?>">
  <?php if ($keywords_val): ?>
  <meta name="keywords" content="<?= htmlspecialchars($keywords_val) ?>">
  <?php endif; ?>
  <?php if ($og_image_val): ?>
  <meta property="og:image" content="<?= htmlspecialchars(SITE_URL . $og_image_val) ?>">
  <?php endif; ?>
  <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.png">
  <link rel="icon" type="image/png" href="/assets/images/logo.png">
  <link rel="shortcut icon" type="image/png" href="/assets/images/logo.png">
  <link rel="apple-touch-icon" href="/assets/images/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap">
  <link rel="stylesheet" href="/assets/css/custom.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: '#1a1a2e',
            navyMid: '#1e3a5f',
            brandBlue: '#2563eb',
            brandOrange: '#f97316'
          }
        }
      }
    }
  </script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script defer src="/assets/js/main.js"></script>
</head>
<body x-data="{open:false}">



<nav class="site-header">

  <!-- Logo -->
  <a href="/" class="site-header__brand" aria-label="Clevora home">
    <img src="/assets/images/logo.png" alt="Clevora">
  </a>

  <!-- Desktop links -->
  <div class="site-header__links hidden md:flex">
    <?php
    $nav = [
      'HOME'               => '/',
      'ABOUT US'           => '/about-us.php',
      'SERVICES'           => '/services.php',      
      'TECHNOLOGY'         => '/technology.php',
      'OUR GALLERY'        => '/album.php',
      'CLIENTS'            => '/clients.php',
      'CAREERS'            => '/career.php',
      'CONTACT US'         => '/contact.php',
    ];
    $cur = basename($_SERVER['PHP_SELF']);
    foreach($nav as $label => $href):
      $active = (basename($href)===$cur || ($href==='/'&&$cur==='index.php'));
    ?>
    <a href="<?=$href?>" class="<?= $active ? 'is-active' : '' ?>">
      <?=$label?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Mobile hamburger -->
  <button @click="open=!open" class="site-header__menu md:hidden" aria-label="Open navigation">☰</button>

</nav>

  <!-- Mobile overlay -->
  <div x-show="open" x-transition
       class="site-mobile-nav" style="display: none;">
    <button @click="open=false"
            class="site-mobile-nav__close" aria-label="Close navigation">x</button>
    <?php foreach($nav as $label => $href): ?>
    <a href="<?=$href?>" @click="open=false">
      <?=$label?>
    </a>
    <?php endforeach; ?>
  </div>
