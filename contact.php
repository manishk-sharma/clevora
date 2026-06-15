<?php
$pageTitle         = 'Contact Clevora | Get a Free BPO Quote';
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
    <h2>Get in Touch</h2>
    <p>Tell us what you need to outsource, improve, moderate, or support. Our team will help you shape the right operating plan.</p>

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
      $socials = [['f','#1877f2'],['X','#374151'],['in','#0a66c2'],['🌳','#f97316']];
      foreach($socials as [$l,$c]):
      ?>
      <a href="#"
         style="background:<?=$c?>;">
        <?=$l?>
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
            $interests = ['Content Moderation','Digital Marketing',
                          'Foreign Language Support','Data Validation',
                          'Mortgage Services','Inbound / Outbound',
                          'Business Process Outsourcing','Back Office Support',
                          'Publishing Solutions','Software Solutions',
                          'Database Management','Conversion Catalyst'];
            foreach($interests as $opt):
            ?>
            <option><?=htmlspecialchars($opt)?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Message -->
        <div class="form-span-2">
          <label class="form-label">
            Message
          </label>
          <textarea name="message" rows="4"
                    placeholder="Tell us about your requirements..."
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

<?php require_once 'includes/footer.php'; ?>
