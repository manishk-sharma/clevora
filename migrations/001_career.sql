-- ============================================================
-- CLEVORA CAREERS CMS MIGRATION
-- ============================================================

-- ----------------------------------------------------------
-- 1. Create careers table
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS careers (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  job_title         VARCHAR(255) NOT NULL,
  slug              VARCHAR(255) NOT NULL,
  location          VARCHAR(150) DEFAULT 'India',
  job_type          VARCHAR(100) DEFAULT 'Full-Time',
  department        VARCHAR(150) DEFAULT '',
  short_description TEXT,
  responsibilities  JSON,
  requirements      JSON,
  experience        VARCHAR(100) DEFAULT '',
  is_active         TINYINT DEFAULT 1,
  sort_order        INT DEFAULT 0,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 2. Create career_settings table
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS career_settings (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  section_key   VARCHAR(100) UNIQUE NOT NULL,
  section_value TEXT,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 3. Seed default career opportunities
-- ----------------------------------------------------------
INSERT INTO careers (job_title, slug, location, job_type, department, short_description, responsibilities, requirements, experience, is_active, sort_order)
SELECT 'Business Development Associate / Sr Associate', 'business-development-associate', 'India', 'Full-Time', 'Sales & Business Development', 
  'Responsible for identifying opportunities, managing client communication and supporting business growth.',
  '["Identify new business opportunities", "Manage client communication", "Build customer relationships"]',
  '["Good communication skills", "Sales understanding", "Professional attitude"]',
  '1-3 Years', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM careers WHERE slug = 'business-development-associate');

INSERT INTO careers (job_title, slug, location, job_type, department, short_description, responsibilities, requirements, experience, is_active, sort_order)
SELECT 'Ecommerce Specialist / Assistant Manager', 'ecommerce-specialist', 'India', 'Full-Time', 'Ecommerce Operations',
  'Manage marketplace operations, listings, orders and online business workflows.',
  '["Manage marketplace operations", "Optimize product listings", "Process orders and track shipments"]',
  '["Experience with e-commerce platforms", "Detail-oriented mindset", "Good analytical skills"]',
  '2-4 Years', 1, 2
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM careers WHERE slug = 'ecommerce-specialist');

INSERT INTO careers (job_title, slug, location, job_type, department, short_description, responsibilities, requirements, experience, is_active, sort_order)
SELECT 'Human Resource Manager / Assistant Manager', 'human-resource-manager', 'India', 'Full-Time', 'Human Resources',
  'Handle recruitment, employee processes and HR operations.',
  '["Manage recruitment pipelines", "Coordinate employee onboarding", "Oversee HR operations and compliance"]',
  '["Strong interpersonal skills", "Knowledge of HR practices", "Relevant degree or certification"]',
  '3-5 Years', 1, 3
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM careers WHERE slug = 'human-resource-manager');

INSERT INTO careers (job_title, slug, location, job_type, department, short_description, responsibilities, requirements, experience, is_active, sort_order)
SELECT 'Virtual Assistant', 'virtual-assistant', 'India', 'Full-Time', 'Administrative Support',
  'Provide remote administrative and operational support.',
  '["Provide remote administrative support", "Schedule meetings and manage calendars", "Handle email correspondence"]',
  '["Proficiency in office software", "Excellent time management", "Strong verbal and written English"]',
  '1-2 Years', 1, 4
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM careers WHERE slug = 'virtual-assistant');

-- ----------------------------------------------------------
-- 4. Seed career general settings
-- ----------------------------------------------------------
INSERT INTO career_settings (section_key, section_value)
VALUES 
  ('hero_label', 'Clevora Global Operations')
  ON DUPLICATE KEY UPDATE section_value = section_value;

INSERT INTO career_settings (section_key, section_value)
VALUES 
  ('hero_title', 'Careers')
  ON DUPLICATE KEY UPDATE section_value = section_value;

INSERT INTO career_settings (section_key, section_value)
VALUES 
  ('main_heading', 'Build Your Career With Clevora')
  ON DUPLICATE KEY UPDATE section_value = section_value;

INSERT INTO career_settings (section_key, section_value)
VALUES 
  ('intro_text', 'Join a growing outsourcing company where talented people work with global businesses and develop professional skills.')
  ON DUPLICATE KEY UPDATE section_value = section_value;

INSERT INTO career_settings (section_key, section_value)
VALUES 
  ('apply_heading', 'Ready to join us?')
  ON DUPLICATE KEY UPDATE section_value = section_value;

INSERT INTO career_settings (section_key, section_value)
VALUES 
  ('apply_text', 'Send your resume and a brief cover letter to <a href="mailto:info@clevora.in" style="color:#2563eb; font-weight:600; text-decoration:none; border-bottom:1px solid #bfdbfe;">info@clevora.in</a>. Include the role you\'re applying for in the subject line. We\'ll get back to you within 3 business days.')
  ON DUPLICATE KEY UPDATE section_value = section_value;

INSERT INTO career_settings (section_key, section_value)
VALUES 
  ('benefit_cards', '[{"title":"Work with Global Clients","description":"Collaborate with international businesses across e-commerce, finance, healthcare and technology."},{"title":"Training & Growth","description":"Receive structured onboarding and continuous skill development opportunities."},{"title":"Collaborative Culture","description":"Work in a supportive environment built around teamwork and innovation."},{"title":"Competitive Compensation","description":"Get rewarding career opportunities with fair compensation."}]')
  ON DUPLICATE KEY UPDATE section_value = section_value;
