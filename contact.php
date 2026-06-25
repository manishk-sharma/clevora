<?php
$pageTitle         = 'Contact Clevora | Outsourcing Consultation';
$pageBannerTitle   = 'CONTACT US';
$pageBannerBreadcrumb = 'Contact Us';
require_once 'includes/header.php';
include 'includes/page-banner.php';
?>

<section class="section section--soft">
<div class="container">
<div class="contact-panel">

  <!-- LEFT: dark contact info panel -->
  <div class="contact-panel__info">
    <span class="section-kicker">Contact Clevora</span>
    <h2>Let's Build Your Next Operations Team</h2>
    <p>Tell us what you need to outsource, improve, moderate or support. Our team will help create the right operational solution.</p>

    <?php
    $info = [
      ['📞','Phone',   setting('contact_phone',$pdo)],
      ['✉', 'Email',   setting('contact_email',$pdo)],
      ['📍','Address', setting('contact_address',$pdo)],
      ['🕐','Hours',   '24 / 7 / 365'],
    ];
    foreach($info as [$icon,$label,$val]):
    ?>
    <div class="contact-item">
      <div class="contact-item__icon">
        <?=$icon?>
      </div>
      <div>
        <span><?=$label?></span>
        <strong><?=htmlspecialchars($val)?></strong>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Map placeholder -->
    <div class="contact-map">
      <iframe
        src="https://maps.google.com/maps?q=<?= urlencode(setting('contact_address', $pdo)) ?>&output=embed"
        style="width:100%; height:100%; border:none; border-radius:10px;"
        loading="lazy" allowfullscreen>
      </iframe>
    </div>

    <!-- Social -->
    <div class="contact-socials">
      <?php
      $socials = [
        ['https://www.facebook.com/clevora.India', '<i class="fa-brands fa-facebook-f"></i>', '#1877f2'],
        ['https://www.xing.com/companies/clevoraglobaloutsourcingservices', '<i class="fa-brands fa-xing"></i>', '#006567'],
        ['https://www.linkedin.com/company/74049332/admin/feed/posts', '<i class="fa-brands fa-linkedin-in"></i>', '#0a66c2'],
        ['https://linktr.ee/clevora', '<i class="fa-solid fa-tree"></i>', '#39e09b'],
        ['https://api.whatsapp.com/send?phone=919953310085&text=%20I%20am%20interested%20in%20your%20services', '<i class="fa-brands fa-whatsapp"></i>', '#25d366']
      ];
      foreach($socials as [$url,$icon,$bg]):
      ?>
      <a href="<?=$url?>"
         target="_blank"
         rel="noopener"
         style="background:<?=$bg?>; color:#fff; display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:8px; font-size:14px; text-decoration:none; margin-right:6px; transition: opacity 0.2s;"
         onmouseover="this.style.opacity='0.85'"
         onmouseout="this.style.opacity='1'">
        <?=$icon?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- RIGHT: white form panel -->
  <div class="contact-panel__form">
    <h2>Send a Message</h2>
    <div id="form-message" class="form-message" style="display:none;"></div>
    <form id="contact-form">
      <div class="contact-form-grid">

        <?php
        $form_fields = [
          ['name'=>'name',    'type'=>'text',  'label'=>'Full Name',     'placeholder'=>'e.g. Manish Sharma', 'required'=>true],
          ['name'=>'email',   'type'=>'email', 'label'=>'Email Address', 'placeholder'=>'you@company.com',    'required'=>true],
          ['name'=>'phone',   'type'=>'tel',   'label'=>'Phone Number',  'placeholder'=>'+91 XXXXXXXXXX',     'required'=>true],
        ];
        foreach($form_fields as $f):
        ?>
        <div class="<?=$f['name']==='name'?'form-span-2':''?>">
          <label class="form-label">
            <?=$f['label']?> <?=$f['required']?'<span style="color:#ef4444">*</span>':''?>
          </label>
          <input type="<?=$f['type']?>" name="<?=$f['name']?>"
                 placeholder="<?=$f['placeholder']?>"
                 <?=$f['required']?'required':''?>
                 class="form-control">
        </div>
        <?php endforeach; ?>

        <!-- Area of interest -->
        <div class="form-span-2">
          <label class="form-label">
            Area of Interest <span style="color:#ef4444">*</span>
          </label>
          <select name="interest" required class="form-select">
            <option value="">Select a service...</option>
            <?php
            $selected_interest = $_GET['interest'] ?? '';
            $interests = [
              'Customer Support Services',
              'Content Moderation',
              'E-Commerce Support',
              'Data Management',
              'Finance & Accounting',
              'HR Services',
              'KPO Services',
              'Call Center Services',
              'General Inquiry'
            ];
            foreach($interests as $opt):
              $selected = (strtolower(trim($opt)) === strtolower(trim($selected_interest))) ? 'selected' : '';
            ?>
            <option <?= $selected ?>><?=htmlspecialchars($opt)?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Message -->
        <div class="form-span-2">
          <label class="form-label">
            Message <span style="color:#ef4444">*</span>
          </label>
          <textarea name="message" rows="4"
                    placeholder="Tell us about your requirements..."
                    required
                    class="form-textarea"></textarea>
        </div>

        <div class="form-span-2">
          <button type="submit" id="submit-btn" class="btn btn--primary contact-submit">
            Send Message
          </button>
          <p class="contact-note">
            We typically respond within 2 business hours.
          </p>
        </div>

      </div>
    </form>
  </div>
</div>
</div>
</section>

<?php 
$hide_footer_cta = true;
require_once 'includes/footer.php'; 
?>
