<?php
$pageTitle = 'Clevora | Global BPO & Outsourcing Solutions | Delhi, India';
$metaDesc  = 'Clevora provides content moderation, BPO, digital marketing, and outsourcing services from Delhi, India.';
require_once 'includes/header.php';

$hero_headline = setting('hero_headline', $pdo);
$hero_bullets  = explode("\n", setting('hero_bullets', $pdo));
$hero_cta      = setting('hero_cta_text', $pdo);
$about_text    = setting('about_home_text', $pdo);

$services = [];
$gallery = [];
$testimonials = [];

if ($pdo) {
    try {
        $services = $pdo->query("SELECT * FROM services WHERE is_active=1 ORDER BY sort_order LIMIT 9")->fetchAll();
        $gallery  = $pdo->query("SELECT * FROM gallery ORDER BY sort_order LIMIT 6")->fetchAll();
        $testimonials = $pdo->query("SELECT * FROM testimonials WHERE is_active=1")->fetchAll();
    } catch(Exception $e) {
        error_log('Homepage fetch error: ' . $e->getMessage());
    }
}

// Fallbacks if DB empty
if (empty($services)) {
    $services = [
        ['slug' => 'database-management', 'name' => 'Database Management', 'icon_url' => '/assets/images/service-db.svg', 'intro' => 'Preventing Oversized Non-Standard Data Formats, Address Verification, Postal Code Correction, NCOA and Standardization.'],
        ['slug' => 'content-moderation', 'name' => 'Content Moderation', 'icon_url' => '/assets/images/service-moderation.svg', 'intro' => 'Protect your brand reputation and build user trust with our global content moderation services.'],
        ['slug' => 'digital-marketing', 'name' => 'Digital Marketing', 'icon_url' => '/assets/images/service-marketing.svg', 'intro' => 'Enhance your digital footprint with SEO, PPC, and social media campaigns designed to generate high-quality leads.']
    ];
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

$stats = [
  ['value'=>setting('stats_projects',$pdo),   'label'=>'Projects Delivered'],
  ['value'=>setting('stats_industries',$pdo), 'label'=>'Industries Served'],
  ['value'=>setting('stats_resumes',$pdo),    'label'=>'Resumes Revised'],
  ['value'=>setting('stats_clients',$pdo),    'label'=>'Happy Clients'],
];
?>

<!-- ─── HERO ─────────────────────────────────────────── -->
<?php
$hero_slides = [
  [
    'eyebrow' => 'GLOBAL BPO SOLUTIONS',
    'title' => 'Business Outsourcing',
    'quote' => 'In your goals, we find our mission.',
    'image' => '/assets/images/hero-bg.jpg',
    'bullets' => [
      'Customer care and live chat support',
      'Multi-language communication support',
      'Order management and fulfilment services',
      'Data analysis, online filing, forms and claims',
    ],
  ],
  [
    'eyebrow' => 'TRUST & SAFETY OPERATIONS',
    'title' => 'Content Moderation',
    'quote' => 'Protect communities with fast, consistent review.',
    'image' => '/assets/images/content-mod.jpg',
    'bullets' => [
      'Web content moderation',
      'Social media content moderation',
      'User-generated content review',
      'Discussion board and marketplace moderation',
    ],
  ],
  [
    'eyebrow' => 'DATA OPERATIONS',
    'title' => 'Database Management',
    'quote' => 'Reliable service for clean, usable data.',
    'image' => '/assets/images/service-banner.jpg',
    'bullets' => [
      'Processing standard and non-standard data formats',
      'Database consolidation and duplicate elimination',
      'Address verification and postal code correction',
      'List management, segmentation and response analysis',
    ],
  ],
  [
    'eyebrow' => 'SOFTWARE & AUTOMATION',
    'title' => 'Software Solutions',
    'quote' => 'Runs faster, costs less, and scales cleanly.',
    'image' => '/assets/images/hero-bg.jpg',
    'bullets' => [
      'Database and product development',
      'Information processing',
      'Information enhancement and analytics',
      'Contact center tools and information security',
    ],
  ],
  [
    'eyebrow' => 'GROWTH SERVICES',
    'title' => 'Digital Marketing',
    'quote' => 'Make contact. Build relationships. Get results.',
    'image' => '/assets/images/content-mod.jpg',
    'bullets' => [
      'Social media management',
      'Search, paid media and performance campaigns',
      'Influencer and viral marketing',
      'Brand content for web, radio and mobile channels',
    ],
  ],
];
?>
<section class="hero-slider" aria-label="Clevora services">
  <div class="hero-slider__viewport">
    <?php foreach($hero_slides as $idx => $slide): ?>
    <article class="hero-slide <?= $idx === 0 ? 'is-active' : '' ?>" data-hero-slide>
      <img class="hero-slide__image" src="<?= htmlspecialchars($slide['image']) ?>" alt="" aria-hidden="true">
      <div class="hero-slide__overlay"></div>
      <div class="hero-slide__content">
        <span class="hero-slide__eyebrow"><?= htmlspecialchars($slide['eyebrow']) ?></span>
        <h1><?= htmlspecialchars($slide['title']) ?></h1>
        <p class="hero-slide__quote">&ldquo;<?= htmlspecialchars($slide['quote']) ?>&rdquo;</p>
        <ul class="hero-slide__list">
          <?php foreach($slide['bullets'] as $bullet): ?>
          <li><?= htmlspecialchars($bullet) ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="hero-slide__actions">
          <a class="hero-btn hero-btn--primary" href="/contact.php">Contact Us</a>
          <a class="hero-btn hero-btn--ghost" href="/services.php">Explore Services</a>
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

<!-- ─── STATS STRIP ──────────────────────────────────── -->
<section style="background:#1a1a2e; padding:28px 24px;">
  <div style="max-width:900px; margin:0 auto;
              display:grid; grid-template-columns:repeat(4,1fr); gap:16px;" class="grid grid-cols-2 md:grid-cols-4">
    <?php foreach($stats as $st): ?>
    <div style="text-align:center;">
      <p class="stat-count" data-target="<?= (int)filter_var($st['value'], FILTER_SANITIZE_NUMBER_INT) ?>"
         style="font-size:32px; font-weight:700; color:#f97316;">0</p>
      <p style="font-size:11px; color:#94a3b8; text-transform:uppercase;
                letter-spacing:.5px; margin-top:2px;">
        <?=htmlspecialchars($st['label'])?>
      </p>
    </div>
    <?php endforeach; ?>
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
        <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
          Global BPO Operations Since 2011
        </h2>
        <div style="width:48px; height:3px; background:#2563eb; border-radius:2px; margin-top:10px;"></div>
      </div>
      <p style="font-size:13px; color:#4b5563; line-height:1.9; margin-bottom:20px;">
        <?= nl2br(htmlspecialchars($about_text)) ?>
      </p>
      <a href="/about-us.php" style="font-size:12px; font-weight:700; color:#2563eb;">Learn more about us &rarr;</a>
    </div>
    <div style="flex:1; min-width:300px; display:flex; justify-content:center;">
      <img src="/assets/images/about-home.jpg" alt="About Clevora" style="border-radius:14px; border:1px solid #e8eaf0; max-height:360px; object-fit:cover; width:100%;">
    </div>
  </div>
</section>

<!-- ─── SERVICES GRID ────────────────────────────────── -->
<section style="background:#f8f9fc; padding:56px 24px;">
  <!-- Section header -->
  <div style="text-align:center; margin-bottom:40px;">
    <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                 font-size:11px; font-weight:600; padding:4px 14px;
                 border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
      OUR SERVICES
    </span>
    <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
      Comprehensive Outsourcing Solutions
    </h2>
    <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
      Everything your business needs, delivered by expert teams.
    </p>
    <div style="width:48px; height:3px; background:#2563eb;
                border-radius:2px; margin:12px auto 0;"></div>
  </div>

  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;" class="grid grid-cols-1 md:grid-cols-3">
    <?php foreach($services as $s): ?>
    <div style="background:#fff; border:1px solid #e8eaf0; border-radius:14px;
                padding:22px; transition:all .2s; cursor:pointer;"
         onmouseover="this.style.borderColor='#93c5fd';
                      this.style.boxShadow='0 4px 16px rgba(37,99,235,.08)';
                      this.style.transform='translateY(-2px)'"
         onmouseout="this.style.borderColor='#e8eaf0';
                     this.style.boxShadow='none';
                     this.style.transform='none'">
      <div style="width:44px; height:44px; border-radius:10px; background:#eff6ff;
                  display:flex; align-items:center; justify-content:center;
                  margin-bottom:12px; overflow:hidden;">
        <?php if($s['icon_url']): ?>
        <img src="<?=htmlspecialchars($s['icon_url'])?>" style="width:28px;height:28px;object-fit:contain;">
        <?php else: ?>
        <span style="color:#2563eb; font-weight:700; font-size:18px;">⚙</span>
        <?php endif; ?>
      </div>
      <h3 style="font-size:12px; font-weight:700; color:#0f172a; margin-bottom:6px;
                 text-transform:uppercase; letter-spacing:.3px;">
        <?=htmlspecialchars($s['name'])?>
      </h3>
      <p style="font-size:12px; color:#6b7280; line-height:1.7; margin-bottom:12px;
                display:-webkit-box; -webkit-line-clamp:3;
                -webkit-box-orient:vertical; overflow:hidden;">
        <?=htmlspecialchars($s['intro'])?>
      </p>
      <a href="/detail-services.php?slug=<?=urlencode($s['slug'])?>"
         style="font-size:12px; color:#2563eb; font-weight:600;">
        Read more →
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ─── GET IN TOUCH (homepage form) ─────────────────── -->
<section style="background:#1a1a2e; padding:56px 24px;">
  <div style="max-width:760px; margin:0 auto; text-align:center;">
    <h2 style="font-size:28px; font-weight:700; color:#fff;
               font-family:'Poppins',sans-serif; margin-bottom:32px;">
      Get in Touch
    </h2>
    <form id="home-contact-form"
          style="display:grid; grid-template-columns:1fr 1fr; gap:14px;" class="grid grid-cols-1 md:grid-cols-2">
      <?php
      $fields = [
        ['name'=>'name',    'type'=>'text',  'placeholder'=>'Full Name'],
        ['name'=>'email',   'type'=>'email', 'placeholder'=>'Email Address'],
        ['name'=>'phone',   'type'=>'tel',   'placeholder'=>'Phone Number'],
        ['name'=>'interest','type'=>'text',  'placeholder'=>'Area of Interest'],
      ];
      foreach($fields as $f):
      ?>
      <input type="<?=$f['type']?>" name="<?=$f['name']?>"
             placeholder="<?=$f['placeholder']?>"
             style="background:#1e3a5f; border:1px solid #334155; color:#fff;
                    border-radius:8px; padding:11px 14px; font-size:13px;
                    outline:none; transition:border .2s;"
             onfocus="this.style.borderColor='#2563eb'"
             onblur="this.style.borderColor='#334155'">
      <?php endforeach; ?>
      <textarea name="message" rows="3" placeholder="Your message..."
                style="background:#1e3a5f; border:1px solid #334155;
                       color:#fff; border-radius:8px; padding:11px 14px;
                       font-size:13px; outline:none; resize:none; transition:border .2s;" class="md:col-span-2"
                onfocus="this.style.borderColor='#2563eb'"
                onblur="this.style.borderColor='#334155'"></textarea>
      <div style="text-align:center;" class="md:col-span-2">
        <button type="submit"
                style="background:#f97316; color:#fff; padding:12px 40px;
                       border-radius:8px; font-size:13px; font-weight:700;
                       border:none; cursor:pointer; transition:background .2s;"
                onmouseover="this.style.background='#ea6c0a'"
                onmouseout="this.style.background='#f97316'">
          SUBMIT
        </button>
      </div>
    </form>
    <div id="home-form-msg" style="display:none; margin-top:14px; font-size:13px;"></div>
  </div>
</section>

<!-- ─── WHY CHOOSE US ────────────────────────────────── -->
<section style="background:#fff; padding:56px 24px;">
  <!-- Section header -->
  <div style="text-align:center; margin-bottom:40px;">
    <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                 font-size:11px; font-weight:600; padding:4px 14px;
                 border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
      WHY CHOOSE US
    </span>
    <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
      What Sets Clevora Apart
    </h2>
    <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
      We deliver quality, availability, and efficiency.
    </p>
    <div style="width:48px; height:3px; background:#2563eb;
                border-radius:2px; margin:12px auto 0;"></div>
  </div>

  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;" class="grid grid-cols-1 md:grid-cols-3">
    <?php
    $whys = [
      ['🎓','QUALIFIED EXPERTS',     'Certified professionals with domain-specific training across every service vertical.'],
      ['⭐','WORKMANSHIP QUALITY',   'Multi-tier QA ensures output with less than 0.5% error rate on every delivery.'],
      ['🕐','FLEXIBLE SCHEDULE',     '24/7/365 operations adapted to your time zone and business requirements.'],
      ['💰','AFFORDABLE PACKAGES',   'Enterprise-grade output at SME-friendly pricing with transparent SLAs.'],
      ['🔒','DATA SECURITY',         'ISO-aligned protocols, NDAs, and GDPR-aware data handling by default.'],
      ['🤝','WORK ETHICS',           'Dedicated account managers and a customer-first culture in everything we do.'],
    ];
    foreach($whys as [$icon,$title,$desc]):
    ?>
    <div style="text-align:center; padding:24px 16px;">
      <div style="width:58px; height:58px; border-radius:50%; background:#eff6ff;
                  border:2px solid #dbeafe; margin:0 auto 14px;
                  display:flex; align-items:center; justify-content:center;
                  font-size:22px;">
        <?=$icon?>
      </div>
      <p style="font-size:12px; font-weight:700; color:#0f172a; margin-bottom:6px;
                text-transform:uppercase; letter-spacing:.4px;"><?=$title?></p>
      <p style="font-size:12px; color:#6b7280; line-height:1.7;"><?=$desc?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ─── GALLERY ──────────────────────────────────────── -->
<section style="background:#f8f9fc; padding:56px 24px;">
  <!-- Section header -->
  <div style="text-align:center; margin-bottom:40px;">
    <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                 font-size:11px; font-weight:600; padding:4px 14px;
                 border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
      OUR GALLERY
    </span>
    <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
      Our Workplace Facilities
    </h2>
    <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
      Have a look at our operations floors and data server rooms.
    </p>
    <div style="width:48px; height:3px; background:#2563eb;
                border-radius:2px; margin:12px auto 0;"></div>
  </div>

  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px;" class="grid grid-cols-1 md:grid-cols-3">
    <?php foreach($gallery as $g): ?>
    <div style="border-radius:12px; overflow:hidden; position:relative;
                border:1px solid #e8eaf0; aspect-ratio:4/3; background:#f0f4ff;
                display:flex; align-items:center; justify-content:center;"
         onmouseover="this.querySelector('.gallery-overlay').style.opacity='1'"
         onmouseout="this.querySelector('.gallery-overlay').style.opacity='0'">
      <img src="<?=htmlspecialchars($g['image_url'])?>"
           alt="<?=htmlspecialchars($g['caption']??'')?>"
           style="width:100%; height:100%; object-fit:cover;">
      <div class="gallery-overlay"
           style="position:absolute; inset:0; background:rgba(37,99,235,.65);
                  display:flex; flex-direction:column; align-items:center;
                  justify-content:center; gap:8px; opacity:0; transition:opacity .25s;">
        <?php if(!empty($g['caption'])): ?>
        <p style="color:#fff; font-size:12px; font-weight:600;">
          <?=htmlspecialchars($g['caption'])?>
        </p>
        <?php endif; ?>
        <a href="<?=htmlspecialchars($g['image_url'])?>" target="_blank"
           style="background:#fff; color:#2563eb; font-size:11px; font-weight:700;
                  padding:6px 14px; border-radius:6px; text-decoration:none;">
          View More
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ─── TESTIMONIALS ─────────────────────────────────── -->
<section style="background:#fff; padding:56px 24px;">
  <!-- Section header -->
  <div style="text-align:center; margin-bottom:40px;">
    <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                 font-size:11px; font-weight:600; padding:4px 14px;
                 border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
      TESTIMONIALS
    </span>
    <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
      What Our Clients Say
    </h2>
    <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
      Trusted reviews from companies and organizations globally.
    </p>
    <div style="width:48px; height:3px; background:#2563eb;
                border-radius:2px; margin:12px auto 0;"></div>
  </div>

  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;" class="grid grid-cols-1 md:grid-cols-3">
    <?php
    $avatar_colors = ['#2563eb','#f97316','#059669','#7c3aed','#dc2626'];
    $i = 0;
    foreach($testimonials as $t):
      $initials = strtoupper(substr($t['name'],0,1) . (strpos($t['name'],' ')!==false ? substr(strrchr($t['name'],' '),1,1) : ''));
      $color    = $avatar_colors[$i % count($avatar_colors)];
      $i++;
    ?>
    <div style="background:#fff; border:1px solid #e8eaf0; border-radius:14px;
                padding:22px; transition:box-shadow .2s;"
         onmouseover="this.style.boxShadow='0 4px 16px rgba(37,99,235,.08)'"
         onmouseout="this.style.boxShadow='none'">
      <div style="font-size:36px; color:#dbeafe; line-height:.8; margin-bottom:10px;">"</div>
      <p style="font-size:12px; color:#4b5563; line-height:1.8; font-style:italic;
                margin-bottom:16px;">
        <?=htmlspecialchars($t['quote'])?>
      </p>
      <div style="display:flex; align-items:center; gap:10px;">
        <?php if($t['photo_url']): ?>
        <img src="<?=htmlspecialchars($t['photo_url'])?>"
             style="width:38px;height:38px;border-radius:50%;object-fit:cover;
                    border:2px solid #dbeafe; flex-shrink:0;">
        <?php else: ?>
        <div style="width:38px; height:38px; border-radius:50%; background:<?=$color?>;
                    display:flex; align-items:center; justify-content:center;
                    color:#fff; font-size:12px; font-weight:700; flex-shrink:0;">
          <?=$initials?>
        </div>
        <?php endif; ?>
        <div>
          <p style="font-size:12px; font-weight:700; color:#0f172a;">
            <?=htmlspecialchars($t['name'])?>
          </p>
          <p style="font-size:11px; color:#9ca3af;">
            <?=htmlspecialchars($t['location'])?>
          </p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ─── CTA STRIP ────────────────────────────────────── -->
<section style="background:#2563eb; padding:24px 24px;">
  <div style="max-width:1200px; margin:0 auto;
              display:flex; align-items:center; justify-content:space-between;
              flex-wrap:wrap; gap:14px;">
    <div>
      <h3 style="color:#fff; font-size:17px; font-weight:700;">
        We have the best experts to elevate your business.
      </h3>
      <p style="color:#bfdbfe; font-size:12px; margin-top:2px;">
        📞 <?=setting('contact_phone',$pdo)?>
      </p>
    </div>
    <a href="/contact.php"
       style="background:#fff; color:#2563eb; padding:11px 24px;
              border-radius:8px; font-size:13px; font-weight:700;
              text-decoration:none; transition:background .2s;"
       onmouseover="this.style.background='#eff6ff'"
       onmouseout="this.style.background='#fff'">
      Contact Us
    </a>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
