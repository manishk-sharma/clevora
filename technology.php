<?php
$pageTitle = 'Technology & Infrastructure | Clevora | Global Outsourcing Services';
$metaDesc = 'Explore Clevora\'s call center technology, secure IT systems, server redundancy protocols, and digital security standards.';

$pageBannerTitle = 'TECHNOLOGY';
$pageBannerBreadcrumb = 'Technology';

require_once 'includes/header.php';
include 'includes/page-banner.php';

$infrastructure = setting('tech_infrastructure', $pdo);
?>

<div style="max-width:1200px; margin:0 auto; padding:48px 24px;" class="space-y-16">
  <!-- Infrastructure Description -->
  <div style="display:flex; gap:48px; align-items:center; flex-wrap:wrap;">
    <div style="flex:1; min-width:300px;" class="space-y-6">
      <div style="margin-bottom:20px;">
        <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                     font-size:11px; font-weight:600; padding:4px 14px;
                     border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
          OUR CAPABILITIES
        </span>
        <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
          Robust Infrastructure
        </h2>
        <div style="width:48px; height:3px; background:#2563eb; border-radius:2px; margin-top:10px;"></div>
      </div>
      <p style="font-size:13px; color:#4b5563; line-height:1.9; whitespace: pre-line;">
        <?= htmlspecialchars($infrastructure) ?>
      </p>
    </div>
    <div style="flex:1; min-width:300px; background:#f8f9fc; border:1px solid #e8eaf0; border-radius:14px; padding:32px; text-align:center;">
      <div style="font-size:40px; margin-bottom:12px;">🔒</div>
      <h3 style="font-size:16px; font-weight:700; color:#0f172a; font-family:'Poppins',sans-serif; margin-bottom:8px;">Information Security</h3>
      <p style="font-size:12px; color:#6b7280; line-height:1.7; max-width:280px; margin:0 auto;">We deploy enterprise firewalls, end-to-end data encryption, and secure local area networks to safeguard client data.</p>
    </div>
  </div>

  <!-- Tech Grid Cards -->
  <div class="space-y-8">
    <div style="text-align:center; margin-bottom:40px;">
      <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                   font-size:11px; font-weight:600; padding:4px 14px;
                   border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
        OPERATIONS SECURITY
      </span>
      <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
        Systems & Redundancy
      </h2>
      <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
        We deliver 99.9% uptime guarantees.
      </p>
      <div style="width:48px; height:3px; background:#2563eb;
                  border-radius:2px; margin:12px auto 0;"></div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;" class="grid grid-cols-1 md:grid-cols-3">
      <!-- Power Backups -->
      <div style="background:#f8f9fc; border:1px solid #e8eaf0; border-radius:14px; padding:32px; display:flex; flex-direction:column; gap:12px;">
        <div style="font-size:32px;">⚡</div>
        <h4 style="font-size:14px; font-weight:700; color:#0f172a; font-family:'Poppins',sans-serif;">Continuous Power</h4>
        <p style="font-size:12px; color:#6b7280; line-height:1.7;">Dual online UPS systems coupled with dynamic diesel generators ensure zero operating downtime during power grid fluctuations.</p>
      </div>
      <!-- Network Bandwidth -->
      <div style="background:#f8f9fc; border:1px solid #e8eaf0; border-radius:14px; padding:32px; display:flex; flex-direction:column; gap:12px;">
        <div style="font-size:32px;">🌐</div>
        <h4 style="font-size:14px; font-weight:700; color:#0f172a; font-family:'Poppins',sans-serif;">High Bandwidth</h4>
        <p style="font-size:12px; color:#6b7280; line-height:1.7;">Multiple redundant gigabit fiber pipelines from distinct ISPs, routed through automated failover network load balancers.</p>
      </div>
      <!-- Physical Security -->
      <div style="background:#f8f9fc; border:1px solid #e8eaf0; border-radius:14px; padding:32px; display:flex; flex-direction:column; gap:12px;">
        <div style="font-size:32px;">🛡️</div>
        <h4 style="font-size:14px; font-weight:700; color:#0f172a; font-family:'Poppins',sans-serif;">Secure Facility</h4>
        <p style="font-size:12px; color:#6b7280; line-height:1.7;">Biometric door lock systems, continuous 24/7 internal CCTV surveillance, and strict NDA-compliant production floor layouts.</p>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
