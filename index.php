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

if ($pdo) {
    try {
        $gallery  = $pdo->query("SELECT * FROM gallery ORDER BY sort_order LIMIT 6")->fetchAll();
        $testimonials = $pdo->query("SELECT * FROM testimonials WHERE is_active=1")->fetchAll();
        $clients  = $pdo->query("SELECT * FROM clients")->fetchAll();
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
?>

<!-- ─── HERO ─────────────────────────────────────────── -->
<?php
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
  ],
];
?>
<section class="hero-slider" aria-labelo="Clevora services">
  <div class="hero-slider__viewport">
    <?php foreach($hero_slides as $idx => $slide): ?>
    <article class="hero-slide <?= $idx === 0 ? 'is-active' : '' ?>" data-hero-slide>
      <img class="hero-slide__image" src="<?= htmlspecialchars($slide['image']) ?>" alt="" aria-hidden="true">
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
          <a class="hero-btn hero-btn--primary" href="/contact.php">Contact Us</a>
          <a class="hero-btn hero-btn--ghost" href="/services.php">Explore Solutions</a>
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
        $marquee_items = array_merge($clients, $clients);
        foreach($marquee_items as $c): 
        ?>
        <div style="background:#fff; border:1px solid #e8eaf0; border-radius:16px; padding:24px;
                    display:flex; align-items:center; justify-content:center; height:100px; width:180px; flex-shrink:0;
                    box-shadow:0 4px 20px rgba(0,0,0,0.01); transition:all 0.3s;"
             onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.05)';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.01)';this.style.transform='none'">
          <?php if (!empty($c['logo_url'])): ?>
            <img src="<?= htmlspecialchars($c['logo_url']) ?>" alt="<?= htmlspecialchars($c['name'] ?? 'Client') ?>" loading="lazy" style="max-height:100%; max-width:100%; object-fit:contain; filter:grayscale(100%); transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(100%)'">
          <?php else: ?>
            <span style="color:#6b7280; font-weight:600; font-size:11px; text-transform:uppercase;"><?= htmlspecialchars($c['name'] ?? 'Client') ?></span>
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
    <!-- Highlight 1: Customer Support -->
    <div style="background:#fff; border:1px solid #f3f4f6; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:box-shadow .2s; cursor:pointer;" onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
      <div style="margin-bottom:20px; font-size: 32px;">💬</div>
      <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px;">Customer Support</h3>
      <p style="font-size:14px; color:#4b5563; line-height:1.6; margin-bottom:24px; height: 72px; overflow: hidden;">
        Multilingual inbound/outbound support, email and live chat operations with SLAs tailored to keep customer satisfaction high.
      </p>
      <a href="/services.php?category=customer-support-services" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">Explore Solutions &rarr;</a>
    </div>

    <!-- Highlight 2: Content Moderation -->
    <div style="background:#fff; border:1px solid #f3f4f6; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:box-shadow .2s; cursor:pointer;" onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
      <div style="margin-bottom:20px; font-size: 32px;">🛡️</div>
      <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px;">Content Moderation</h3>
      <p style="font-size:14px; color:#4b5563; line-height:1.6; margin-bottom:24px; height: 72px; overflow: hidden;">
        24/7 video, audio, image and social media review moderation. Keep your application community and brand reputation safe.
      </p>
      <a href="/services.php?category=content-moderation-services" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">Explore Solutions &rarr;</a>
    </div>

    <!-- Highlight 3: Data Management -->
    <div style="background:#fff; border:1px solid #f3f4f6; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:box-shadow .2s; cursor:pointer;" onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
      <div style="margin-bottom:20px; font-size: 32px;">📂</div>
      <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px;">Data Management</h3>
      <p style="font-size:14px; color:#4b5563; line-height:1.6; margin-bottom:24px; height: 72px; overflow: hidden;">
        High speed data entry, processing, and standardisation solutions to keep corporate records accurate and accessible.
      </p>
      <a href="/services.php?category=back-office-data-management" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">Explore Solutions &rarr;</a>
    </div>

    <!-- Highlight 4: E-Commerce Support -->
    <div style="background:#fff; border:1px solid #f3f4f6; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:box-shadow .2s; cursor:pointer;" onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
      <div style="margin-bottom:20px; font-size: 32px;">🛒</div>
      <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px;">E-Commerce Support</h3>
      <p style="font-size:14px; color:#4b5563; line-height:1.6; margin-bottom:24px; height: 72px; overflow: hidden;">
        Optimize store operations: catalog product uploads, order tracking coordination, returns and marketplace support.
      </p>
      <a href="/services.php?category=e-commerce-support" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">Explore Solutions &rarr;</a>
    </div>

    <!-- Highlight 5: Finance Operations -->
    <div style="background:#fff; border:1px solid #f3f4f6; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:box-shadow .2s; cursor:pointer;" onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
      <div style="margin-bottom:20px; font-size: 32px;">💳</div>
      <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px;">Finance Operations</h3>
      <p style="font-size:14px; color:#4b5563; line-height:1.6; margin-bottom:24px; height: 72px; overflow: hidden;">
        Automate billing support, accounts receivable/payable, expense reconciliations and payroll auditing.
      </p>
      <a href="/services.php?category=finance-accounting" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">Explore Solutions &rarr;</a>
    </div>

    <!-- Highlight 6: HR Solutions -->
    <div style="background:#fff; border:1px solid #f3f4f6; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.03); transition:box-shadow .2s; cursor:pointer;" onmouseover="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
      <div style="margin-bottom:20px; font-size: 32px;">👥</div>
      <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px;">HR Solutions</h3>
      <p style="font-size:14px; color:#4b5563; line-height:1.6; margin-bottom:24px; height: 72px; overflow: hidden;">
        Recruitment process outsourcing (RPO), resume screening, talent sourcing and HR operations administration.
      </p>
      <a href="/services.php?category=hr-solutions" style="font-size:13px; color:#3b82f6; font-weight:600; text-decoration:none;">Explore Solutions &rarr;</a>
    </div><!-- end HR card -->
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
        <!-- Step 1 -->
        <div style="text-align:center;">
          <div style="width:56px; height:56px; border-radius:50%; background:#1d4ed8; color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:700; margin:0 auto 24px auto; box-shadow:0 4px 10px rgba(29,78,216,0.2);">
            1
          </div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
            Tell Us What You Need
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; max-width:280px; margin:0 auto;">
            Share your requirements, and we'll match you with a pre-trained team within 24 hours.
          </p>
        </div>

        <!-- Step 2 -->
        <div style="text-align:center;">
          <div style="width:56px; height:56px; border-radius:50%; background:#1d4ed8; color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:700; margin:0 auto 24px auto; box-shadow:0 4px 10px rgba(29,78,216,0.2);">
            2
          </div>
           <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
            We Deploy Your Team
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; max-width:280px; margin:0 auto;">
            Your dedicated team starts within 7 days. We handle onboarding, tools, and processes.
          </p>
        </div>
        <!-- Step 3 -->
        <div style="text-align:center;">
          <div style="width:56px; height:56px; border-radius:50%; background:#1d4ed8; color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:700; margin:0 auto 24px auto; box-shadow:0 4px 10px rgba(29,78,216,0.2);">
            3
          </div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
            Scale As You Grow
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; max-width:280px; margin:0 auto;">
            Add or reduce team members anytime. No long-term contracts. Pay only for hours worked.
          </p>
        </div>
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
      
      <!-- Card 1: QUALIFIED EXPERTS -->
      <div style="background:#172554; border:1px solid #1e40af; border-radius:16px; padding:32px; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px; height:48px; border-radius:50%; background:rgba(59,130,246,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
          <svg style="color:#60a5fa; width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
          </svg>
        </div>
        <h3 style="font-size:18px; font-weight:600; color:#fff; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          QUALIFIED EXPERTS
        </h3>
        <p style="font-size:14px; color:#93c5fd; line-height:1.6;">
          Certified professionals with domain-specific training across every service vertical.
        </p>
      </div>

      <!-- Card 2: WORKMANSHIP QUALITY -->
      <div style="background:#172554; border:1px solid #1e40af; border-radius:16px; padding:32px; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px; height:48px; border-radius:50%; background:rgba(244,63,94,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
          <svg style="color:#fb7185; width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
          </svg>
        </div>
        <h3 style="font-size:18px; font-weight:600; color:#fff; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          WORKMANSHIP QUALITY
        </h3>
        <p style="font-size:14px; color:#93c5fd; line-height:1.6;">
          Multi-tier QA ensures output with less than 0.5% error rate on every delivery.
        </p>
      </div>

      <!-- Card 3: FLEXIBLE SCHEDULE -->
      <div style="background:#172554; border:1px solid #1e40af; border-radius:16px; padding:32px; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px; height:48px; border-radius:50%; background:rgba(20,184,166,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
          <svg style="color:#2dd4bf; width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 style="font-size:18px; font-weight:600; color:#fff; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          FLEXIBLE SCHEDULE
        </h3>
        <p style="font-size:14px; color:#93c5fd; line-height:1.6;">
          24/7/365 operations adapted to your time zone and business requirements.
        </p>
      </div>

      <!-- Card 4: AFFORDABLE PACKAGES -->
      <div style="background:#172554; border:1px solid #1e40af; border-radius:16px; padding:32px; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px; height:48px; border-radius:50%; background:rgba(59,130,246,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
          <svg style="color:#60a5fa; width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 style="font-size:18px; font-weight:600; color:#fff; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          AFFORDABLE PACKAGES
        </h3>
        <p style="font-size:14px; color:#93c5fd; line-height:1.6;">
          Enterprise-grade output at SME-friendly pricing with transparent SLAs.
        </p>
      </div>

      <!-- Card 5: DATA SECURITY -->
      <div style="background:#172554; border:1px solid #1e40af; border-radius:16px; padding:32px; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px; height:48px; border-radius:50%; background:rgba(20,184,166,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
          <svg style="color:#2dd4bf; width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
        <h3 style="font-size:18px; font-weight:600; color:#fff; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          DATA SECURITY
        </h3>
        <p style="font-size:14px; color:#93c5fd; line-height:1.6;">
          ISO-aligned protocols, NDAs, and GDPR-aware data handling by default.
        </p>
      </div>

      <!-- Card 6: WORK ETHICS -->
      <div style="background:#172554; border:1px solid #1e40af; border-radius:16px; padding:32px; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width:48px; height:48px; border-radius:50%; background:rgba(59,130,246,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
          <svg style="color:#60a5fa; width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <h3 style="font-size:18px; font-weight:600; color:#fff; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          WORK ETHICS
        </h3>
        <p style="font-size:14px; color:#93c5fd; line-height:1.6;">
          Dedicated account managers and a customer-first culture in everything we do.
        </p>
      </div>

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
      <!-- Gaming & Entertainment -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(37,99,235,0.1); color:#2563eb; font-size:20px;">
          <i class="fa-solid fa-gamepad"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Gaming & Entertainment
        </h3>
        <p>
          Content moderation, QA testing, player support
        </p>
      </article>

      <!-- Education -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(239,68,68,0.1); color:#ef4444; font-size:20px;">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Education
        </h3>
        <p>
          Admissions processing, student records, LMS data
        </p>
      </article>

      <!-- Retail & E-commerce -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(6,182,212,0.1); color:#06b6d4; font-size:20px;">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Retail & E-commerce
        </h3>
        <p>
          Product listings, order processing, catalog ops
        </p>
      </article>

      <!-- Hospitality -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(59,130,246,0.1); color:#3b82f6; font-size:20px;">
          <i class="fa-solid fa-hotel"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Hospitality
        </h3>
        <p>
          Reservations, guest data, multi-property ops
        </p>
      </article>

      <!-- Staffing & HR -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(236,72,153,0.1); color:#ec4899; font-size:20px;">
          <i class="fa-solid fa-address-card"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Staffing & HR
        </h3>
        <p>
          Resume screening, ATS management, onboarding
        </p>
      </article>

      <!-- Healthcare -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(20,184,166,0.1); color:#14b8a6; font-size:20px;">
          <i class="fa-solid fa-briefcase-medical"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Healthcare
        </h3>
        <p>
          Claims processing, patient records, compliance
        </p>
      </article>

      <!-- Real Estate -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(79,70,229,0.1); color:#4f46e5; font-size:20px;">
          <i class="fa-solid fa-house"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Real Estate
        </h3>
        <p>
          Listings management, lead processing, CRM data
        </p>
      </article>

      <!-- Financial Services -->
      <article class="card feature-card">
        <div class="feature-card__icon" style="background:rgba(245,158,11,0.1); color:#f59e0b; font-size:20px;">
          <i class="fa-solid fa-coins"></i>
        </div>
        <h3 style="text-transform:none; font-size:16px; font-weight:600; letter-spacing:normal; margin-bottom:8px; font-family:'Poppins', sans-serif;">
          Financial Services
        </h3>
        <p>
          Transaction processing, reconciliation, reporting
        </p>
      </article>
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
      <?php
      $faqs = [
        ['q' => 'What outsourcing services does Clevora offer?', 'a' => 'Clevora provides customer support, call center operations, content moderation, e-commerce support, finance, HR, data management and KPO solutions.'],
        ['q' => 'How quickly can you deploy a team?', 'a' => 'Deployment depends on requirements, team size and process complexity. Clevora builds flexible teams designed around client needs.'],
        ['q' => 'How much can outsourcing save?', 'a' => 'Savings vary by operation type, but outsourcing helps reduce hiring, infrastructure and management costs.'],
        ['q' => 'How do you ensure data security?', 'a' => 'We follow controlled access practices, secure workflows and confidentiality-focused operational processes.'],
        ['q' => 'Can I scale services?', 'a' => 'Yes. Teams and processes can expand or adjust according to changing business requirements.'],
        ['q' => 'Where are your teams located?', 'a' => 'Our operations are based in Delhi, India, supporting clients globally.']
      ];
      foreach($faqs as $faq):
      ?>
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
