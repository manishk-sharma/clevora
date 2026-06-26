-- ============================================================
-- CLEVORA TECHNOLOGY CMS MIGRATION
-- ============================================================

-- ----------------------------------------------------------
-- 1. Drop existing technology_sections and create it anew
-- ----------------------------------------------------------
DROP TABLE IF EXISTS technology_sections;

CREATE TABLE technology_sections (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  section_key   VARCHAR(100) DEFAULT '',
  section_title VARCHAR(255) NOT NULL,
  subtitle      VARCHAR(255) DEFAULT '',
  description   TEXT,
  icon          VARCHAR(255) DEFAULT '🔧',
  badge_text    VARCHAR(150) DEFAULT '',
  sort_order    INT DEFAULT 0,
  is_active     TINYINT DEFAULT 1,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_section_key (section_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 2. Create technology_settings table
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS technology_settings (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  setting_key   VARCHAR(100) UNIQUE NOT NULL,
  setting_value TEXT,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 3. Seed default technology cards
-- ----------------------------------------------------------
INSERT INTO technology_sections (section_key, section_title, description, icon, sort_order, is_active)
VALUES
  ('infrastructure', 'Infrastructure', 'Enterprise-ready infrastructure designed to support secure, stable, and scalable global outsourcing operations.', '🏗️', 1, 1),
  ('security', 'Security & Compliance', 'Structured security practices, controlled access, confidentiality processes, and responsible data handling standards.', '🔒', 2, 1),
  ('workflow', 'Workflow Systems', 'Organized workflow management systems designed for productivity tracking, process visibility, and consistent delivery.', '⚙️', 3, 1),
  ('quality', 'Quality Monitoring', 'Performance reviews, quality checks, reporting, and improvement processes ensure reliable service delivery.', '📊', 4, 1),
  ('backup', 'Backup & Business Continuity', 'Reliable backup practices and continuity planning help maintain smooth business operations.', '💾', 5, 1),
  ('operations', 'Operations Technology', 'Modern communication platforms and operational tools enable efficient customer support and business management.', '🌐', 6, 1);

-- ----------------------------------------------------------
-- 4. Seed default general settings
-- ----------------------------------------------------------
INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('hero_small_text', 'Clevora Global Operations')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('hero_title', 'Technology')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('breadcrumb_title', 'Technology')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('main_label', 'Our Capabilities')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('main_heading', 'Secure Operations & Technology Infrastructure')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('main_description', 'Clevora combines skilled teams, modern infrastructure, secure processes, and advanced operational systems to deliver reliable outsourcing services worldwide.')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('security_title', 'Data Protection & Security')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('security_description', 'We implement controlled access, secure workflows, confidentiality practices, and operational safeguards to protect client information.')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;

INSERT INTO technology_settings (setting_key, setting_value)
VALUES 
  ('security_badge', 'Security & Compliance Assured')
  ON DUPLICATE KEY UPDATE setting_value = setting_value;
