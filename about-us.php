<?php
$pageTitle = 'About Us | Clevora | Global Outsourcing Services';
$metaDesc = 'Learn about Clevora Global Outsourcing Services, established in 2011. Discover our mission, vision, history, and founder.';

$pageBannerTitle = 'ABOUT US';
$pageBannerBreadcrumb = 'About Us';

require_once 'includes/header.php';

$history = setting('about_full_history', $pdo);
$mission = setting('about_mission', $pdo);
$vision  = setting('about_vision', $pdo);
$founder_name = setting('management_founder_name', $pdo);
$founder_role = setting('management_founder_role', $pdo);
$founder_bio  = setting('management_founder_bio', $pdo);

?>

<?php include 'includes/page-banner.php'; ?>


<!-- ─── OUR STORY ────────────────────────────────────── -->
<section style="background:#fff; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Our Story</span>
      <h2 class="section-title">Built to solve the operations bottleneck</h2>
      <p class="section-copy">Clevora was founded with a simple observation: growing businesses were spending too much time and money on operational tasks that didn't need to be done in-house. Data entry, bookkeeping, admin work - critical tasks, but not core competencies.</p>
    </div>

    <!-- The Problem vs Solution Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Problem Card -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01);">
        <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          The Problem
        </h3>
        <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
          Businesses were hiring expensive local staff for repetitive operational work, or worse, pulling their skilled employees away from high-value tasks. The result: burnout, bloated costs, and stalled growth.
        </p>
      </div>

      <!-- Solution Card -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01);">
        <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:12px; font-family:'Poppins', sans-serif;">
          The Solution
        </h3>
        <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
          We built a model that puts US-based management with India-based operations teams. Clients get dedicated, pre-trained specialists who integrate directly into their workflows - at 70% lower cost than hiring locally.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ─── STATS STRIP ──────────────────────────────────── -->
<section style="background:#eff6ff; padding:60px 24px;">
  <div style="max-width:1100px; margin:0 auto;">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
      <div>
        <h3 class="stat-count" data-target="500" data-suffix="+" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">500+</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Team Members</p>
      </div>
      <div>
        <h3 class="stat-count" data-target="70" data-suffix="%" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">70%</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Average Cost Savings</p>
      </div>
      <div>
        <h3 class="stat-count" data-target="99.5" data-suffix="%" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">99.5%</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Accuracy SLA</p>
      </div>
      <div>
        <h3 class="stat-count" data-target="200" data-suffix="+" style="font-size:36px; font-weight:700; color:#1d4ed8; margin-bottom:4px; font-family:'Poppins', sans-serif;">200+</h3>
        <p style="font-size:12px; color:#4b5563; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin:0;">Clients Served</p>
      </div>
    </div>
  </div>
</section>

<!-- ─── OUR VALUES ───────────────────────────────────── -->
<section style="background:#f8f9fc; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Our Values</span>
      <h2 class="section-title">What drives us every day</h2>
      <p class="section-copy">Our values aren't wall posters. They're the operating principles that shape how we hire, how we deliver, and how we grow.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      
      <!-- Card 1 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:32px; display:flex; gap:20px; align-items:start;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:#3b82f6; flex-shrink:0;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:17px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Client-First Delivery
          </h3>
          <p style="font-size:13.5px; color:#4b5563; line-height:1.6; margin:0;">
            Every engagement starts with your goals. We build teams, processes, and workflows around your business - not the other way around.
          </p>
        </div>
      </div>

      <!-- Card 2 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:32px; display:flex; gap:20px; align-items:start;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(244,63,94,0.1); display:flex; align-items:center; justify-content:center; color:#f43f5e; flex-shrink:0;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:17px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Operational Excellence
          </h3>
          <p style="font-size:13.5px; color:#4b5563; line-height:1.6; margin:0;">
            We obsess over accuracy, speed, and consistency. ISO-certified processes, daily QA checks, and continuous improvement are built into every team.
          </p>
        </div>
      </div>

      <!-- Card 3 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:32px; display:flex; gap:20px; align-items:start;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(20,184,166,0.1); display:flex; align-items:center; justify-content:center; color:#14b8a6; flex-shrink:0;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:17px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Transparency & Trust
          </h3>
          <p style="font-size:13.5px; color:#4b5563; line-height:1.6; margin:0;">
            No hidden fees, no surprise invoices, no black-box operations. You get full visibility into your team, their output, and your costs.
          </p>
        </div>
      </div>

      <!-- Card 4 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:32px; display:flex; gap:20px; align-items:start;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:#3b82f6; flex-shrink:0;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:17px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            People Matter
          </h3>
          <p style="font-size:13.5px; color:#4b5563; line-height:1.6; margin:0;">
            We invest in our team members with training, career growth, and fair compensation. Happy teams build better outcomes for our clients.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ─── GLOBAL PRESENCE ──────────────────────────────── -->
<section style="background:#fff; padding:100px 24px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Global Presence</span>
      <h2 class="section-title">Managed Delivery, India-operated</h2>
      <p class="section-copy">Our hybrid model combines the best of both worlds: dedicated account management for communication and quality oversight, with India-based operations teams for cost-efficient delivery.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Card 1 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01); display:flex; flex-direction:column; gap:20px;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:#3b82f6;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Dedicated Account Management
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
            Our dedicated account managers handle client relationships, onboarding oversight, service level agreements (SLAs), and strategic consultation, ensuring seamless communication.
          </p>
        </div>
      </div>

      <!-- Card 2 -->
      <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.01); display:flex; flex-direction:column; gap:20px;">
        <div style="width:40px; height:40px; border-radius:50%; background:rgba(244,63,94,0.1); display:flex; align-items:center; justify-content:center; color:#f43f5e;">
          <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
        </div>
        <div>
          <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            India Delivery Operations
          </h3>
          <p style="font-size:14px; color:#4b5563; line-height:1.6; margin:0;">
            Based in state-of-the-art facilities in Delhi NCR, India. Our teams run 24/7/365 operations, executing support, data operations, and database solutions.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ─── FOUNDER & MANAGEMENT ────────────────────────── -->
<section style="background:#f8f9fc; padding:100px 24px;">
  <div style="max-width:900px; margin:0 auto;">
    <div class="section-head">
      <span class="section-kicker">Founder</span>
      <h2 class="section-title">Led by our leadership team</h2>
    </div>

    <!-- Leader Profile Card -->
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:24px; padding:48px; box-shadow:0 4px 30px rgba(0,0,0,0.02); display:flex; flex-direction:column; md:flex-row; gap:40px; align-items:center;">
      <div style="text-align:center; flex-shrink:0; width:160px;">
        <img src="/assets/images/founder.jpg" alt="<?= htmlspecialchars($founder_name) ?>" loading="lazy" style="width:140px; height:140px; border-radius:50%; object-fit:cover; margin-bottom:16px; border:4px solid #f1f5f9;">
        <h3 style="font-size:18px; font-weight:600; color:#0f172a; margin-bottom:4px; font-family:'Poppins', sans-serif;"><?= htmlspecialchars($founder_name) ?></h3>
        <p style="font-size:12px; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin:0;"><?= htmlspecialchars($founder_role) ?></p>
      </div>
      
      <div style="flex:1; border-top:1px solid #e2e8f0; md:border-top:none; md:border-left:1px solid #e2e8f0; padding-top:24px; md:padding-top:0; md:padding-left:40px;">
        <p style="font-size:14.5px; color:#4b5563; line-height:1.8; margin:0; white-space:pre-line;">
          <?= htmlspecialchars($founder_bio) ?>
        </p>
      </div>
    </div>
  </div>
</section>


<?php require_once 'includes/footer.php'; ?>
