-- ============================================================
-- CLEVORA CMS MIGRATION
-- Run via: php run_cms_migration.php
-- Safe to re-run (uses IF NOT EXISTS / IF NOT EXISTS checks)
-- ============================================================

-- ----------------------------------------------------------
-- 1. ALTER admin_users — add remember_token, last_login, updated_at
-- ----------------------------------------------------------
ALTER TABLE admin_users
  ADD COLUMN IF NOT EXISTS remember_token VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS last_login DATETIME DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update default admin password to bcrypt of 'admin123'
UPDATE admin_users SET password = '$2y$10$w31bCcbdjkd/j9Iq8UacDOgnUh/NOPZX0nPZUjKmvxgnnENEFBuPO' WHERE username = 'admin';

-- ----------------------------------------------------------
-- 2. hero_sliders — homepage slider management
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS hero_sliders (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  small_heading VARCHAR(255) DEFAULT '',
  main_heading  VARCHAR(255) NOT NULL,
  description   TEXT,
  bullets       JSON,
  cta_text      VARCHAR(100) DEFAULT 'Contact Us',
  cta_link      VARCHAR(255) DEFAULT '/contact.php',
  image         VARCHAR(255) DEFAULT '',
  sort_order    INT DEFAULT 0,
  status        TINYINT DEFAULT 1,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default hero sliders from current hardcoded data
INSERT INTO hero_sliders (small_heading, main_heading, description, bullets, cta_text, cta_link, image, sort_order, status)
SELECT 'SCALABLE OUTSOURCING SOLUTIONS', 'Customer Experience (CX)', 'Proactive 24/7 customer engagement across channels.',
  '["Inbound and outbound customer care support","Fluent multilingual support agents","SLA-backed email and live chat support","Technical helpdesk and troubleshooting support"]',
  'Contact Us', '/contact.php', '/assets/images/hero-bg.jpg', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM hero_sliders WHERE main_heading = 'Customer Experience (CX)');

INSERT INTO hero_sliders (small_heading, main_heading, description, bullets, cta_text, cta_link, image, sort_order, status)
SELECT 'TRUST & SAFETY SOLUTIONS', 'Content Moderation & Operations', 'Protecting your brand and users round the clock.',
  '["Live streaming content moderation","Video and image review services","Social media review and comments moderation","Compliance and policy enforcement"]',
  'Contact Us', '/contact.php', '/assets/images/content-mod.jpg', 2, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM hero_sliders WHERE main_heading = 'Content Moderation & Operations');

INSERT INTO hero_sliders (small_heading, main_heading, description, bullets, cta_text, cta_link, image, sort_order, status)
SELECT 'SECURE PROCESS MANAGEMENT', 'Data Operations & Back Office', 'Highly accurate and secure database processing.',
  '["Data entry and transcription services","Database standardisation and cleaning","Bookkeeping and accounts support","Administrative process management"]',
  'Contact Us', '/contact.php', '/assets/images/service-banner.jpg', 3, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM hero_sliders WHERE main_heading = 'Data Operations & Back Office');

INSERT INTO hero_sliders (small_heading, main_heading, description, bullets, cta_text, cta_link, image, sort_order, status)
SELECT 'GLOBAL SUPPORT OPERATIONS', 'E-Commerce Support Operations', 'Powering your storefront operations seamlessly.',
  '["Order processing and shipment tracking support","Product listing and catalog uploads","Returns, refunds and exchange management","Multi-marketplace store support"]',
  'Contact Us', '/contact.php', '/assets/images/hero-office.jpg', 4, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM hero_sliders WHERE main_heading = 'E-Commerce Support Operations');

-- ----------------------------------------------------------
-- 3. homepage_sections — Why Choose, Industries, FAQ, CTA
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS homepage_sections (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  section_type  VARCHAR(50) NOT NULL,
  title         VARCHAR(255) NOT NULL,
  description   TEXT,
  icon          VARCHAR(100) DEFAULT '',
  extra_data    JSON,
  sort_order    INT DEFAULT 0,
  status        TINYINT DEFAULT 1,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_section_type (section_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 4. about_page — structured about content
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS about_page (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  page_title       VARCHAR(255) DEFAULT 'About Clevora',
  intro            TEXT,
  company_story    LONGTEXT,
  problem_section  TEXT,
  solution_section TEXT,
  mission          TEXT,
  vision           TEXT,
  status           TINYINT DEFAULT 1,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed from existing site_settings
INSERT INTO about_page (page_title, intro, company_story, mission, vision)
SELECT 'About Clevora',
  (SELECT setting_value FROM site_settings WHERE setting_key = 'about_home_text' LIMIT 1),
  (SELECT setting_value FROM site_settings WHERE setting_key = 'about_full_history' LIMIT 1),
  (SELECT setting_value FROM site_settings WHERE setting_key = 'about_mission' LIMIT 1),
  (SELECT setting_value FROM site_settings WHERE setting_key = 'about_vision' LIMIT 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM about_page LIMIT 1);

-- ----------------------------------------------------------
-- 5. about_values — repeatable values cards
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS about_values (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  icon        VARCHAR(100) DEFAULT '⭐',
  sort_order  INT DEFAULT 0,
  status      TINYINT DEFAULT 1,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 6. founder — management/founder profile
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS founder (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  name            VARCHAR(255) DEFAULT 'Mayank Chandhok',
  role            VARCHAR(255) DEFAULT 'Founder & Managing Director',
  image           VARCHAR(255) DEFAULT '/assets/images/founder.jpg',
  bio             LONGTEXT,
  message         TEXT,
  experience_text TEXT,
  social_links    JSON,
  status          TINYINT DEFAULT 1,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed from existing site_settings
INSERT INTO founder (name, role, bio)
SELECT
  (SELECT setting_value FROM site_settings WHERE setting_key = 'management_founder_name' LIMIT 1),
  (SELECT setting_value FROM site_settings WHERE setting_key = 'management_founder_role' LIMIT 1),
  (SELECT setting_value FROM site_settings WHERE setting_key = 'management_founder_bio' LIMIT 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM founder LIMIT 1);

-- ----------------------------------------------------------
-- 7. ALTER service_categories — add SEO and banner fields
-- ----------------------------------------------------------
ALTER TABLE service_categories
  ADD COLUMN IF NOT EXISTS banner_image VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS full_description LONGTEXT DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS seo_title VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS seo_description TEXT DEFAULT NULL;

-- ----------------------------------------------------------
-- 8. ALTER services — add image, SEO, CTA fields
-- ----------------------------------------------------------
ALTER TABLE services
  ADD COLUMN IF NOT EXISTS image VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS meta_title VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS meta_description TEXT DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS keywords VARCHAR(500) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS cta_heading VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS cta_text TEXT DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS cta_button VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ----------------------------------------------------------
-- 9. service_features — repeatable feature rows
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS service_features (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  service_id  INT NOT NULL,
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  sort_order  INT DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_service_id (service_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 10. service_benefits — repeatable benefit rows
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS service_benefits (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  service_id  INT NOT NULL,
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  sort_order  INT DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_service_id (service_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 11. service_process — repeatable process steps
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS service_process (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  service_id  INT NOT NULL,
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  sort_order  INT DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_service_id (service_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 12. service_industries — repeatable industry tags
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS service_industries (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  service_id  INT NOT NULL,
  name        VARCHAR(255) NOT NULL,
  sort_order  INT DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_service_id (service_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- ----------------------------------------------------------
-- 14. ALTER clients — add website, sort_order, status, updated_at
-- ----------------------------------------------------------
ALTER TABLE clients
  ADD COLUMN IF NOT EXISTS website VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS sort_order INT DEFAULT 0,
  ADD COLUMN IF NOT EXISTS status TINYINT DEFAULT 1,
  ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ----------------------------------------------------------
-- 15. ALTER testimonials — add position, company, industry, rating, updated_at
-- ----------------------------------------------------------
ALTER TABLE testimonials
  ADD COLUMN IF NOT EXISTS position VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS company VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS industry VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS rating TINYINT DEFAULT 5,
  ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


-- ----------------------------------------------------------
-- 17. contact_settings — structured contact info
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_settings (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  setting_key  VARCHAR(100) UNIQUE NOT NULL,
  setting_value LONGTEXT,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed from existing site_settings
INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'phone', (SELECT setting_value FROM site_settings WHERE setting_key = 'contact_phone' LIMIT 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'phone');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'email', (SELECT setting_value FROM site_settings WHERE setting_key = 'contact_email' LIMIT 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'email');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'address', (SELECT setting_value FROM site_settings WHERE setting_key = 'contact_address' LIMIT 1)
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'address');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'whatsapp', '' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'whatsapp');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'google_map', '' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'google_map');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'business_hours', 'Monday - Friday: 9:00 AM - 6:00 PM IST' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'business_hours');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'facebook', '' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'facebook');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'linkedin', '' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'linkedin');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'xing', '' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'xing');

INSERT INTO contact_settings (setting_key, setting_value)
SELECT 'linktree', '' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM contact_settings WHERE setting_key = 'linktree');

-- ----------------------------------------------------------
-- 18. ALTER leads — add status, updated_at
-- ----------------------------------------------------------
ALTER TABLE leads
  ADD COLUMN IF NOT EXISTS status ENUM('new','read','contacted','closed') DEFAULT 'new',
  ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ----------------------------------------------------------
-- 19. seo_settings — per-page SEO
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS seo_settings (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  page_slug        VARCHAR(100) UNIQUE NOT NULL,
  meta_title       VARCHAR(255) DEFAULT NULL,
  meta_description TEXT DEFAULT NULL,
  keywords         VARCHAR(500) DEFAULT NULL,
  og_image         VARCHAR(255) DEFAULT NULL,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default pages
INSERT INTO seo_settings (page_slug, meta_title, meta_description) VALUES
('home', 'Clevora | Global BPO, Customer Experience & Digital Operations Partner', 'Clevora provides secure outsourcing solutions including customer support, content operations, data management, HR, finance, e-commerce support and BPO services worldwide.'),
('about', 'About Clevora | Global Outsourcing Company Since 2011', 'Learn about Clevora''s journey from a small Delhi startup to a global outsourcing partner.'),
('services', 'Our Services | Clevora BPO Solutions', 'Explore Clevora''s comprehensive range of BPO and outsourcing services.'),
('technology', 'Technology & Infrastructure | Clevora', 'Discover Clevora''s state-of-the-art technology infrastructure and security systems.'),
('gallery', 'Our Gallery | Clevora Workspace & Team', 'View Clevora''s modern workspace, team activities, and infrastructure.'),
('clients', 'Our Clients | Clevora Partners', 'Organizations that trust Clevora for their outsourcing needs.'),
('careers', 'Careers at Clevora | Build Your Future', 'Explore career opportunities at Clevora.'),
('contact', 'Contact Clevora | Outsourcing Consultation', 'Get in touch with Clevora for your outsourcing needs.')
ON DUPLICATE KEY UPDATE page_slug = page_slug;

-- ----------------------------------------------------------
-- 20. media_library — centralized uploads
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS media_library (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  filename      VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  mime_type     VARCHAR(100) DEFAULT '',
  size          INT DEFAULT 0,
  url           VARCHAR(500) NOT NULL,
  alt_text      VARCHAR(255) DEFAULT '',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
