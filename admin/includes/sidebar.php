<?php
$curFile = basename($_SERVER['PHP_SELF']);
$sections = [
  'HOME'    => [['Dashboard','/admin/dashboard.php','📊']],
  'CONTENT' => [
    ['Add Service','/admin/sections/add-service.php','➕'],
    ['Services','/admin/sections/services.php','⚙'],
    ['About Us','/admin/sections/about.php','ℹ'],
    ['Gallery','/admin/sections/gallery.php','🖼'],
    ['Testimonial','/admin/sections/testimonials.php','💬'],
    ['Client','/admin/sections/clients.php','👥'],
    ['Home About Us','/admin/sections/home-about.php','🏠'],
    ['Technology Page','/admin/sections/technology.php','💻'],
    ['Content Moderation','/admin/sections/content-moderation.php','🛡'],
    ['Our Management','/admin/sections/management.php','👤'],
    ['Contact Page','/admin/sections/contact-settings.php','✉'],
  ],
  'AUTH'    => [['Log out','/admin/logout.php','🚪']],
];
?>
<!-- WHITE SIDEBAR -->
<aside style="width:230px; background:#fff; border-right:1px solid #e8eaf0;
              min-height:100vh; display:flex; flex-direction:column; flex-shrink:0;">

  <div style="padding:16px 18px; border-bottom:1px solid #e8eaf0;">
    <img src="/assets/images/logo.png" alt="Clevora" style="height:36px; margin: 0 auto;">
  </div>

  <nav style="flex:1; padding:12px 0; overflow-y:auto;">
    <?php
    foreach($sections as $groupLabel => $items):
    ?>
    <p style="font-size:10px; font-weight:700; color:#9ca3af; letter-spacing:.8px;
              padding:0 16px; margin:14px 0 6px; text-transform:uppercase;">
      <?=$groupLabel?>
    </p>
    <?php foreach($items as [$label,$href,$icon]):
      $isActive = ($curFile === basename($href));
    ?>
    <a href="<?=$href?>"
       style="display:flex; align-items:center; gap:9px; padding:10px 16px; margin-bottom:2px;
              font-size:12px; text-decoration:none; transition:all .15s;
              <?=$isActive
                ? 'background:#eff6ff; color:#2563eb; font-weight:600; border-left:3px solid #2563eb;'
                : 'color:#4b5563; border-left:3px solid transparent;'?>"
       onmouseover="<?=$isActive?'':'this.style.background=\'#f8f9fc\';this.style.color=\'#2563eb\''?>"
       onmouseout="<?=$isActive?'':'this.style.background=\'\';this.style.color=\'#4b5563\''?>">
      <span style="width:20px; height:20px; border-radius:5px; background:#f0f2f8;
                   display:flex; align-items:center; justify-content:center;
                   font-size:11px; flex-shrink:0;">
        <?=$icon?>
      </span>
      <?=$label?>
    </a>
    <?php endforeach; endforeach; ?>
  </nav>
</aside>
