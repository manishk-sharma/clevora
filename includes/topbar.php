<div class="topbar">
  <div class="topbar__inner">
  <div class="topbar__contact">
    <a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', setting('contact_phone',$pdo))) ?>">Phone: <?= htmlspecialchars(setting('contact_phone',$pdo)) ?></a>
    <a href="mailto:<?= htmlspecialchars(setting('contact_email',$pdo)) ?>">Email: <?= htmlspecialchars(setting('contact_email',$pdo)) ?></a>
  </div>
  <div class="topbar__right">
    <select class="topbar__select" id="language-selector" aria-label="Select language">
      <option value="">Select Language</option>
      <option value="en">English</option>
      <option value="zh-CN">Chinese (Simplified)</option>
      <option value="nl">Dutch</option>
      <option value="fr">French</option>
      <option value="hi">Hindi</option>
      <option value="it">Italian</option>
      <option value="ja">Japanese</option>
      <option value="ru">Russian</option>
      <option value="es">Spanish</option>
    </select>

    <!-- Google Translate hidden container and script integration -->
    <div id="google_translate_element" style="display:none;position:absolute;visibility:hidden;width:0;height:0;"></div>
    <script type="text/javascript">
      function googleTranslateElementInit() {
        new google.translate.TranslateElement({
          pageLanguage: 'en',
          autoDisplay: false
        }, 'google_translate_element');
      }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('language-selector');
        if (!select) return;

        select.addEventListener('change', () => {
          const lang = select.value;
          
          // Find Google Translate combo select
          const translateSelect = document.querySelector('.goog-te-combo');
          if (translateSelect) {
            translateSelect.value = lang || 'en'; // Fallback to English if empty select
            translateSelect.dispatchEvent(new Event('change'));
          } else {
            // Retry if Google Translate element hasn't fully initialized
            setTimeout(() => {
              const retrySelect = document.querySelector('.goog-te-combo');
              if (retrySelect) {
                retrySelect.value = lang || 'en';
                retrySelect.dispatchEvent(new Event('change'));
              }
            }, 500);
          }
        });

        // Initialize selector value based on current cookie state
        setTimeout(() => {
          const matches = document.cookie.match(/googtrans=\/en\/([^;]+)/);
          if (matches && matches[1]) {
            select.value = matches[1];
          }
        }, 1000);
      });
    </script>
    <?php
    $socials = [
      ['#','f','#1877f2'],['#','G','#ea4335'],
      ['#','X','#374151'],['#','in','#0a66c2'],['#','🌳','#f97316']
    ];
    foreach($socials as [$url,$label,$bg]):
    ?>
    <a href="<?=$url?>"
       class="topbar__social"
       style="background:<?=$bg?>;">
      <?=$label?>
    </a>
    <?php endforeach; ?>
  </div>
  </div>
</div>
