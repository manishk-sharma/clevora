<?php
require_once __DIR__ . '/../includes/db.php';
if(!isset($pageTitle)) $pageTitle = 'Clevora | Global BPO & Outsourcing Solutions';
if(!isset($metaDesc))  $metaDesc  = 'Clevora provides BPO, content moderation, digital marketing and outsourcing services from Delhi, India.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>">
  <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
  <link rel="icon" type="image/png" href="/assets/images/logo.png">
  <link rel="shortcut icon" type="image/png" href="/assets/images/logo.png">
  <link rel="apple-touch-icon" href="/assets/images/logo.png">
  <link rel="stylesheet" href="/assets/css/custom.css">
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
<body>

<?php include 'topbar.php'; ?>

<nav class="site-header"
     x-data="{open:false}">

  <!-- Logo -->
  <a href="/" class="site-header__brand" aria-label="Clevora home">
    <img src="/assets/images/logo.png" alt="Clevora">
    <span>
      <strong>Clevora</strong>
      <small>Global Outsourcing</small>
    </span>
  </a>

  <!-- Desktop links -->
  <div class="site-header__links hidden md:flex">
    <?php
    $nav = [
      'HOME'               => '/',
      'ABOUT US'           => '/about-us.php',
      'SERVICES'           => '/services.php',
      'CONTENT MODERATION' => '/Content-Moderation.php',
      'TECHNOLOGY'         => '/technology.php',
      'CLIENTS'            => '/clients.php',
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

  <!-- Mobile overlay -->
  <div x-show="open" x-transition
       class="site-mobile-nav">
    <button @click="open=false"
            class="site-mobile-nav__close" aria-label="Close navigation">x</button>
    <?php foreach($nav as $label => $href): ?>
    <a href="<?=$href?>" @click="open=false">
      <?=$label?>
    </a>
    <?php endforeach; ?>
  </div>
</nav>
