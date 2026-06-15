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

function getServiceCategory($slug) {
    $slug = strtolower($slug);
    if (strpos($slug, 'support') !== false || strpos($slug, 'call') !== false || strpos($slug, 'language') !== false) {
        return ['tag' => 'Support', 'class' => 'tag-support'];
    } elseif (strpos($slug, 'data') !== false || strpos($slug, 'office') !== false || strpos($slug, 'validation') !== false) {
        return ['tag' => 'Data Operations', 'class' => 'tag-data'];
    } elseif (strpos($slug, 'moderation') !== false || strpos($slug, 'safety') !== false) {
        return ['tag' => 'Trust & Safety', 'class' => 'tag-content'];
    } elseif (strpos($slug, 'marketing') !== false || strpos($slug, 'seo') !== false) {
        return ['tag' => 'Marketing', 'class' => 'tag-marketing'];
    } else {
        return ['tag' => 'Technology', 'class' => 'tag-tech'];
    }
}
?>

<!-- ─── HERO SECTION ───────────────────────────────────── -->
<section class="premium-hero d-flex align-items-center">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="hero-trust-badge mb-3 text-white">
          <span class="d-inline-block" style="width: 8px; height: 8px; border-radius: 50%; background: var(--orange);"></span>
          <span>Global BPO &amp; Trust Operations</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3 text-uppercase" style="letter-spacing: -0.01em; line-height: 1.15;">
          <?= !empty($hero_headline) ? htmlspecialchars($hero_headline) : 'Scale Your Business Operations Globally' ?>
        </h1>
        <p class="text-light opacity-75 mb-4 fs-6" style="line-height: 1.8; max-width: 520px;">
          Clevora delivers enterprise-grade content moderation, multi-language support, database management, and digital marketing. Achieve zero-latency scaling and unmatched quality.
        </p>
        
        <div class="d-flex flex-wrap gap-3 mb-5">
          <a href="/contact.php" class="btn btn-primary px-4 py-3 text-white" style="background: var(--orange); border: none; font-weight: 700; border-radius: 8px; box-shadow: 0 10px 20px rgba(249,115,22,0.2); text-decoration: none;">
            <?= !empty($hero_cta) ? htmlspecialchars($hero_cta) : 'Get Free Consultation' ?>
          </a>
          <a href="/services.php" class="btn btn-outline-light px-4 py-3" style="border: 1px solid rgba(255,255,255,0.3); font-weight: 700; border-radius: 8px; background: rgba(255,255,255,0.05); text-decoration: none;">
            Explore Services
          </a>
        </div>

        <div class="d-flex align-items-center gap-4 text-white-50 small">
          <div>
            <span class="text-warning">★★★★★</span>
            <span class="d-block text-white fw-semibold">4.8/5 on Google Business</span>
          </div>
          <div style="border-left: 1px solid rgba(255,255,255,0.15); padding-left: 20px;">
            <span class="text-white fw-bold d-block">24/7/365</span>
            <span>Always-On Operations</span>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="hero-skew-card d-none d-lg-block">
          <img src="/assets/images/hero-bg.jpg" alt="Clevora Premium Operations">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── TRUST LOGOS SECTION ─────────────────────────── -->
<section class="trust-logos">
  <div class="container">
    <p class="text-center text-muted small fw-bold text-uppercase mb-4" style="letter-spacing: 0.15em;">Trusted by Industry Leaders Worldwide</p>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4 align-items-center justify-content-center text-center">
      <?php for($x = 1; $x <= 6; $x++): ?>
      <div class="col">
        <img src="/assets/images/client-<?=$x?>.png" alt="Client Partner <?=$x?>" class="logo-item img-fluid">
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ─── STATS STRIP ──────────────────────────────────── -->
<section class="premium-stats-section py-5">
  <div class="container">
    <div class="row g-4 text-center">
      <?php foreach($stats as $st): ?>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-number stat-count" data-target="<?= (int)filter_var($st['value'], FILTER_SANITIZE_NUMBER_INT) ?>" data-suffix="+">0</div>
          <div class="stat-label"><?= htmlspecialchars($st['label']) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── ABOUT US SNIPPET ──────────────────────────────── -->
<section class="py-5 bg-white">
  <div class="container my-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="section-kicker mb-3">Who We Are</span>
        <h2 class="section-title mb-4">Global BPO &amp; Outsourcing Partner Since 2011</h2>
        <p class="text-muted mb-4 fs-6" style="line-height: 1.8;">
          <?= nl2br(htmlspecialchars($about_text)) ?>
        </p>
        <a href="/about-us.php" class="btn btn-outline-primary fw-bold" style="border-radius: 8px; border: 2px solid var(--blue); color: var(--blue); text-decoration: none;">
          Learn More About Us &rarr;
        </a>
      </div>
      <div class="col-lg-6">
        <div class="position-relative">
          <div class="position-absolute bg-primary rounded-4" style="inset: -15px 15px 15px -15px; z-index: 0; opacity: 0.05;"></div>
          <img src="/assets/images/about-home.jpg" alt="Clevora Headquarters" class="img-fluid rounded-4 shadow-lg position-relative" style="z-index: 1; border: 1px solid #e2e8f0;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── SERVICES GRID ────────────────────────────────── -->
<section class="py-5" style="background: #f8fafc;">
  <div class="container my-4">
    <div class="text-center mb-5">
      <span class="section-kicker">Our Services</span>
      <h2 class="section-title mx-auto mt-2">Comprehensive Outsourcing Solutions</h2>
      <p class="text-muted mx-auto" style="max-width: 580px;">Everything your enterprise needs to scale, managed by dedicated operational divisions.</p>
    </div>

    <div class="row g-4">
      <?php foreach($services as $s): 
          $cat = getServiceCategory($s['slug']);
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="premium-service-card">
          <span class="service-tag <?= $cat['class'] ?>"><?= $cat['tag'] ?></span>
          <div class="service-icon-wrapper">
            <?php if(!empty($s['icon_url'])): ?>
            <img src="<?= htmlspecialchars($s['icon_url']) ?>" alt="<?= htmlspecialchars($s['name']) ?>">
            <?php else: ?>
            <span style="color: var(--blue); font-weight: 700; font-size: 20px;">⚙</span>
            <?php endif; ?>
          </div>
          <h3><?= htmlspecialchars($s['name']) ?></h3>
          <p><?= htmlspecialchars($s['intro']) ?></p>
          <a href="/detail-services.php?slug=<?= urlencode($s['slug']) ?>" class="read-more">
            Learn More <span>&rarr;</span>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── GET IN TOUCH (homepage form) ─────────────────── -->
<section class="py-5" style="background: #0f172a; position: relative; overflow: hidden;">
  <div class="container my-4" style="max-width: 800px; position: relative; z-index: 1;">
    <div class="text-center mb-5">
      <span class="section-kicker" style="color: var(--orange);">Inquire</span>
      <h2 class="section-title mx-auto text-white mt-2">Get in Touch</h2>
      <p class="text-white-50 mx-auto" style="max-width: 500px;">Send us a message and our operations managers will reach out within 24 hours.</p>
    </div>

    <form id="home-contact-form" class="row g-3">
      <?php
      $fields = [
        ['name'=>'name',    'type'=>'text',  'placeholder'=>'Full Name'],
        ['name'=>'email',   'type'=>'email', 'placeholder'=>'Email Address'],
        ['name'=>'phone',   'type'=>'tel',   'placeholder'=>'Phone Number'],
        ['name'=>'interest','type'=>'text',  'placeholder'=>'Area of Interest'],
      ];
      foreach($fields as $f):
      ?>
      <div class="col-md-6">
        <input type="<?=$f['type']?>" name="<?=$f['name']?>" class="form-control text-white" 
               placeholder="<?=$f['placeholder']?>" required
               style="background: #1e293b; border: 1px solid #334155; padding: 12px 16px; border-radius: 8px;">
      </div>
      <?php endforeach; ?>
      <div class="col-12">
        <textarea name="message" rows="4" class="form-control text-white" placeholder="Your message..." required
                  style="background: #1e293b; border: 1px solid #334155; padding: 12px 16px; border-radius: 8px; resize: none;"></textarea>
      </div>
      <div class="col-12 text-center mt-4">
        <button type="submit" class="btn btn-primary px-5 py-3 text-white fw-bold" 
                style="background: var(--orange); border: none; font-size: 13px; border-radius: 8px;">
          SUBMIT MESSAGE
        </button>
      </div>
    </form>
    <div id="home-form-msg" style="display:none; margin-top:14px; font-size:13px;"></div>
  </div>
</section>

<!-- ─── WHY CHOOSE US ────────────────────────────────── -->
<section class="py-5 bg-white">
  <div class="container my-4">
    <div class="text-center mb-5">
      <span class="section-kicker">Why Choose Us</span>
      <h2 class="section-title mx-auto mt-2">What Sets Clevora Apart</h2>
      <p class="text-muted mx-auto" style="max-width: 580px;">We combine global standards with customized workflows to deliver premium outcomes.</p>
    </div>

    <div class="row g-4">
      <?php
      $whys = [
        ['🎓','Qualified Experts',     'Certified professionals with domain-specific training across every service vertical.'],
        ['⭐','Workmanship Quality',   'Multi-tier QA ensures output with less than 0.5% error rate on every delivery.'],
        ['🕐','Flexible Schedule',     '24/7/365 operations adapted to your time zone and business requirements.'],
        ['💰','Affordable Packages',   'Enterprise-grade output at SME-friendly pricing with transparent SLAs.'],
        ['🔒','Data Security',         'ISO-aligned protocols, NDAs, and GDPR-aware data handling by default.'],
        ['🤝','Work Ethics',           'Dedicated account managers and a customer-first culture in everything we do.'],
      ];
      foreach($whys as [$icon,$title,$desc]):
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="premium-feature-card">
          <div class="feature-circle-icon">
            <?=$icon?>
          </div>
          <h3 class="fw-bold fs-6 mb-2 text-uppercase" style="letter-spacing: 0.05em;"><?= $title ?></h3>
          <p class="text-muted small mb-0" style="line-height: 1.7;"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── INDUSTRIES WE SERVE ─────────────────────────── -->
<section class="py-5" style="background: #f8fafc;">
  <div class="container my-4">
    <div class="text-center mb-5">
      <span class="section-kicker">Industries</span>
      <h2 class="section-title mx-auto mt-2">Industries We Serve</h2>
      <p class="text-muted mx-auto" style="max-width: 580px;">Tailored operational workflows designed for the unique compliance and scaling needs of your sector.</p>
    </div>

    <div class="row g-4">
      <?php
      $industries = [
        ['🛒', 'Retail & E-Commerce', 'Customer support, product listing validation, review moderation, and catalog management.'],
        ['🏥', 'Healthcare & Biotech', 'Data entry, medical billing documentation, multi-lingual call support, and patient communication.'],
        ['💳', 'Financial Services', 'Account verification, secure form processing, digital onboarding, and ledger management support.'],
        ['📚', 'Publishing & Media', 'Data digitisation, typesetting support, copywriting QA, and multi-format conversions.'],
        ['💻', 'Technology & SaaS', '24/7 technical chat support, API usage logs moderation, customer success, and feedback sorting.'],
        ['📦', 'Logistics & Shipping', 'Order dispatch validation, shipment tracking updates, claims processing, and address standardization.']
      ];
      foreach($industries as [$icon, $title, $desc]):
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="industry-card">
          <div class="industry-icon-box"><?=$icon?></div>
          <h4><?=$title?></h4>
          <p><?=$desc?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── GALLERY ──────────────────────────────────────── -->
<section class="py-5 bg-white">
  <div class="container my-4">
    <div class="text-center mb-5">
      <span class="section-kicker">Our Workspace</span>
      <h2 class="section-title mx-auto mt-2">Operations Center Gallery</h2>
      <p class="text-muted mx-auto" style="max-width: 580px;">Take a look at our secure infrastructure, operations floors, and collaborative workspaces.</p>
    </div>

    <div class="row g-4">
      <?php foreach($gallery as $g): ?>
      <div class="col-md-6 col-lg-4">
        <div class="position-relative overflow-hidden rounded-4 shadow-sm" style="aspect-ratio: 4/3; border: 1px solid #e2e8f0; cursor: pointer;"
             onmouseover="this.querySelector('.gallery-hover-overlay').style.opacity='1'"
             onmouseout="this.querySelector('.gallery-hover-overlay').style.opacity='0'">
          <img src="<?= htmlspecialchars($g['image_url']) ?>" alt="<?= htmlspecialchars($g['caption'] ?? '') ?>" class="w-100 h-100" style="object-fit: cover;">
          <div class="gallery-hover-overlay position-absolute inset-0 d-flex flex-column align-items-center justify-content-center"
               style="background: rgba(37, 99, 235, 0.85); opacity: 0; transition: opacity 0.3s ease;">
            <?php if(!empty($g['caption'])): ?>
            <p class="text-white fw-bold mb-3 px-3 text-center"><?= htmlspecialchars($g['caption']) ?></p>
            <?php endif; ?>
            <a href="<?= htmlspecialchars($g['image_url']) ?>" target="_blank" class="btn btn-light btn-sm fw-bold px-3" style="border-radius: 6px; font-size: 11px;">
              View Image &rarr;
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── TESTIMONIALS ─────────────────────────────────── -->
<section class="py-5" style="background: #f8fafc;">
  <div class="container my-4">
    <div class="text-center mb-5">
      <span class="section-kicker">Testimonials</span>
      <h2 class="section-title mx-auto mt-2">What Our Clients Say</h2>
      <p class="text-muted mx-auto" style="max-width: 580px;">Hear directly from the enterprise teams and startup leaders who work with us daily.</p>
    </div>

    <div class="row g-4">
      <?php
      $avatar_colors = ['#2563eb','#f97316','#059669','#7c3aed','#dc2626'];
      $i = 0;
      foreach($testimonials as $t):
        $initials = strtoupper(substr($t['name'],0,1) . (strpos($t['name'],' ')!==false ? substr(strrchr($t['name'],' '),1,1) : ''));
        $color    = $avatar_colors[$i % count($avatar_colors)];
        $i++;
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="testimonial-quote-card">
          <div>
            <div class="quote-stars">★★★★★</div>
            <div class="quote-text">"<?= htmlspecialchars($t['quote']) ?>"</div>
          </div>
          <div class="quote-user mt-4">
            <?php if(!empty($t['photo_url'])): ?>
            <img src="<?= htmlspecialchars($t['photo_url']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" class="quote-avatar">
            <?php else: ?>
            <div class="quote-avatar" style="background: <?=$color?>;"><?=$initials?></div>
            <?php endif; ?>
            <div class="quote-info">
              <h5><?= htmlspecialchars($t['name']) ?></h5>
              <p><?= htmlspecialchars($t['location']) ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── CONNECT WITH CLEVORA ──────────────────────────── -->
<section class="py-5 bg-white">
  <div class="container my-4">
    <div class="text-center mb-5">
      <span class="section-kicker">Connect</span>
      <h2 class="section-title mx-auto mt-2">Connect With Clevora</h2>
      <p class="text-muted mx-auto" style="max-width: 580px;">Follow us across platforms or get in touch through your preferred channel.</p>
    </div>

    <div class="social-grid">
      <!-- Facebook -->
      <a href="https://www.facebook.com/clevora.India" target="_blank" class="social-card sc-facebook">
        <div class="social-icon-wrapper">f</div>
        <div class="social-meta">
          <h4>Facebook</h4>
          <span>Clevora India</span>
        </div>
      </a>

      <!-- Instagram -->
      <a href="https://www.instagram.com/clevora.india/" target="_blank" class="social-card sc-instagram">
        <div class="social-icon-wrapper">📸</div>
        <div class="social-meta">
          <h4>Instagram</h4>
          <span>@clevora.india</span>
        </div>
      </a>

      <!-- LinkedIn -->
      <a href="https://www.linkedin.com/company/clevoraindia/" target="_blank" class="social-card sc-linkedin">
        <div class="social-icon-wrapper">in</div>
        <div class="social-meta">
          <h4>LinkedIn</h4>
          <span>Clevora Global</span>
        </div>
      </a>

      <!-- WhatsApp -->
      <a href="https://wa.me/919811166666" target="_blank" class="social-card sc-whatsapp">
        <div class="social-icon-wrapper">💬</div>
        <div class="social-meta">
          <h4>WhatsApp</h4>
          <span>Direct Chat</span>
        </div>
      </a>

      <!-- Google Business -->
      <a href="https://www.google.com/search?q=clevora+global+outsourcing+services" target="_blank" class="social-card sc-google">
        <div class="social-icon-wrapper">G</div>
        <div class="social-meta">
          <h4>Google</h4>
          <span>Rate Us Online</span>
        </div>
      </a>

      <!-- Xing -->
      <a href="https://www.xing.com/" target="_blank" class="social-card sc-xing">
        <div class="social-icon-wrapper">X</div>
        <div class="social-meta">
          <h4>Xing</h4>
          <span>European Network</span>
        </div>
      </a>

      <!-- IndiaMart -->
      <a href="https://www.indiamart.com/" target="_blank" class="social-card sc-indiamart">
        <div class="social-icon-wrapper">🛒</div>
        <div class="social-meta">
          <h4>IndiaMart</h4>
          <span>Trade Profile</span>
        </div>
      </a>

      <!-- JustDial -->
      <a href="https://www.justdial.com/Delhi/Clevora-Global-Outsourcing-Services-Llp-Kirti-Nagar/011PXX11.XX11.230713170701.K6W9_BZDET" target="_blank" class="social-card sc-justdial">
        <div class="social-icon-wrapper">📞</div>
        <div class="social-meta">
          <h4>JustDial</h4>
          <span>Delhi Listing</span>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ─── PREMIUM CTA BANNER ───────────────────────────── -->
<section class="py-5 bg-white">
  <div class="container my-4">
    <div class="premium-cta-section px-4">
      <h2>Ready to Optimise Your Business Operations?</h2>
      <p>Partner with Clevora to access dedicated, round-the-clock teams, and scale your support, moderation, and data workflows without overheads.</p>
      <div class="cta-actions">
        <a href="/contact.php" class="cta-btn text-white" style="text-decoration: none;">Get Started Today &rarr;</a>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
