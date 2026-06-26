<?php if (!isset($hide_footer_cta) || !$hide_footer_cta): 
  $cta_heading = setting('cta_heading', $pdo) ?: 'Ready to become our next success story?';
  $cta_text = setting('cta_text', $pdo) ?: 'Tell us about your operational challenge. We will help build a scalable outsourcing solution.';
  $cta_btn_text = setting('cta_button_text', $pdo) ?: 'Get a Free Quote';
  $cta_btn_url = setting('cta_button_url', $pdo) ?: 'contact.php';
?>
<!-- ─── GET IN TOUCH CTA ──────────────────────────────── -->
<section style="background:#1d4ed8; padding:80px 24px; color:#fff; text-align:center;">
  <div style="max-width:800px; margin:0 auto;">
    <h2 style="font-size:clamp(26px, 4.5vw, 42px); font-weight:700; color:#fff; margin-bottom:16px; font-family:'Poppins', sans-serif; letter-spacing:-0.01em;">
      <?= htmlspecialchars($cta_heading) ?>
    </h2>
    <p style="font-size:clamp(15px, 2vw, 18px); color:#bfdbfe; max-width:600px; margin:0 auto 36px auto; line-height:1.6;">
      <?= htmlspecialchars($cta_text) ?>
    </p>
    <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap; margin-bottom:24px;">
      <a href="<?= htmlspecialchars((str_starts_with($cta_btn_url, '/') || str_starts_with($cta_btn_url, 'http')) ? $cta_btn_url : '/' . $cta_btn_url) ?>" style="display:inline-block; background:#db6060; color:#fff; font-size:14px; font-weight:600; padding:12px 36px; border-radius:9999px; text-decoration:none; box-shadow:0 4px 14px rgba(202, 37, 37, 0.3); transition:background 0.2s;" onmouseover="this.style.background='#a20e0eff'" onmouseout="this.style.background='#b41111ff'">
        <?= htmlspecialchars($cta_btn_text) ?>
      </a>
    </div>
    <p style="font-size:12px; color:#93c5fd; margin:0; opacity:0.8;">
      No commitment required. We respond.
    </p>
  </div>
</section>
<?php endif; ?>

<footer class="site-footer">
  <div class="site-footer__grid">
    <div>
      <a class="site-footer__brand" href="/" aria-label="Clevora home">
        <img class="site-footer__logo" src="/assets/images/logo.png" alt="Clevora">
      </a>
      <p>
        Clevora is a Delhi-based global outsourcing partner providing customer experience, content operations, data management, finance, HR and digital business solutions since 2011.
      </p>
      <div class="site-footer__contact">
        <span><?= htmlspecialchars(setting('contact_phone',$pdo)) ?></span>
        <span><?= htmlspecialchars(setting('contact_email',$pdo)) ?></span>
        <span><?= htmlspecialchars(setting('contact_address', $pdo)) ?></span>
      </div>
    </div>

    <div>
      <h4>Quick Links</h4>
      <?php
      $quick = [
        'Home' => '/',
        'About Us' => '/about-us.php',
        'Services' => '/services.php',
        'Our Clients' => '/clients.php',
        'Technology' => '/technology.php',
        'Careers' => '/career.php',
        'Contact Us' => '/contact.php'
      ];
      foreach($quick as $label => $href):
      ?>
      <a href="<?=$href?>"><?=$label?></a>
      <?php endforeach; ?>
    </div>

    <div>
      <h4>Top Services</h4>
      <?php
      $services_footer = [
        'Foreign Language Experts' => '/detail-services.php?slug=foreign-language-support',
        'Digital Marketing'        => '/detail-services.php?slug=digital-marketing',
        'BPO Services'             => '/detail-services.php?slug=business-outsourcing',
        'Back Office Services'     => '/detail-services.php?slug=back-office',
        'Email/Chat Support'       => '/contact.php',
        'Publishing Solutions'     => '/detail-services.php?slug=publishing-solutions',
      ];
      foreach($services_footer as $label => $href):
      ?>
      <a href="<?=$href?>"><?=$label?></a>
      <?php endforeach; ?>
    </div>

    <div>
      <h4>Subscribe</h4>
      <p><?= htmlspecialchars(setting('footer_subscribe_text',$pdo)) ?></p>
      <form id="newsletter-form" class="site-footer__form">
        <input type="email" name="email" placeholder="Your email" aria-label="Email address" required>
        <button type="submit">Go</button>
      </form>
      <div id="newsletter-form-msg" class="form-message" style="display:none; font-size:12px; margin-top:8px; color:rgba(255,255,255,0.7);"></div>
      <div class="site-footer__socials" aria-label="Social links">
        <?php
        $fb = setting('social_facebook', $pdo) ?: 'https://www.facebook.com/clevora.India';
        $xing = setting('social_xing', $pdo) ?: 'https://www.xing.com/companies/clevoraglobaloutsourcingservices';
        $linkedin = setting('social_linkedin', $pdo) ?: 'https://www.linkedin.com/company/74049332/admin/feed/posts';
        $linktree = setting('social_linktree', $pdo) ?: 'https://linktr.ee/clevora';
        
        $whatsapp_num = setting('contact_whatsapp', $pdo) ?: '919953310085';
        if (str_starts_with($whatsapp_num, 'http://') || str_starts_with($whatsapp_num, 'https://')) {
            $whatsapp_url = $whatsapp_num;
        } else {
            $whatsapp_url = 'https://api.whatsapp.com/send?phone=' . urlencode(preg_replace('/[^0-9]/', '', $whatsapp_num)) . '&text=I%20am%20interested%20in%20your%20services';
        }

        $socials = [
          [$fb, '<i class="fa-brands fa-facebook-f"></i>', '#1877f2'],
          [$xing, '<i class="fa-brands fa-xing"></i>', '#006567'],
          [$linkedin, '<i class="fa-brands fa-linkedin-in"></i>', '#0a66c2'],
          [$linktree, '<i class="fa-solid fa-tree"></i>', '#39e09b'],
          [$whatsapp_url, '<i class="fa-brands fa-whatsapp"></i>', '#25d366']
        ];
        foreach($socials as [$url,$icon,$bg]):
        ?>
        <a href="<?=$url?>" target="_blank" rel="noopener" style="background:<?=$bg?>; color:#fff; display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; font-size:14px; text-decoration:none; margin-right:6px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'"><?=$icon?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="site-footer__bottom">
    <span>© <?=date('Y')?> Clevora. All Rights Reserved.</span>
  </div>
</footer>
</body>
</html>
