<footer class="site-footer">
  <div class="site-footer__cta">
    <div>
      <p class="section-kicker">Ready to scale?</p>
      <h2>Build a smarter operations team with Clevora.</h2>
    </div>
    <a class="btn btn--light" href="/contact.php">Start a Conversation</a>
  </div>

  <div class="site-footer__grid">
    <div>
      <a class="site-footer__brand" href="/" aria-label="Clevora home">
        <img class="site-footer__logo" src="/assets/images/logo.png" alt="Clevora">
        <span>
          <strong>Clevora</strong>
          <small>Global Outsourcing</small>
        </span>
      </a>
      <p>
        A privately held BPO organization incorporated in 2011, providing
        outsourcing, support, data, marketing, and moderation solutions.
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
      $quick = ['Home'=>'/','About Us'=>'/about-us.php',
                'Content Moderation'=>'/Content-Moderation.php',
                'Technology'=>'/technology.php','Contact Us'=>'/contact.php'];
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
      <form class="site-footer__form">
        <input type="email" placeholder="Your email" aria-label="Email address">
        <button type="submit">Go</button>
      </form>
      <div class="site-footer__socials" aria-label="Social links">
        <?php
        $socials = [['f','#1877f2'],['X','#374151'],['in','#0a66c2'],['M','#f97316']];
        foreach($socials as [$l,$c]):
        ?>
        <a href="#" style="background:<?=$c?>;"><?=$l?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="site-footer__bottom">
    <span>© <?=date('Y')?> Clevora. All Rights Reserved.</span>
    <span>Total Visit: <span id="visit-count">104914</span></span>
  </div>
</footer>
</body>
</html>
