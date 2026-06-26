-- ============================================================
-- CLEVORA HERO SLIDER VIDEO SUPPORT MIGRATION
-- ============================================================

-- Alter hero_sliders table to add media columns
ALTER TABLE hero_sliders
  ADD COLUMN IF NOT EXISTS media_type ENUM('image','video') DEFAULT 'image',
  ADD COLUMN IF NOT EXISTS media_file VARCHAR(500) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS video_poster VARCHAR(500) DEFAULT NULL;

-- Populate media_file with current image values for existing rows
UPDATE hero_sliders 
SET media_file = image, media_type = 'image' 
WHERE media_file IS NULL OR media_file = '';
