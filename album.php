<?php
$pageTitle = 'Our Workplace Facilities & Gallery | Clevora';
$metaDesc = 'Explore the professional environment supporting Clevora\'s global BPO and secure operations infrastructure.';

$pageBannerTitle = 'OUR GALLERY';
$pageBannerBreadcrumb = 'Gallery';

require_once 'includes/header.php';
include 'includes/page-banner.php';

$founder_name = setting('management_founder_name', $pdo);
$founder_role = setting('management_founder_role', $pdo);
$founder_bio  = setting('management_founder_bio', $pdo);

$albums = [];
if ($pdo) {
    try {
        // Query active albums that have at least one active image
        $albums = $pdo->query("
            SELECT a.*, COUNT(i.id) as photo_count 
            FROM gallery_albums a
            INNER JOIN gallery_images i ON a.id = i.album_id
            WHERE a.is_active = 1 AND i.is_active = 1
            GROUP BY a.id
            ORDER BY a.sort_order ASC, a.id DESC
        ")->fetchAll();
    } catch (Exception $e) {
        error_log('Gallery albums query error: ' . $e->getMessage());
    }
}

// Fallback if no albums are in database or database fails
if (empty($albums)) {
    $albums = [
        [
            'id' => 1,
            'title' => 'Modern Operations Workspace',
            'description' => 'A glimpse inside our day-to-day operations and high-end infrastructure.',
            'cover_image' => '/assets/images/gallery-1.jpg',
            'photo_count' => 2,
            'fallback_images' => [
                ['url' => '/assets/images/gallery-1.jpg', 'caption' => 'Modern Operations Workspace'],
                ['url' => '/assets/images/gallery-3.jpg', 'caption' => 'Team Collaboration']
            ]
        ],
        [
            'id' => 2,
            'title' => 'Server Room & Infrastructure',
            'description' => 'Technology environment supporting secure operations.',
            'cover_image' => '/assets/images/gallery-2.jpg',
            'photo_count' => 2,
            'fallback_images' => [
                ['url' => '/assets/images/gallery-2.jpg', 'caption' => 'Server Room & Infrastructure'],
                ['url' => '/assets/images/gallery-5.jpg', 'caption' => 'Network Infrastructure']
            ]
        ],
        [
            'id' => 3,
            'title' => 'Corporate Training Center',
            'description' => 'Continuous learning and skill improvement sessions.',
            'cover_image' => '/assets/images/gallery-4.jpg',
            'photo_count' => 1,
            'fallback_images' => [
                ['url' => '/assets/images/gallery-4.jpg', 'caption' => 'Corporate Training']
            ]
        ]
    ];
}
?>

<style>
/* Fullscreen Lightbox Backdrop */
.custom-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(15, 23, 42, 0.96);
    backdrop-filter: blur(8px);
    z-index: 99999;
    display: none;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    user-select: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.custom-lightbox.active {
    display: flex;
    opacity: 1;
}

/* Lightbox Header */
.lightbox-header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    box-sizing: border-box;
    z-index: 100000;
}

.lightbox-title {
    font-size: 16px;
    font-weight: 600;
    color: #e2e8f0;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 60%;
}

.lightbox-counter {
    font-size: 13px;
    font-weight: 500;
    color: #94a3b8;
    background: rgba(255, 255, 255, 0.1);
    padding: 4px 12px;
    border-radius: 9999px;
}

.lightbox-close {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #fff;
    font-size: 24px;
    line-height: 1;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, transform 0.2s;
}

.lightbox-close:hover {
    background: rgba(239, 68, 68, 0.8);
    transform: scale(1.05);
}

/* Slide Main Display Area */
.lightbox-main {
    flex: 1;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    padding: 0 80px;
    box-sizing: border-box;
    overflow: hidden;
}

.lightbox-slide-container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.lightbox-slide-img {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    opacity: 0;
    transform: scale(0.98);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.lightbox-slide-img.loaded {
    opacity: 1;
    transform: scale(1);
}

/* Navigation Arrow Buttons */
.lightbox-arrow {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 20px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, transform 0.2s, color 0.2s;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 100000;
    outline: none;
}

.lightbox-arrow:hover {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
    transform: translateY(-50%) scale(1.05);
}

.lightbox-arrow:active {
    transform: translateY(-50%) scale(0.95);
}

.lightbox-arrow-left {
    left: 20px;
}

.lightbox-arrow-right {
    right: 20px;
}

/* Captions Overlay */
.lightbox-caption-container {
    width: 100%;
    text-align: center;
    padding: 15px 30px;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.8), transparent);
    box-sizing: border-box;
}

.lightbox-caption {
    font-size: 14px;
    color: #f8fafc;
    margin: 0;
    font-weight: 500;
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.5;
}

/* Bottom Thumbnail Strip */
.lightbox-thumbs-container {
    width: 100%;
    background: rgba(15, 23, 42, 0.6);
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    padding: 12px 0;
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
}

.lightbox-thumbs-scroll {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    max-width: 90%;
    padding: 4px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
}

.lightbox-thumbs-scroll::-webkit-scrollbar {
    height: 4px;
}

.lightbox-thumbs-scroll::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
}

.lightbox-thumb {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.2s, border-color 0.2s, transform 0.2s;
    border: 2px solid transparent;
    flex-shrink: 0;
}

.lightbox-thumb:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.lightbox-thumb.active {
    opacity: 1;
    border-color: #2563eb;
    transform: scale(1.05);
}

/* Mobile Responsiveness for Lightbox */
@media (max-width: 768px) {
    .lightbox-main {
        padding: 0;
    }
    .lightbox-arrow {
        width: 44px;
        height: 44px;
        font-size: 16px;
        background: rgba(15, 23, 42, 0.6);
    }
    .lightbox-arrow-left {
        left: 10px;
    }
    .lightbox-arrow-right {
        right: 10px;
    }
    .lightbox-header {
        padding: 15px;
    }
    .lightbox-title {
        font-size: 14px;
        max-width: 50%;
    }
}
</style>

<div style="max-width:1200px; margin:0 auto; padding:48px 24px;" class="space-y-16">

  <!-- Gallery Grid -->
  <div class="space-y-8">
    <!-- Section header -->
    <div style="text-align:center; margin-bottom:40px;">
      <span style="display:inline-block; background:#dbeafe; color:#1d4ed8;
                   font-size:11px; font-weight:600; padding:4px 14px;
                   border-radius:20px; letter-spacing:.5px; margin-bottom:10px;">
        OUR WORKPLACE
      </span>
      <h2 style="font-size:26px; font-weight:700; color:#0f172a; margin-bottom:8px;">
        Our Workplace Facilities
      </h2>
      <p style="font-size:13px; color:#6b7280; max-width:500px; margin:0 auto;">
        Explore the professional environment supporting Clevora’s global operations.
      </p>
      <div style="width:48px; height:3px; background:#2563eb;
                  border-radius:2px; margin:12px auto 0;"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php foreach($albums as $idx => $alb): 
        // Determine category tag dynamically for design preservation
        $tag = 'Gallery';
        if (stripos($alb['title'], 'workspace') !== false) {
            $tag = 'Workspace';
        } elseif (stripos($alb['title'], 'server') !== false || stripos($alb['title'], 'technology') !== false) {
            $tag = 'Technology';
        } elseif (stripos($alb['title'], 'team') !== false || stripos($alb['title'], 'collaboration') !== false) {
            $tag = 'Team';
        } elseif (stripos($alb['title'], 'training') !== false) {
            $tag = 'Training';
        } elseif (stripos($alb['title'], 'network') !== false || stripos($alb['title'], 'infrastructure') !== false) {
            $tag = 'Infrastructure';
        }

        // Fetch album photos for lightbox
        if (isset($alb['fallback_images'])) {
            $images_list = $alb['fallback_images'];
        } else {
            $images_list = [];
            if ($pdo) {
                try {
                    $img_stmt = $pdo->prepare("SELECT image as url, caption FROM gallery_images WHERE album_id = ? AND is_active = 1 ORDER BY sort_order ASC, id ASC");
                    $img_stmt->execute([$alb['id']]);
                    $images_list = $img_stmt->fetchAll();
                } catch (Exception $e) {
                    error_log('Gallery images query error: ' . $e->getMessage());
                }
            }
        }
        $images_json = htmlspecialchars(json_encode($images_list), ENT_QUOTES, 'UTF-8');
      ?>
      <div class="gallery-card"
           onclick="openLightbox(<?= $alb['id'] ?>)"
           data-album-id="<?= $alb['id'] ?>"
           data-album-title="<?= htmlspecialchars($alb['title'], ENT_QUOTES, 'UTF-8') ?>"
           data-images="<?= $images_json ?>"
           style="cursor:pointer; background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 4px 20px rgba(0,0,0,0.01); transition:transform 0.2s, box-shadow 0.2s;" 
           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.06)';" 
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.01)';">
        <!-- Card Image -->
        <div style="aspect-ratio:16/9; overflow:hidden; background:#f1f5f9; border-bottom:1px solid #f1f5f9; position:relative;">
          <img src="<?=htmlspecialchars($alb['cover_image'])?>"
               alt="<?=htmlspecialchars($alb['title']??'')?>"
               loading="lazy"
               style="width:100%; height:100%; object-fit:cover;">
        </div>
        
        <!-- Card Content -->
        <div style="padding:24px; flex:1; display:flex; flex-direction:column; justify-content:space-between; gap:16px;">
          <div class="space-y-3">
            <!-- Category Tag -->
            <div>
              <span style="font-size:11px; font-weight:700; color:#4b5563; background:#f1f5f9; padding:4px 12px; border-radius:9999px; letter-spacing:0.5px;">
                <?=htmlspecialchars($tag)?>
              </span>
            </div>
            
            <!-- Title -->
            <h3 style="font-size:18px; font-weight:700; color:#0f172a; line-height:1.4; font-family:'Poppins', sans-serif; margin:0;">
              <?=htmlspecialchars($alb['title'])?>
            </h3>
            
            <!-- Description -->
            <p style="font-size:13.5px; color:#6b7280; line-height:1.6; margin:0;">
              <?=htmlspecialchars($alb['description'])?>
            </p>
          </div>
          
          <!-- Card Footer -->
          <div style="border-top:1px solid #f1f5f9; padding-top:16px; display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:12px; color:#94a3b8; font-weight:500;">
              <?= $alb['photo_count'] ?> <?= $alb['photo_count'] === 1 ? 'Photo' : 'Photos' ?>
            </span>
            <span style="font-size:12.5px; font-weight:600; color:#2563eb; text-decoration:none;">
              View Album &rarr;
            </span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Premium Fullscreen Lightbox Overlay -->
<div id="gallery-lightbox" class="custom-lightbox">
    <div class="lightbox-header">
        <h4 class="lightbox-title" id="lightbox-album-title">Album Name</h4>
        <div class="lightbox-counter" id="lightbox-counter-label">1 / 1</div>
        <button class="lightbox-close" id="lightbox-close-btn" aria-label="Close lightbox">&times;</button>
    </div>
    
    <div class="lightbox-main">
        <button class="lightbox-arrow lightbox-arrow-left" id="lightbox-prev-btn" aria-label="Previous image">&#10094;</button>
        
        <div class="lightbox-slide-container">
            <img src="" alt="" class="lightbox-slide-img" id="lightbox-img">
        </div>
        
        <button class="lightbox-arrow lightbox-arrow-right" id="lightbox-next-btn" aria-label="Next image">&#10095;</button>
    </div>
    
    <div class="lightbox-caption-container">
        <p class="lightbox-caption" id="lightbox-caption-label">Caption goes here</p>
    </div>
    
    <div class="lightbox-thumbs-container">
        <div class="lightbox-thumbs-scroll" id="lightbox-thumbs-list">
            <!-- Thumbnails injected dynamically -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const albumTitleLabel = document.getElementById('lightbox-album-title');
    const counterLabel = document.getElementById('lightbox-counter-label');
    const captionLabel = document.getElementById('lightbox-caption-label');
    const thumbsList = document.getElementById('lightbox-thumbs-list');
    
    const closeBtn = document.getElementById('lightbox-close-btn');
    const prevBtn = document.getElementById('lightbox-prev-btn');
    const nextBtn = document.getElementById('lightbox-next-btn');
    
    let currentImages = [];
    let currentIndex = 0;
    
    // Swipe coordinates
    let touchStartX = 0;
    let touchEndX = 0;
    
    window.openLightbox = function(albumId) {
        const card = document.querySelector(`.gallery-card[data-album-id="${albumId}"]`);
        if (!card) return;
        
        const imagesData = card.getAttribute('data-images');
        const albumTitle = card.getAttribute('data-album-title');
        
        try {
            currentImages = JSON.parse(imagesData);
        } catch (e) {
            console.error('Failed to parse images data', e);
            currentImages = [];
        }
        
        if (currentImages.length === 0) {
            alert('This album does not have any active photos yet.');
            return;
        }
        
        albumTitleLabel.textContent = albumTitle;
        currentIndex = 0;
        
        // Build thumbnail strip
        thumbsList.innerHTML = '';
        currentImages.forEach((img, idx) => {
            const thumb = document.createElement('img');
            thumb.src = img.url;
            thumb.alt = img.caption || 'Thumbnail';
            thumb.className = 'lightbox-thumb';
            thumb.onclick = (e) => {
                e.stopPropagation();
                showSlide(idx);
            };
            thumbsList.appendChild(thumb);
        });
        
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden'; // Lock background scrolling
        
        showSlide(0);
    };
    
    function showSlide(index) {
        if (index < 0) {
            index = currentImages.length - 1;
        } else if (index >= currentImages.length) {
            index = 0;
        }
        
        currentIndex = index;
        
        const imgObj = currentImages[currentIndex];
        
        // Fade out
        lightboxImg.classList.remove('loaded');
        
        // Change image source
        lightboxImg.src = imgObj.url;
        lightboxImg.alt = imgObj.caption || '';
        
        // Fade in when loaded
        lightboxImg.onload = () => {
            lightboxImg.classList.add('loaded');
        };
        
        // Update texts
        counterLabel.textContent = `${currentIndex + 1} / ${currentImages.length}`;
        captionLabel.textContent = imgObj.caption || '';
        
        // Update thumbnails highlight
        const thumbs = thumbsList.querySelectorAll('.lightbox-thumb');
        thumbs.forEach((thumb, idx) => {
            if (idx === currentIndex) {
                thumb.classList.add('active');
                // Scroll thumbnail into view
                thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                thumb.classList.remove('active');
            }
        });
    }
    
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        lightboxImg.src = '';
    }
    
    // Keyboard listeners
    document.addEventListener('keydown', (e) => {
        if (!lightbox.classList.contains('active')) return;
        
        if (e.key === 'Escape' || e.key === 'Esc') {
            closeLightbox();
        } else if (e.key === 'ArrowRight' || e.key === 'Right') {
            showSlide(currentIndex + 1);
        } else if (e.key === 'ArrowLeft' || e.key === 'Left') {
            showSlide(currentIndex - 1);
        }
    });
    
    // Mobile Swipe detection
    lightbox.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    lightbox.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const threshold = 50; // swipe length in pixels
        if (touchEndX < touchStartX - threshold) {
            // swiped left, show next
            showSlide(currentIndex + 1);
        } else if (touchEndX > touchStartX + threshold) {
            // swiped right, show prev
            showSlide(currentIndex - 1);
        }
    }
    
    // Button clicks
    closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeLightbox();
    });
    prevBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        showSlide(currentIndex - 1);
    });
    nextBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        showSlide(currentIndex + 1);
    });
    
    // Close on click overlay background (excluding image/buttons/controls)
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox || e.target.classList.contains('lightbox-slide-container') || e.target.classList.contains('lightbox-main')) {
            closeLightbox();
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
