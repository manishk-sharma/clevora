<?php
$pageTitle = 'Technology & Infrastructure | Clevora | Global Outsourcing Services';
$metaDesc = 'Explore Clevora\'s call center technology, secure IT systems, server redundancy protocols, and digital security standards.';

$pageBannerTitle = 'TECHNOLOGY';
$pageBannerBreadcrumb = 'Technology';

require_once 'includes/header.php';

$infrastructure = setting('tech_infrastructure', $pdo);
?>

<!-- ─── TOP INFRASTRUCTURE HERO ───────────────────────── -->
<section style="background:#fff; padding:80px 24px;">
  <div style="max-width:1200px; margin:0 auto; display:flex; gap:48px; align-items:center; flex-wrap:wrap;">
    
    <!-- Left Column: Description -->
    <div style="flex:1.2; min-width:320px;" class="space-y-6">
      <span style="display:inline-block; background:#eff6ff; color:#3b82f6;
                   font-size:11px; font-weight:700; padding:6px 16px;
                   border-radius:9999px; letter-spacing:1px; text-transform:uppercase; margin-bottom:8px;">
        Our Capabilities
      </span>
      <h1 style="font-size:clamp(32px, 5vw, 48px); font-weight:700; color:#0f172a; line-height:1.2; font-family:'Poppins', sans-serif; letter-spacing:-0.02em; margin-top:0;">
        Robust Infrastructure & Support Systems
      </h1>
      <p style="font-size:15px; color:#4b5563; line-height:1.9; white-space:pre-line; margin:0;">
        Our production servers are housed in a Tier-3 secure data center in Delhi. We maintain redundant fiber-optic connectivity from multiple internet service providers, automated daily backup protocols, 
        and UPS backup battery arrays alongside on-site diesel generators to guarantee 99.9% network availability. Physical access to our production rooms is restricted with biometric authorization.
      </p>
    </div>

    <!-- Right Column: Interactive Tech Mockup -->
    <div style="flex:1; min-width:320px; display:flex; justify-content:center;">
      <div style="background:#f8f9fc; border:1px solid #e2e8f0; border-radius:24px; padding:32px; width:100%; max-width:480px; box-shadow:0 10px 30px rgba(0,0,0,0.02); position:relative; overflow:hidden;">
        <!-- Glowing decoration orb -->
        <div style="position:absolute; -top:50px; -right:50px; width:150px; height:150px; border-radius:50%; background:rgba(59,130,246,0.1); filter:blur(40px); z-index:0;"></div>
        
        <div style="position:relative; z-index:1; text-align:center;" class="space-y-6">
          <div style="width:72px; height:72px; border-radius:50%; background:rgba(37,99,235,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto; color:#2563eb; font-size:32px;">
            
          </div>
          <h3 style="font-size:20px; font-weight:700; color:#0f172a; font-family:'Poppins', sans-serif; margin-bottom:8px;">Information Security</h3>
          <p style="font-size:13.5px; color:#64748b; line-height:1.7; margin:0 auto; max-width:340px;">
            We deploy enterprise firewalls, end-to-end data encryption, and secure local area networks to safeguard client data.
          </p>
          <div style="display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; padding:8px 16px; border-radius:9999px; font-size:12px; color:#10b981; font-weight:600; box-shadow:0 2px 8px rgba(0,0,0,0.02);">
            <span style="width:8px; height:8px; border-radius:50%; background:#10b981; display:inline-block; animation:pulse 2s infinite;"></span>
            Security Standard Certified
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ─── TECHNOLOGY STANDARDS (Dark Background) ───────── -->
<section style="background:#0f172a; padding:100px 24px; color:#fff;">
  <div style="max-width:1200px; margin:0 auto;">
    
    <div style="text-align:center; margin-bottom:64px;">
      <span style="display:inline-block; background:rgba(255,255,255,0.08); color:#fff;
                   font-size:11px; font-weight:700; padding:6px 18px;
                   border-radius:9999px; letter-spacing:1px; margin-bottom:20px; text-transform:uppercase;">
        Redundancy & Uptime
      </span>
      <h2 style="font-size:clamp(32px, 4.5vw, 42px); font-weight:700; color:#fff; margin-bottom:20px; font-family:'Poppins', sans-serif;">
        Our Technology & Security Standards
      </h2>
      <p style="font-size:16px; color:#94a3b8; max-width:800px; margin:0 auto; line-height:1.7;">
        We deliver 99.9% network uptime guarantees and enterprise-grade security protocols by default.
      </p>
    </div>

    <!-- 3-Column Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
      
      <!-- Metric Card 1 -->
      <div style="background:#1e293b; border:1px solid #334155; border-radius:24px; padding:48px 32px; display:flex; flex-direction:column; gap:20px;">
        <div>
          <h4 style="font-size:16px; font-weight:600; color:#cbd5e1; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Uptime SLA Guarantee
          </h4>
          <p style="font-size:13.5px; color:#94a3b8; line-height:1.6; margin:0;">
            Dual online UPS systems coupled with dynamic diesel generators ensure zero operating downtime during power grid fluctuations.
          </p>
        </div>
      </div>

      <!-- Metric Card 2 -->
      <div style="background:#1e293b; border:1px solid #334155; border-radius:24px; padding:48px 32px; display:flex; flex-direction:column; gap:20px;">
        <div>
          <h4 style="font-size:16px; font-weight:600; color:#cbd5e1; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Data Encryption Standard
          </h4>
          <p style="font-size:13.5px; color:#94a3b8; line-height:1.6; margin:0;">
            Multiple redundant gigabit fiber pipelines from distinct ISPs, routed through automated failover network load balancers.
          </p>
        </div>
      </div>

      <!-- Metric Card 3 -->
      <div style="background:#1e293b; border:1px solid #334155; border-radius:24px; padding:48px 32px; display:flex; flex-direction:column; gap:20px;">
        <div>
          <h4 style="font-size:16px; font-weight:600; color:#cbd5e1; margin-bottom:8px; font-family:'Poppins', sans-serif;">
            Secure Facility Operations
          </h4>
          <p style="font-size:13.5px; color:#94a3b8; line-height:1.6; margin:0;">
            Biometric door lock systems, continuous 24/7 internal CCTV surveillance, and strict NDA-compliant production floor layouts.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
