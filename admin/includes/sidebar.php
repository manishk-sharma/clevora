<?php
$curFile = basename($_SERVER['PHP_SELF']);
$curDir = basename(dirname($_SERVER['PHP_SELF']));

// Define sidebar structure with groups
$sidebar = [
  'DASHBOARD' => [
    ['Dashboard', '/admin/dashboard.php', '📊'],
  ],
  'WEBSITE CONTENT' => [
    ['Home Page',          '/admin/sections/home-about.php',        '🏠'],
    ['About Page',         '/admin/sections/about.php',             'ℹ️'],
    ['Services',           '/admin/sections/services.php',          '⚙️'],
    ['Service Categories', '/admin/sections/service-categories.php','📁'],
    ['Technology Management', '/admin/sections/technology.php',     '💻'],
    ['Gallery',            '/admin/sections/gallery.php',           '🖼️'],
  ],
  'BUSINESS' => [
    ['Leads / Inquiries',  '/admin/sections/leads.php',            '📨'],
    ['Clients',            '/admin/sections/clients.php',          '👥'],
    ['Testimonials',       '/admin/sections/testimonials.php',     '💬'],
  ],
  'COMPANY' => [
    ['Founder / Management', '/admin/sections/management.php',     '👤'],
    ['Careers Management',   '/admin/sections/careers.php',        '💼'],
    ['Career Settings',      '/admin/sections/career-settings.php','📝'],
    ['Contact Settings',     '/admin/sections/contact-settings.php','✉️'],
  ],
  'SYSTEM' => [
    ['SEO Settings',     '/admin/sections/seo.php',                '🔍'],
    ['Media Library',    '/admin/sections/media.php',              '📂'],
    ['Content Moderation', '/admin/sections/content-moderation.php','🛡️'],
    ['Admin Profile',    '/admin/sections/profile.php',            '🔑'],
    ['Logout',           '/admin/logout.php',                      '🚪'],
  ],
];
?>
<!-- SIDEBAR -->
<aside style="width:240px; background:#fff; border-right:1px solid #e8eaf0;
              min-height:100vh; display:flex; flex-direction:column; flex-shrink:0;
              position:sticky; top:0; height:100vh; overflow-y:auto;"
       x-data="{ openGroups: { 'WEBSITE CONTENT': true, 'BUSINESS': true, 'COMPANY': true, 'SYSTEM': true } }">

  <!-- Logo -->
  <div style="padding:18px 20px; border-bottom:1px solid #e8eaf0;">
    <a href="/admin/dashboard.php" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
      <img src="/assets/images/logo.png" alt="Clevora" style="height:32px;">
      <span style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:1px;">CMS</span>
    </a>
  </div>

  <nav style="flex:1; padding:8px 0;">
    <?php foreach($sidebar as $groupLabel => $items): ?>

    <?php if($groupLabel === 'DASHBOARD'): ?>
      <!-- Dashboard — no collapsible -->
      <?php foreach($items as [$label, $href, $icon]):
        $isActive = ($curFile === basename($href));
      ?>
      <a href="<?=$href?>"
         style="display:flex; align-items:center; gap:10px; padding:11px 18px; margin:2px 8px; border-radius:8px;
                font-size:12px; text-decoration:none; font-weight:600; transition:all .15s;
                <?=$isActive
                  ? 'background:#eff6ff; color:#2563eb;'
                  : 'color:#4b5563;'?>"
         onmouseover="<?=$isActive?'':'this.style.background=\'#f8f9fc\';this.style.color=\'#2563eb\''?>"
         onmouseout="<?=$isActive?'':'this.style.background=\'\';this.style.color=\'#4b5563\''?>">
        <span style="width:22px; height:22px; border-radius:6px; background:<?=$isActive?'#dbeafe':'#f0f2f8'?>;
                     display:flex; align-items:center; justify-content:center;
                     font-size:12px; flex-shrink:0;"><?=$icon?></span>
        <?=$label?>
      </a>
      <?php endforeach; ?>

    <?php else: ?>
      <!-- Collapsible Group -->
      <div style="margin-top:6px;">
        <button @click="openGroups['<?=$groupLabel?>'] = !openGroups['<?=$groupLabel?>']"
                style="width:100%; display:flex; align-items:center; justify-content:space-between;
                       padding:6px 18px; margin:0; border:none; background:none; cursor:pointer;
                       font-size:10px; font-weight:700; color:#9ca3af; letter-spacing:.8px;
                       text-transform:uppercase; text-align:left;">
          <span><?=$groupLabel?></span>
          <span style="font-size:10px; transition:transform .2s;"
                :style="openGroups['<?=$groupLabel?>'] ? 'transform:rotate(0)' : 'transform:rotate(-90deg)'"
          >▼</span>
        </button>
        <div x-show="openGroups['<?=$groupLabel?>']" x-collapse>
          <?php foreach($items as [$label, $href, $icon]):
            $isActive = ($curFile === basename($href));
          ?>
          <a href="<?=$href?>"
             style="display:flex; align-items:center; gap:10px; padding:9px 18px; margin:1px 8px; border-radius:8px;
                    font-size:12px; text-decoration:none; transition:all .15s;
                    <?=$isActive
                      ? 'background:#eff6ff; color:#2563eb; font-weight:600;'
                      : 'color:#4b5563;'?>"
             onmouseover="<?=$isActive?'':'this.style.background=\'#f8f9fc\';this.style.color=\'#2563eb\''?>"
             onmouseout="<?=$isActive?'':'this.style.background=\'\';this.style.color=\'#4b5563\''?>">
            <span style="width:22px; height:22px; border-radius:6px; background:<?=$isActive?'#dbeafe':'#f0f2f8'?>;
                         display:flex; align-items:center; justify-content:center;
                         font-size:11px; flex-shrink:0;"><?=$icon?></span>
            <?=$label?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php endforeach; ?>
  </nav>

  <!-- Footer -->
  <div style="padding:14px 18px; border-top:1px solid #e8eaf0; font-size:10px; color:#9ca3af;">
    &copy; <?= date('Y') ?> Clevora CMS
  </div>
</aside>
