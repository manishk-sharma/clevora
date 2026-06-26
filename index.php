<?php
$pageTitle = 'Clevora | Global BPO, Customer Experience & Digital Operations Partner';
$metaDesc  = 'Clevora provides secure outsourcing solutions including customer support, content operations, data management, HR, finance, e-commerce support and BPO services worldwide.';
require_once 'includes/header.php';

$hero_headline = setting('hero_headline', $pdo);
$hero_bullets  = explode("\n", setting('hero_bullets', $pdo));
$hero_cta      = setting('hero_cta_text', $pdo);
$about_text    = setting('about_home_text', $pdo);

$services = [];
$gallery = [];
$testimonials = [];
$clients = [];
$partners_list = [];
$solutions_list = [];
$steps_list = [];

if ($pdo) {
    try {
        $gallery  = $pdo->query("SELECT * FROM gallery ORDER BY sort_order LIMIT 6")->fetchAll();
        $testimonials = $pdo->query("SELECT * FROM testimonials WHERE is_active=1")->fetchAll();
        $clients  = $pdo->query("SELECT * FROM clients WHERE status=1 ORDER BY sort_order ASC, id DESC")->fetchAll();
        $partners_list = $pdo->query("SELECT * FROM home_partners WHERE is_active=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
        $solutions_list = $pdo->query("SELECT * FROM home_solutions WHERE is_active=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
        $steps_list = $pdo->query("SELECT * FROM home_process_steps WHERE is_active=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
    } catch(Exception $e) {
        error_log('Homepage fetch error: ' . $e->getMessage());
    }
}

if (empty($gallery)) {
    $gallery = [
        ['image_url' => '/assets/images/gallery-1.jpg', 'caption' => 'Our Modern Workspace'],
        ['image_url' => '/assets/images/gallery-2.jpg', 'caption' => 'Server Room & Infrastructure'],
        ['image_url' => '/assets/images/gallery-3.jpg', 'caption' => 'Team Collaboration Session']
    ];
}

if (empty($testimonials)) {
    $testimonials = [
        ['name' => 'Rahul Kumar Gupta', 'location' => 'Karnal, India', 'photo_url' => '/assets/images/testimonial-1.jpg', 'quote' => 'This is very satisfied that Clevora provides us with great satisfaction and we are extremely happy with their operations.'],
        ['name' => 'Patrick John', 'location' => 'California, USA', 'photo_url' => '/assets/images/testimonial-2.jpg', 'quote' => 'I am most satisfied customer after working with clevora. Their round-the-clock availability is amazing.'],
        ['name' => 'John Andrea', 'location' => 'Berlin, Germany', 'photo_url' => '/assets/images/testimonial-3.jpg', 'quote' => 'We have been running outsourcing through Clevora for some time now. Their professional multilingual team handles our tickets efficiently.']
    ];
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

$stats = [
  ['value'=>setting('stats_projects',$pdo),   'label'=>'Projects Completed'],
  ['value'=>setting('stats_industries',$pdo), 'label'=>'Industries Supported'],
  ['value'=>setting('stats_resumes',$pdo),    'label'=>'Processes Managed'],
  ['value'=>setting('stats_clients',$pdo),    'label'=>'Client Experiences Delivered'],
];

// Fetch hero sliders dynamically
$hero_slides = [];
if ($pdo) {
    try {
        $db_slides = $pdo->query("SELECT * FROM hero_sliders WHERE status=1 ORDER BY sort_order ASC, id DESC")->fetchAll();
        foreach ($db_slides as $s) {
            $bullets = json_decode($s['bullets'] ?? '[]', true);
            if (!is_array($bullets)) {
                $bullets = [];
            }
            $hero_slides[] = [
                'eyebrow' => $s['small_heading'],
                'title' => $s['main_heading'],
                'quote' => $s['description'],
                'image' => $s['image'],
                'bullets' => $bullets,
                'cta_text' => $s['cta_text'],
                'cta_link' => $s['cta_link'],
                'media_type' => $s['media_type'] ?? 'image',
                'media_file' => $s['media_file'] ?? $s['image'],
                'video_poster' => $s['video_poster'] ?? ''
            ];
        }
    } catch (Exception $e) {
        error_log('Hero slides fetch failed: ' . $e->getMessage());
    }
}

if (empty($hero_slides)) {
    $hero_slides = [
      [
        'eyebrow' => 'SCALABLE OUTSOURCING SOLUTIONS',
        'title' => 'Customer Experience (CX)',
        'quote' => 'Proactive 24/7 customer engagement<br>across channels.',
        'image' => '/assets/images/hero-bg.jpg',
        'bullets' => [
          'Inbound and outbound customer care support',
          'Fluent multilingual support agents',
          'SLA-backed email and live chat support',
          'Technical helpdesk and troubleshooting support',
        ],
        'cta_text' => 'Contact Us',
        'cta_link' => '/contact.php'
      ],
      [
        'eyebrow' => 'TRUST & SAFETY SOLUTIONS',
        'title' => 'Content Moderation & Operations',
        'quote' => 'Protecting your brand and users<br>round the clock.',
        'image' => '/assets/images/content-mod.jpg',
        'bullets' => [
          'Live streaming content moderation',
          'Video and image review services',
          'Social media review and comments moderation',
          'Compliance and policy enforcement',
        ],
        'cta_text' => 'Contact Us',
        'cta_link' => '/contact.php'
      ],
      [
        'eyebrow' => 'SECURE PROCESS MANAGEMENT',
        'title' => 'Data Operations & Back Office',
        'quote' => 'Highly accurate and secure database<br>processing.',
        'image' => '/assets/images/service-banner.jpg',
        'bullets' => [
          'Data entry and transcription services',
          'Database standardisation and cleaning',
          'Bookkeeping and accounts support',
          'Administrative process management',
        ],
        'cta_text' => 'Contact Us',
        'cta_link' => '/contact.php'
      ],
      [
        'eyebrow' => 'GLOBAL SUPPORT OPERATIONS',
        'title' => 'E-Commerce Support Operations',
        'quote' => 'Powering your storefront operations<br>seamlessly.',
        'image' => '/assets/images/hero-office.jpg',
        'bullets' => [
          'Order processing and shipment tracking support',
          'Product listing and catalog uploads',
          'Returns, refunds and exchange management',
          'Multi-marketplace store support',
        ],
        'cta_text' => 'Contact Us',
        'cta_link' => '/contact.php'
      ],
    ];
}

if (empty($partners_list)) {
    $partners_list = [
        ['logo' => '/assets/images/client-1.png', 'company_name' => 'Client One'],
        ['logo' => '/assets/images/client-2.png', 'company_name' => 'Client Two'],
        ['logo' => '/assets/images/client-3.png', 'company_name' => 'Client Three'],
        ['logo' => '/assets/images/client-4.png', 'company_name' => 'Client Four'],
        ['logo' => '/assets/images/client-5.png', 'company_name' => 'Client Five'],
        ['logo' => '/assets/images/client-6.png', 'company_name' => 'Client Six']
    ];
}

if (empty($solutions_list)) {
    $solutions_list = [
        ['title' => 'Customer Support Services', 'description' => 'Multilingual inbound/outbound support, email and live chat operations with SLAs tailored to keep customer satisfaction high.', 'icon' => '💬', 'button_text' => 'Explore Solutions', 'button_link' => '/services.php?category=customer-support-services'],
        ['title' => 'Content & Moderation Services', 'description' => '24/7 video, audio, image and social media review moderation. Keep your application community and brand reputation safe.', 'icon' => '🛡️', 'button_text' => 'Explore Solutions', 'button_link' => '/services.php?category=content-moderation-services'],
        ['title' => 'E-Commerce Support', 'description' => 'Optimize store operations: catalog product uploads, order tracking coordination, returns and marketplace support.', 'icon' => '🛒', 'button_text' => 'Explore Solutions', 'button_link' => '/services.php?category=e-commerce-support'],
        ['title' => 'Back Office & Data Management', 'description' => 'High speed data entry, processing, and standardisation solutions to keep corporate records accurate and accessible.', 'icon' => '📂', 'button_text' => 'Explore Solutions', 'button_link' => '/services.php?category=back-office-data-management'],
        ['title' => 'Finance & Accounting', 'description' => 'Automate billing support, accounts receivable/payable, expense reconciliations and payroll auditing.', 'icon' => '💳', 'button_text' => 'Explore Solutions', 'button_link' => '/services.php?category=finance-accounting'],
        ['title' => 'Recruitment & HR Services', 'description' => 'Recruitment process outsourcing (RPO), resume screening, talent sourcing and HR operations administration.', 'icon' => '👥', 'button_text' => 'Explore Solutions', 'button_link' => '/services.php?category=hr-solutions']
    ];
}

if (empty($steps_list)) {
    $steps_list = [
        ['step_number' => 1, 'title' => 'Tell Us What You Need', 'description' => "Share your business challenges, goals and outsourcing requirements."],
        ['step_number' => 2, 'title' => 'We Deploy Your Team', 'description' => "Our experts create a customized support process for your company."],
        ['step_number' => 3, 'title' => 'Scale & Grow', 'description' => "Focus on growth while Clevora manages your operations."]
    ];
}

// Fetch active service categories
$categories_list = [];
if ($pdo) {
    try {
        $categories_list = $pdo->query("SELECT * FROM service_categories WHERE is_active=1 ORDER BY sort_order ASC, name ASC LIMIT 6")->fetchAll();
    } catch (Exception $e) {
        error_log('Frontend categories fetch failed: ' . $e->getMessage());
    }
}
if (empty($categories_list)) {
    $categories_list = [
        ['name' => 'Customer Support', 'slug' => 'customer-support-services', 'description' => 'Multilingual inbound/outbound support, email and live chat operations with SLAs tailored to keep customer satisfaction high.', 'icon' => '💬'],
        ['name' => 'Content Moderation', 'slug' => 'content-moderation-services', 'description' => '24/7 video, audio, image and social media review moderation. Keep your application community and brand reputation safe.', 'icon' => '🛡️'],
        ['name' => 'Data Management', 'slug' => 'back-office-data-management', 'description' => 'High speed data entry, processing, and standardisation solutions to keep corporate records accurate and accessible.', 'icon' => '📂'],
        ['name' => 'E-Commerce Support', 'slug' => 'e-commerce-support', 'description' => 'Optimize store operations: catalog product uploads, order tracking coordination, returns and marketplace support.', 'icon' => '🛒'],
        ['name' => 'Finance Operations', 'slug' => 'finance-accounting', 'description' => 'Automate billing support, accounts receivable/payable, expense reconciliations and payroll auditing.', 'icon' => '💳'],
        ['name' => 'HR Solutions', 'slug' => 'hr-solutions', 'description' => 'Recruitment process outsourcing (RPO), resume screening, talent sourcing and HR operations administration.', 'icon' => '👥']
    ];
}

// Fetch Why Choose Us
$why_choose = [];
if ($pdo) {
    try {
        $db_why = $pdo->query("SELECT * FROM homepage_sections WHERE section_type='why_choose' AND status=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
        foreach ($db_why as $w) {
            $why_choose[] = [
                'title' => $w['title'],
                'description' => $w['description'],
                'icon' => $w['icon']
            ];
        }
    } catch (Exception $e) {
        error_log('Why Choose fetch failed: ' . $e->getMessage());
    }
}
if (empty($why_choose)) {
    $why_choose = [
        ['title' => 'QUALIFIED EXPERTS', 'description' => 'Certified professionals with domain-specific training across every service vertical.', 'icon' => 'fa-graduation-cap'],
        ['title' => 'WORKMANSHIP QUALITY', 'description' => 'Multi-tier QA ensures output with less than 0.5% error rate on every delivery.', 'icon' => 'fa-certificate'],
        ['title' => 'FLEXIBLE SCHEDULE', 'description' => '24/7/365 operations adapted to your time zone and business requirements.', 'icon' => 'fa-clock'],
        ['title' => 'AFFORDABLE PACKAGES', 'description' => 'Enterprise-grade output at SME-friendly pricing with transparent SLAs.', 'icon' => 'fa-coins'],
        ['title' => 'DATA SECURITY', 'description' => 'ISO-aligned protocols, NDAs, and GDPR-aware data handling by default.', 'icon' => 'fa-shield-halved'],
        ['title' => 'WORK ETHICS', 'description' => 'Dedicated account managers and a customer-first culture in everything we do.', 'icon' => 'fa-users']
    ];
}

// Fetch Industries
$industries_sec = [];
if ($pdo) {
    try {
        $db_ind = $pdo->query("SELECT * FROM homepage_sections WHERE section_type='industry' AND status=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
        foreach ($db_ind as $ind) {
            $industries_sec[] = [
                'title' => $ind['title'],
                'description' => $ind['description'],
                'icon' => $ind['icon']
            ];
        }
    } catch (Exception $e) {
        error_log('Industries fetch failed: ' . $e->getMessage());
    }
}
if (empty($industries_sec)) {
    $industries_sec = [
        ['title' => 'Gaming & Entertainment', 'description' => 'Content moderation, QA testing, player support', 'icon' => 'fa-gamepad', 'bg' => 'rgba(37,99,235,0.1)', 'color' => '#2563eb'],
        ['title' => 'Education', 'description' => 'Admissions processing, student records, LMS data', 'icon' => 'fa-graduation-cap', 'bg' => 'rgba(239,68,68,0.1)', 'color' => '#ef4444'],
        ['title' => 'Retail & E-commerce', 'description' => 'Product listings, order processing, catalog ops', 'icon' => 'fa-cart-shopping', 'bg' => 'rgba(6,182,212,0.1)', 'color' => '#06b6d4'],
        ['title' => 'Hospitality', 'description' => 'Reservations, guest data, multi-property ops', 'icon' => 'fa-hotel', 'bg' => 'rgba(59,130,246,0.1)', 'color' => '#3b82f6'],
        ['title' => 'Staffing & HR', 'description' => 'Resume screening, ATS management, onboarding', 'icon' => 'fa-address-card', 'bg' => 'rgba(236,72,153,0.1)', 'color' => '#ec4899'],
        ['title' => 'Healthcare', 'description' => 'Claims processing, patient records, compliance', 'icon' => 'fa-briefcase-medical', 'bg' => 'rgba(20,184,166,0.1)', 'color' => '#14b8a6'],
        ['title' => 'Real Estate', 'description' => 'Listings management, lead processing, CRM data', 'icon' => 'fa-house', 'bg' => 'rgba(79,70,229,0.1)', 'color' => '#4f46e5'],
        ['title' => 'Financial Services', 'description' => 'Transaction processing, reconciliation, reporting', 'icon' => 'fa-coins', 'bg' => 'rgba(245,158,11,0.1)', 'color' => '#f59e0b']
    ];
}

// Fetch FAQs
$faqs = [];
if ($pdo) {
    try {
        $db_faqs = $pdo->query("SELECT * FROM homepage_sections WHERE section_type='faq' AND status=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
        foreach ($db_faqs as $f) {
            $faqs[] = ['q' => $f['title'], 'a' => $f['description']];
        }
    } catch (Exception $e) {
        error_log('FAQ fetch failed: ' . $e->getMessage());
    }
}
if (empty($faqs)) {
    $faqs = [
        ['q' => 'What outsourcing services does Clevora offer?', 'a' => 'Clevora provides customer support, call center operations, content moderation, e-commerce support, finance, HR, data management and KPO solutions.'],
        ['q' => 'How quickly can you deploy a team?', 'a' => 'Deployment depends on requirements, team size and process complexity. Clevora builds flexible teams designed around client needs.'],
        ['q' => 'How much can outsourcing save?', 'a' => 'Savings vary by operation type, but outsourcing helps reduce hiring, infrastructure and management costs.'],
        ['q' => 'How do you ensure data security?', 'a' => 'We follow controlled access practices, secure workflows and confidentiality-focused operational processes.'],
        ['q' => 'Can I scale services?', 'a' => 'Yes. Teams and processes can expand or adjust according to changing business requirements.'],
        ['q' => 'Where are your teams located?', 'a' => 'Our operations are based in Delhi, India, supporting clients globally.']
    ];
}
?>
<section class="hero-slider" aria-labelo="Clevora services">
  <div class="hero-slider__viewport">
    <?php foreach($hero_slides as $idx => $slide): ?>
    <article class="hero-slide <?= $idx === 0 ? 'is-active' : '' ?>" data-hero-slide>
      <?php if (($slide['media_type'] ?? 'image') === 'video'): ?>
        <video class="hero-slide__image" autoplay muted loop playsinline preload="metadata" poster="<?= htmlspecialchars($slide['video_poster'] ?? '') ?>">
          <source src="<?= htmlspecialchars($slide['media_file'] ?? '') ?>" type="video/<?= htmlspecialchars(pathinfo($slide['media_file'] ?? '', PATHINFO_EXTENSION)) ?>">
        </video>
      <?php else: ?>
        <img class="hero-slide__image" src="<?= htmlspecialchars($slide['image']) ?>" alt="" aria-hidden="true">
      <?php endif; ?>
      <div class="hero-slide__overlay"></div>
      <div class="hero-slide__content">
        <span class="hero-slide__eyebrow"><?= htmlspecialchars($slide['eyebrow']) ?></span>
        <h1><?= htmlspecialchars($slide['title']) ?></h1>
        <p class="hero-slide__quote">&ldquo;<?= $slide['quote'] ?>&rdquo;</p>
        <ul class="hero-slide__list">
          <?php foreach($slide['bullets'] as $bullet): ?>
          <li><?= htmlspecialchars($bullet) ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="hero-slide__actions">
          <a class="hero-btn hero-btn--primary" href="<?= htmlspecialchars($slide['cta_link'] ?: '/contact.php') ?>"><?= htmlspecialchars($slide['cta_text'] ?: 'Contact Us') ?></a>
          <a class="hero-btn hero-btn--ghost" href="<?= htmlspecialchars(($slide['secondary_cta_link'] ?? '') ?: '/services.php') ?>"><?= htmlspecialchars(($slide['secondary_cta_text'] ?? '') ?: 'Explore Solutions') ?></a>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <button class="hero-slider__arrow hero-slider__arrow--prev" type="button" data-hero-prev aria-label="Previous hero slide">
    <span aria-hidden="true"></span>
  </button>
  <button class="hero-slider__arrow hero-slider__arrow--next" type="button" data-hero-next aria-label="Next hero slide">
    <span aria-hidden="true"></span>
  </button>

  <div class="hero-slider__dots" aria-label="Hero slide controls">
    <?php foreach($hero_slides as $idx => $slide): ?>
    <button class="<?= $idx === 0 ? 'is-active' : '' ?>" type="button" data-hero-dot="<?= $idx ?>" aria-label="Show <?= htmlspecialchars($slide['title']) ?> slide"></button>
    <?php endforeach; ?>
  </div>
</section>

<!-- ─── PARTNERS / CLIENTS GRID (Partnerships Marquee) ── -->
<section style="background:#fff; padding:64px 24px; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
  <div style="max-width:1200px; margin:0 auto;" class="space-y-12">
    <!-- Header -->
    <div style="text-align:center; margin-bottom:40px;">
      <span style="display:inline-block; background:#eff6ff; color:#2563eb;
                   font-size:11px; font-weight:700; padding:6px 16px;
                   border-radius:9999px; letter-spacing:1px; margin-bottom:16px; text-transform:uppercase;">
        Partnerships
      </span>
      <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
        Trusted By Organizations Globally
      </h2>
      <p style="font-size:14px; color:#6b7280; max-width:500px; margin:0 auto; line-height:1.6;">
        We provide backend processes and calling solutions to industry leaders.
      </p>
    </div>

    <!-- Logos Infinite Marquee -->
    <div style="width:100%; overflow:hidden; position:relative; padding:10px 0;">
      <div class="marquee-track" style="display:flex; gap:24px; width:max-content;" onmouseover="this.style.animationPlayState='paused'" onmouseout="this.style.animationPlayState='running'">
        <?php 
        // Duplicate the logo list to create a seamless looping effect
        $marquee_items = array_merge($partners_list, $partners_list);
        foreach($marquee_items as $p): 
        ?>
        <div style="background:#fff; border:1px solid #e8eaf0; border-radius:16px; padding:24px;
                    display:flex; align-items:center; justify-content:center; height:100px; width:180px; flex-shrink:0;
                    box-shadow:0 4px 20px rgba(0,0,0,0.01); transition:all 0.3s;"
             onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.05)';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.01)';this.style.transform='none'">
          <?php if (!empty($p['logo'])): ?>
            <img src="<?= htmlspecialchars($p['logo']) ?>" alt="<?= htmlspecialchars($p['company_name'] ?? 'Client') ?>" loading="lazy" style="max-height:100%; max-width:100%; object-fit:contain; filter:grayscale(100%); transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(100%)'">
          <?php else: ?>
            <span style="color:#6b7280; font-weight:600; font-size:11px; text-transform:uppercase;"><?= htmlspecialchars($p['company_name'] ?? 'Client') ?></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>


<!-- ─── STATS STRIP ──────────────────────────────────── -->
<section style="background:#f8f9fc; padding:80px 24px; position:relative; overflow:hidden;">
  <!-- Globe pattern background -->
  <div style="position:absolute; inset:0; opacity:0.25; background-image:url('/assets/images/empBusiness.png'); background-size:cover; background-position:center; background-repeat:no-repeat; filter:brightness(0); z-index:0;"></div>
  
  <div style="max-width:1100px; margin:0 auto; position:relative; z-index:1;">
    <div style="text-align:center; margin-bottom:64px;">
      <h2 style="font-size:32px; font-weight:600; color:var(--blue); margin-bottom:12px; font-family:'Poppins', sans-serif;">
        Empowering Your Business with Expert Solutions
      </h2>
      <p style="font-size:16px; color:var(--text-muted);">
        Driving Growth Through Smarter Support.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 items-center">
      <?php foreach($stats as $idx => $st): ?>
      <div style="text-align:center; position:relative;">
        <h3 style="font-size:42px; font-weight:700; color:var(--text-dark); margin-bottom:8px; display:flex; align-items:center; justify-content:center; gap:4px; font-family:'Poppins',sans-serif;">
          <span class="stat-count" data-target="<?= (int)filter_var($st['value'], FILTER_SANITIZE_NUMBER_INT) ?>">0</span>
          <span style="color:#60a5fa;">+</span>
        </h3>
        <p style="font-size:14px; color:var(--text-muted); line-height:1.4; white-space:pre-line;">
          <?=htmlspecialchars($st['label'])?>
        </p>
        
        <?php if($idx < count($stats) - 1): ?>
        <div class="hidden md:block" style="position:absolute; right:-16px; top:50%; transform:translateY(-50%); width:1px; height:40px; background:#e2e8f0;"></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── ABOUT US SNIPPET (Index.php page update) ──────── -->
<section style="background:#fff; padding:64px 24px;">
  <div style="max-width:1200px; margin:0 auto; display:flex; gap:48px; align-items:center; flex-wrap:wrap;">
    <div style="flex:1; min-width:300px;" class="space-y-4">
      <div style="margin-bottom:20px;">
        <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                     font-size:11px; font-weight:600; padding:4px 14px;
                     border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
          WHO WE ARE
        </span>
        <h2 style="font-size:32px; font-weight:700; color:#0f172a; margin-bottom:8px;">
          Global Outsourcing Excellence Since 2011
        </h2>
        <div style="width:48px; height:3px; background:#2563eb; border-radius:2px; margin-top:10px;"></div>
      </div>
      <p style="font-size:14px; color:#4b5563; line-height:1.9; margin-bottom:20px;">
        <?= nl2br(htmlspecialchars($about_text)) ?>
      </p>
      <a href="/about-us.php" style="font-size:14px; font-weight:700; color:#2563eb;">Learn more about us &rarr;</a>
    </div>
    <div style="flex:1; min-width:300px; display:flex; justify-content:center;">
      <img src="/assets/images/about-home.jpg" alt="About Clevora" loading="lazy" style="border-radius:14px; border:1px solid #e8eaf0; max-height:360px; object-fit:cover; width:100%;">
    </div>
  </div>
</section>


<!-- ─── SERVICES GRID ────────────────────────────────── -->
<section style="background:#f9fafb; padding:70px 24px;">
  <!-- Section header -->
  <div style="text-align:center; margin-bottom:56px;">
    <span style="display:inline-block; background:#eff6ff; color:#3b82f6;
                 font-size:12px; font-weight:700; padding:6px 16px;
                 border-radius:9999px; letter-spacing:0.5px; margin-bottom:16px; text-transform:uppercase;">
      Core Solutions
    </span>
    <h2 style="font-size:clamp(32px, 4vw, 44px); font-weight:600; color:#0f172a; margin-bottom:16px; line-height:1.2;">
      Everything your business needs to scale operations
    </h2>
    <p style="font-size:18px; color:#4b5563; max-width:700px; margin:0 auto; line-height:1.6;">
      Outsource operational overhead to Clevora. We manage front-office customer engagement and back-office pipelines with accuracy and data security.
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl mx-auto">
    <?php foreach($solutions_list as $sol): ?>
    <div style="background:#fff; border:1px solid #f3f4f6; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:box-shadow .2s; cursor:pointer;" onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'" onclick="window.location.href='<?= htmlspecialchars($sol['button_link']) ?>'">
      <div style="margin-bottom:20px; font-size: 32px;"><?= htmlspecialchars($sol['icon'] ?: '💬') ?></div>
      <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px;"><?= htmlspecialchars($sol['title']) ?></h3>
      <p style="font-size:14px; color:#4b5563; line-height:1.6; margin-bottom:24px; height: 72px; overflow: hidden;">
        <?= htmlspecialchars($sol['description']) ?>
      </p>
      <a href="<?= htmlspecialchars($sol['button_link']) ?>" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;"><?= htmlspecialchars($sol['button_text'] ?: 'Explore Solutions') ?> &rarr;</a>
    </div>
    <?php endforeach; ?>
  </div><!-- end grid -->

  <!-- Centered CTA -->
  <div style="text-align:center; margin-top:48px;">
    <a href="/services.php"
       style="display:inline-flex; align-items:center; gap:8px;
              background:#2563eb; color:#fff;
              font-size:13px; font-weight:700; letter-spacing:0.04em; text-transform:uppercase;
              padding:14px 36px; border-radius:9999px; text-decoration:none;
              box-shadow:0 4px 18px rgba(37,99,235,0.25);
              transition:background 0.2s, transform 0.2s;"
       onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-2px)'"
       onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)'">
      Explore All Services &rarr;
    </a>
  </div>
</section>

<!-- ─── HOW IT WORKS ──────────────────────────────────── -->
<section style="background:#fff; padding:80px 24px; position:relative; overflow:hidden;">
  <div style="max-width:1200px; margin:0 auto; position:relative; z-index:1;">
    <div style="text-align:center; margin-bottom:60px;">
      <span style="display:inline-block; background:#eff6ff; color:#3b82f6;
                   font-size:11px; font-weight:700; padding:6px 16px;
                   border-radius:9999px; letter-spacing:1px; margin-bottom:16px; text-transform:uppercase;">
        How It Works
      </span>
      <h2 style="font-size:clamp(32px, 4vw, 42px); font-weight:700; color:#0f172a; margin-bottom:16px; font-family:'Poppins', sans-serif;">
        Go live in 3 simple steps
      </h2>
    </div>

    <!-- Steps timeline wrapper -->
    <div style="position:relative;">
      <!-- Horizontal Connecting Line (Desktop only) -->
      <div class="hidden md:block" style="position:absolute; top:28px; left:16.66%; right:16.66%; height:2px; background:#dbeafe; z-index:0;"></div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8" style="position:relative; z-index:1;">
        <?php foreach ($steps_list as $step): ?>
        <div style="text-align:center;">
          <div style="width:56px; height:56px; border-radius:50%; background:#1d4ed8; color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:700; margin:0 auto 24px auto; box-shadow:0 4px 10px rgba(29,78,216,0.2);">
            <?= htmlspecialchars($step['step_number']) ?>
          </div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
            <?= htmlspecialchars($step['title']) ?>
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; max-width:280px; margin:0 auto;">
            <?= htmlspecialchars($step['description']) ?>
          </p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ─── WHAT MAKES US DIFFERENT ───────────────────────── -->
<section style="background:#1e3a8a; padding:100px 24px; color:#fff;">
  <div style="max-width:1200px; margin:0 auto;">
    <div style="text-align:center; margin-bottom:60px;">
      <span style="display:inline-block; background:#eff6ff; color:#1e3a8a;
                   font-size:11px; font-weight:700; padding:6px 18px;
                   border-radius:9999px; letter-spacing:1px; margin-bottom:20px; text-transform:uppercase;">
        What Makes Us Different
      </span>
      <h2 style="font-size:clamp(32px, 4.5vw, 46px); font-weight:700; color:#fff; margin-bottom:20px; font-family:'Poppins', sans-serif;">
        What Sets Clevora Apart
      </h2>
      <p style="font-size:16px; color:#bfdbfe; max-width:800px; margin:0 auto; line-height:1.7;">
        We deliver quality, availability, and efficiency.
      </p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <?php foreach ($why_choose as $item): ?>
      <div style="background:#172554; border:1px solid #1e40af; border-radius:16px; padding:32px; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px; height:48px; border-radius:50%; background:rgba(59,130,246,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
          <?php if (str_starts_with($item['icon'], '<')): ?>
            <?= $item['icon'] ?>
          <?php else: ?>
            <i class="fa-solid <?= htmlspecialchars($item['icon']) ?>" style="color:#60a5fa; font-size:20px;"></i>
          <?php endif; ?>
        </div>
        <h3 style="font-size:18px; font-weight:600; color:#fff; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          <?= htmlspecialchars($item['title']) ?>
        </h3>
        <p style="font-size:14px; color:#93c5fd; line-height:1.6;">
          <?= htmlspecialchars($item['description']) ?>
        </p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ─── TESTIMONIALS ─────────────────────────────────── -->
<section style="background:#fff; padding:64px 24px;">
  <!-- Section header -->
  <div style="text-align:center; margin-bottom:48px;">
    <span style="display:inline-block; background:#eff6ff; color:#2563eb;
                 font-size:11px; font-weight:700; padding:6px 16px;
                 border-radius:9999px; letter-spacing:1px; margin-bottom:16px; text-transform:uppercase;">
      Our Clients
    </span>
    <h2 style="font-size:36px; font-weight:600; color:#0f172a; margin:0;">
      What our clients say
    </h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl mx-auto">
    <?php
    $avatar_colors = ['#7c3aed','#d97706','#1d4ed8','#059669','#dc2626'];
    $i = 0;
    foreach($testimonials as $t):
      $initials = strtoupper(substr($t['name'],0,1) . (strpos($t['name'],' ')!==false ? substr(strrchr($t['name'],' '),1,1) : ''));
      $color    = $avatar_colors[$i % count($avatar_colors)];
      $i++;
    ?>
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:32px; display:flex; flex-direction:column;">
      <div style="font-size:14px; font-weight:500; color:#4b5563; margin-bottom:20px;">
        <?=htmlspecialchars($t['location'])?>
      </div>
      
      <p style="font-size:15px; color:#374151; line-height:1.6; margin-bottom:32px; flex-grow:1;">
        "<?=htmlspecialchars($t['quote'])?>"
      </p>
      
      <div style="height:1px; background:#f3f4f6; margin-bottom:24px;"></div>
      
      <div style="display:flex; align-items:center; gap:12px;">
        <?php if($t['photo_url']): ?>
        <img src="<?=htmlspecialchars($t['photo_url'])?>"
             loading="lazy"
             style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        <?php else: ?>
        <div style="width:40px; height:40px; border-radius:50%; background:<?=$color?>;
                    display:flex; align-items:center; justify-content:center;
                    color:#fff; font-size:14px; font-weight:600;">
          <?=$initials?>
        </div>
        <?php endif; ?>
        <div>
          <p style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:2px;">
            <?=htmlspecialchars($t['name'])?>
          </p>
          <p style="font-size:12px; color:#6b7280;">
            Role, <?=htmlspecialchars($t['location'])?>
          </p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  
  <div style="text-align:center; margin-top:40px;">
    <a href="/clients.php" style="color:#2563eb; font-size:14px; font-weight:600; text-decoration:none;">
      Read all client testimonials &rarr;
    </a>
  </div>
</section>


<!-- ─── INDUSTRIES SECTION ───────────────────────────── -->
<section class="section" style="background:#f8f9fc; padding:64px 24px;">
  <div class="container">
    <div class="section-head">
      <span style="display:inline-block; background:#eff6ff; color:#2563eb;
                   font-size:11px; font-weight:700; padding:6px 16px;
                   border-radius:9999px; letter-spacing:1px; margin-bottom:16px; text-transform:uppercase;">
        Industries
      </span>
      <h2 class="section-title">Trusted across industries everywhere</h2>
      <p class="section-copy">From gaming studios to healthcare providers, we build ops teams that understand your industry.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($industries_sec as $ind): 
        $bg = $ind['bg'] ?? 'rgba(37,99,235,0.1)';
        $color = $ind['color'] ?? '#2563eb';
      ?>
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:<?= $bg ?>; color:<?= $color ?>; font-size:20px;">
          <i class="fa-solid <?= htmlspecialchars($ind['icon']) ?>"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          <?= htmlspecialchars($ind['title']) ?>
        </h3>
        <p>
          <?= htmlspecialchars($ind['description']) ?>
        </p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── FAQ SECTION ──────────────────────────────────── -->
<section style="background:#f4f5f7; padding:64px 24px;">
  <div style="max-width:800px; margin:0 auto;">
    <h2 style="font-size:clamp(36px, 5vw, 48px); font-weight:500; color:#111827; text-align:center; margin-bottom:56px; letter-spacing:-0.02em;">
      Frequently Asked Questions
    </h2>
    
    <div style="display:flex; flex-direction:column; border-top:1px solid #e5e7eb;">
      <?php foreach($faqs as $faq): ?>
      <div x-data="{ open: false }" style="border-bottom:1px solid #e5e7eb;">
        <button @click="open = !open" style="width:100%; display:flex; justify-content:space-between; align-items:center; padding:32px 0; background:transparent; border:none; cursor:pointer; text-align:left;">
          <span style="font-size:20px; font-weight:400; color:#111827; letter-spacing:-0.01em;"><?= htmlspecialchars($faq['q']) ?></span>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:24px !important; height:24px !important; min-width:24px !important; min-height:24px !important; flex-shrink:0 !important; color:#6b7280; transition:transform 0.3s;" :style="open && 'transform:rotate(180deg)'">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div x-show="open" style="padding-bottom:32px; color:#4b5563; font-size:16px; line-height:1.7; display:none;" x-transition>
          <?= htmlspecialchars($faq['a']) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

  <?php /* ?>
  <!-- ─── GET IN TOUCH (homepage form) ─────────────────── -->
  <section class="section section--soft">
  <div class="container">
  <div class="contact-panel">

    <!-- LEFT: dark contact info panel -->
    <div class="contact-panel__info">
      <span class="section-kicker">Contact Clevora</span>
      <h2>Get in Touch</h2>
      <p>Tell us what you need to outsource, improve, moderate, or support. Our team will help you shape the right operating plan.</p>

      <?php
      $info = [
        ['📞','Phone',   setting('contact_phone',$pdo)],
        ['✉', 'Email',   setting('contact_email',$pdo)],
        ['📍','Address', setting('contact_address',$pdo)],
        ['🕐','Hours',   '24 / 7 / 365'],
      ];
      foreach($info as [$icon,$label,$val]):
      ?>
      <div class="contact-item">
        <div class="contact-item__icon">
          <?=$icon?>
        </div>
        <div>
          <span><?=$label?></span>
          <strong><?=htmlspecialchars($val)?></strong>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Map placeholder -->
      <div class="contact-map">
        <iframe
          src="https://maps.google.com/maps?q=<?= urlencode(setting('contact_address', $pdo)) ?>&output=embed"
          style="width:100%; height:100%; border:none; border-radius:10px;"
          loading="lazy" allowfullscreen>
        </iframe>
      </div>

      <!-- Social -->
      <div class="contact-socials">
        <?php
        $socials = [['f','#1877f2'],['X','#374151'],['in','#0a66c2'],['🌳','#f97316']];
        foreach($socials as [$l,$c]):
        ?>
        <a href="#"
          style="background:<?=$c?>;">
          <?=$l?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT: white form panel -->
    <div class="contact-panel__form">
      <h2>Send a Message</h2>
      <div id="form-message" class="form-message" style="display:none;"></div>
      <form id="contact-form">
        <div class="contact-form-grid">

          <?php
          $form_fields = [
            ['name'=>'name',    'type'=>'text',  'label'=>'Full Name',     'placeholder'=>'e.g. Manish Sharma', 'required'=>true],
            ['name'=>'email',   'type'=>'email', 'label'=>'Email Address', 'placeholder'=>'you@company.com',    'required'=>true],
            ['name'=>'phone',   'type'=>'tel',   'label'=>'Phone Number',  'placeholder'=>'+91 XXXXXXXXXX',     'required'=>true],
          ];
          foreach($form_fields as $f):
          ?>
          <div class="<?=$f['name']==='name'?'form-span-2':''?>">
            <label class="form-label">
              <?=$f['label']?> <?=$f['required']?'<span style="color:#ef4444">*</span>':''?>
            </label>
            <input type="<?=$f['type']?>" name="<?=$f['name']?>"
                  placeholder="<?=$f['placeholder']?>"
                  <?=$f['required']?'required':''?>
                  class="form-control">
          </div>
          <?php endforeach; ?>

          <!-- Area of interest -->
          <div class="form-span-2">
            <label class="form-label">
              Area of Interest <span style="color:#ef4444">*</span>
            </label>
            <select name="interest" required class="form-select">
              <option value="">Select a service...</option>
              <?php
              $interests = ['Content Moderation','Digital Marketing',
                            'Foreign Language Support','Data Validation',
                            'Mortgage Services','Inbound / Outbound',
                            'Business Process Outsourcing','Back Office Support',
                            'Publishing Solutions','Software Solutions',
                            'Database Management','Conversion Catalyst'];
              foreach($interests as $opt):
              ?>
              <option><?=htmlspecialchars($opt)?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Message -->
          <div class="form-span-2">
            <label class="form-label">
              Message
            </label>
            <textarea name="message" rows="4"
                      placeholder="Tell us about your requirements..."
                      class="form-textarea"></textarea>
          </div>

          <div class="form-span-2">
            <button type="submit" id="submit-btn" class="btn btn--primary contact-submit">
              Send Message
            </button>
            <p class="contact-note">
              We typically respond within 2 business hours.
            </p>
          </div>

        </div>
      </form>
    </div>
  </div>
  </div>

  </section>
  <?php */ ?>


<?php require_once 'includes/footer.php'; ?>
