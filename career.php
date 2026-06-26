<?php
require_once 'includes/db.php';

function career_setting(string $key, ?PDO $pdo): string {
    if (!$pdo) {
        return get_default_career_setting($key);
    }
    try {
        $stmt = $pdo->prepare("SELECT section_value FROM career_settings WHERE section_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : get_default_career_setting($key);
    } catch (Exception $e) {
        return get_default_career_setting($key);
    }
}

function get_default_career_setting(string $key): string {
    $defaults = [
        'hero_label' => 'Clevora Global Operations',
        'hero_title' => 'Careers',
        'main_heading' => 'Build Your Career With Clevora',
        'intro_text' => 'Join a growing outsourcing company where talented people work with global businesses and develop professional skills.',
        'apply_heading' => 'Ready to join us?',
        'apply_text' => 'Send your resume and a brief cover letter to <a href="mailto:info@clevora.in" style="color:#2563eb; font-weight:600; text-decoration:none; border-bottom:1px solid #bfdbfe;">info@clevora.in</a>. Include the role you\'re applying for in the subject line. We\'ll get back to you within 3 business days.',
        'benefit_cards' => '[]'
    ];
    return $defaults[$key] ?? '';
}

// Fetch dynamic career settings
$hero_label  = career_setting('hero_label', $pdo);
$hero_title  = career_setting('hero_title', $pdo);
$main_heading = career_setting('main_heading', $pdo);
$intro_text  = career_setting('intro_text', $pdo);
$apply_heading = career_setting('apply_heading', $pdo);
$apply_text  = career_setting('apply_text', $pdo);

$pageTitle = 'Careers at Clevora | Build Your Future With Global Operations';
$metaDesc = 'Join Clevora and work with global clients across outsourcing, customer support, operations and digital services.';

$pageBannerTitle = strtoupper($hero_title ?: 'Careers');
$pageBannerBreadcrumb = $hero_title ?: 'Careers';

require_once 'includes/header.php';
?>

<?php include 'includes/page-banner.php'; ?>

<!-- ─── WHY WORK WITH US ──────────────────────────────── -->
<section style="background:#fff; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker"><?= htmlspecialchars($hero_label) ?></span>
      <h2 class="section-title"><?= htmlspecialchars($main_heading) ?></h2>
      <p class="section-copy"><?= htmlspecialchars($intro_text) ?></p>
    </div>

    <!-- 2x2 Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <?php
      $benefits_json = career_setting('benefit_cards', $pdo);
      $benefits = json_decode($benefits_json, true);
      if (empty($benefits)) {
          $benefits = [
              [
                  'title' => 'Work with Global Clients',
                  'description' => 'Collaborate with international businesses across e-commerce, finance, healthcare and technology.'
              ],
              [
                  'title' => 'Training & Growth',
                  'description' => 'Receive structured onboarding and continuous skill development opportunities.'
              ],
              [
                  'title' => 'Collaborative Culture',
                  'description' => 'Work in a supportive environment built around teamwork and innovation.'
              ],
              [
                  'title' => 'Competitive Compensation',
                  'description' => 'Get rewarding career opportunities with fair compensation.'
              ]
          ];
      }

      $card_styles = [
          [
              'color' => '#3b82f6',
              'bg' => 'rgba(59,130,246,0.1)',
              'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>'
          ],
          [
              'color' => '#f43f5e',
              'bg' => 'rgba(244,63,94,0.1)',
              'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>'
          ],
          [
              'color' => '#14b8a6',
              'bg' => 'rgba(20,184,166,0.1)',
              'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>'
          ],
          [
              'color' => '#3b82f6',
              'bg' => 'rgba(59,130,246,0.1)',
              'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
          ]
      ];

      foreach ($benefits as $idx => $b):
          $style = $card_styles[$idx % count($card_styles)];
      ?>
      <!-- Card -->
      <div style="background:#fff; border:1px solid #f1f5f9; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.02); display:flex; flex-direction:column; gap:20px;">
        <div style="width:44px; height:44px; border-radius:50%; background:<?= $style['bg'] ?>; display:flex; align-items:center; justify-content:center; color:<?= $style['color'] ?>;">
          <svg style="width:22px; height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <?= $style['svg'] ?>
          </svg>
        </div>
        <div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            <?= htmlspecialchars($b['title']) ?>
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
            <?= htmlspecialchars($b['description']) ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── CURRENT OPENINGS ──────────────────────────────── -->
<section id="open-positions" style="background:#f8f9fc; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Open Positions</span>
      <h2 class="section-title">Current openings</h2>
      <p class="section-copy">We're growing fast and looking for talented people to join our team. See if there's a role that fits you.</p>
    </div>

    <!-- 2x2 Grid for Jobs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php
      $jobs = [];
      if ($pdo) {
          try {
              $jobs = $pdo->query("SELECT * FROM careers WHERE is_active = 1 ORDER BY sort_order ASC, id DESC")->fetchAll();
          } catch (Exception $e) {
              error_log('Careers fetch failed: ' . $e->getMessage());
          }
      }

      if (empty($jobs)) {
          $jobs = [
              [
                  'job_title' => 'Business Development Associate / Sr Associate',
                  'location' => 'India',
                  'job_type' => 'Full-Time',
                  'short_description' => 'Responsible for identifying opportunities, managing client communication and supporting business growth.'
              ],
              [
                  'job_title' => 'Ecommerce Specialist / Assistant Manager',
                  'location' => 'India',
                  'job_type' => 'Full-Time',
                  'short_description' => 'Manage marketplace operations, listings, orders and online business workflows.'
              ],
              [
                  'job_title' => 'Human Resource Manager / Assistant Manager',
                  'location' => 'India',
                  'job_type' => 'Full-Time',
                  'short_description' => 'Handle recruitment, employee processes and HR operations.'
              ],
              [
                  'job_title' => 'Virtual Assistant',
                  'location' => 'India',
                  'job_type' => 'Full-Time',
                  'short_description' => 'Provide remote administrative and operational support.'
              ]
          ];
      }

      foreach ($jobs as $j):
      ?>
      <!-- Job Card -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:32px; box-shadow:0 4px 20px rgba(0,0,0,0.01); display:flex; flex-direction:column; justify-content:between; height:100%;">
        <div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
            <?= htmlspecialchars($j['job_title']) ?>
          </h3>
          <div style="display:flex; gap:8px; margin-bottom:16px;">
            <span style="font-size:10px; font-weight:700; color:#2563eb; background:#eff6ff; padding:2px 8px; border-radius:4px; letter-spacing:0.5px; text-transform:uppercase;"><?= htmlspecialchars($j['location']) ?></span>
            <span style="font-size:10px; font-weight:700; color:#4b5563; background:#f1f5f9; padding:2px 8px; border-radius:4px; letter-spacing:0.5px; text-transform:uppercase;"><?= htmlspecialchars($j['job_type']) ?></span>
          </div>
          <p style="font-size:13.5px; color:#4b5563; line-height:1.6; margin:0;">
            <?= htmlspecialchars($j['short_description']) ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── READY TO JOIN US ──────────────────────────────── -->
<section style="background:#fff; padding:100px 24px;">
  <div style="max-width:800px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">How to Apply</span>
      <h2 class="section-title"><?= htmlspecialchars($apply_heading) ?></h2>
      <p class="section-copy"><?= $apply_text ?></p>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
