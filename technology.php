<?php
$pageTitle = 'Technology & Secure Operations Infrastructure | Clevora';
$metaDesc = 'Discover Clevora’s secure infrastructure, workflow systems, quality monitoring and scalable outsourcing technology.';

$pageBannerTitle = 'TECHNOLOGY';
$pageBannerBreadcrumb = 'Technology';
require_once 'includes/header.php';
include 'includes/page-banner.php';

$infrastructure = setting('tech_infrastructure', $pdo);
?>

<!-- ─── TOP INFRASTRUCTURE HERO ───────────────────────── -->
<section style="background:#fff; padding:80px 24px;">
  <div style="max-width:1200px; margin:0 auto; display:flex; gap:48px; align-items:center; flex-wrap:wrap;">
    
    <div style="flex:1.2; min-width:320px;" class="space-y-4">
      <div style="margin-bottom:16px;">
        <span class="section-kicker">Our Capabilities</span>
        <h2 class="section-title" style="margin-top:8px;">Secure Operations & Technology Infrastructure</h2>
      </div>
      <p class="section-copy" style="white-space:pre-line;">
        Clevora utilizes enterprise-grade infrastructure to host and manage BPO pipelines. Our operations are housed in secure facilities featuring redundant fiber-optic lines, automated failover load balancers, and backup power grids. We deploy workflow management systems and multi-tier quality monitoring channels to ensure high compliance, performance security, and zero downtime for our clients.
      </p>
    </div>

    <!-- Right Column: Interactive Tech Mockup -->
    <div style="flex:1; min-width:320px; display:flex; justify-content:center;">
      <div style="background:#f8f9fc; border:1px solid #e2e8f0; border-radius:24px; padding:32px; width:100%; max-width:480px; box-shadow:0 10px 30px rgba(0,0,0,0.02); position:relative; overflow:hidden;">
        <!-- Glowing decoration orb -->
        <div style="position:absolute; -top:50px; -right:50px; width:150px; height:150px; border-radius:50%; background:rgba(59,130,246,0.1); filter:blur(40px); z-index:0;"></div>
        
        <div style="position:relative; z-index:1; text-align:center;" class="space-y-6">
          <div style="width:72px; height:72px; border-radius:50%; background:rgba(37,99,235,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto; color:#2563eb; font-size:32px;">
            <i class="fa-solid fa-lock"></i>
          </div>
          <h3 style="font-size:20px; font-weight:700; color:#0f172a; font-family:'Poppins', sans-serif; margin-bottom:8px;">Data Protection & Security</h3>
          <p style="font-size:13.5px; color:#64748b; line-height:1.7; margin:0 auto; max-width:340px;">
            We implement database encryption, isolated network segments, strict access controls, and non-disclosure protocols to protect customer info.
          </p>
          <div style="display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; padding:8px 16px; border-radius:9999px; font-size:12px; color:#10b981; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.02);">
            <span style="width:8px; height:8px; border-radius:50%; background:#10b981; display:inline-block; animation:pulse 2s infinite;"></span>
            Security & Compliance Assured
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ─── TECHNOLOGY STANDARDS (Dark Background) ───────── -->
<section style="background:#0f172a; padding:100px 24px; color:#fff;">
  <div style="max-width:1200px; margin:0 auto;">
    
    <div class="section-head">
      <span class="section-kicker" style="color:#60a5fa;">Quality & Operations</span>
      <h2 class="section-title" style="color:#fff;">Operational Technology Standards</h2>
      <p class="section-copy" style="color:#94a3b8;">Integrating secure networks, structured workflows, and quality monitoring channels for enterprise delivery.</p>
    </div>

    <!-- 3-Column Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
      
      <!-- Metric Card 1 -->
      <div style="background:#1e293b; border:1px solid #334155; border-radius:24px; padding:48px 32px; display:flex; flex-direction:column; gap:20px;">
        <div>
          <h4 style="font-size:16px; font-weight:600; color:#cbd5e1; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Scalable Support Infrastructure
          </h4>
          <p style="font-size:13.5px; color:#94a3b8; line-height:1.6; margin:0;">
            Features cloud dialer configurations, ticket queues (Zendesk, Freshdesk), 24/7 timezone coverage, and scalable seat deployments.
          </p>
        </div>
      </div>

      <!-- Metric Card 2 -->
      <div style="background:#1e293b; border:1px solid #334155; border-radius:24px; padding:48px 32px; display:flex; flex-direction:column; gap:20px;">
        <div>
          <h4 style="font-size:16px; font-weight:600; color:#cbd5e1; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Quality Monitoring & Audits
          </h4>
          <p style="font-size:13.5px; color:#94a3b8; line-height:1.6; margin:0;">
            Features dual-stage QA check systems, agent call recording audits, file verification logs, and weekly performance reviews.
          </p>
        </div>
      </div>

      <!-- Metric Card 3 -->
      <div style="background:#1e293b; border:1px solid #334155; border-radius:24px; padding:48px 32px; display:flex; flex-direction:column; gap:20px;">
        <div>
          <h4 style="font-size:16px; font-weight:600; color:#cbd5e1; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Workflow Management Systems
          </h4>
          <p style="font-size:13.5px; color:#94a3b8; line-height:1.6; margin:0;">
            Features automated task trackers, secure dashboard logging, SLA alert triggers, and real-time operations status reports.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
