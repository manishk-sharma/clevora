-- ============================================================
-- CLEVORA HOMEPAGE CMS UPGRADE MIGRATION
-- ============================================================

-- 1. Alter hero_sliders table to add secondary cta buttons
ALTER TABLE hero_sliders
  ADD COLUMN IF NOT EXISTS secondary_cta_text VARCHAR(150) DEFAULT 'Explore Solutions',
  ADD COLUMN IF NOT EXISTS secondary_cta_link VARCHAR(255) DEFAULT '/services.php';

-- 2. Create home_partners table
CREATE TABLE IF NOT EXISTS home_partners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(255) NOT NULL,
  logo VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0,
  is_active TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create home_solutions table
CREATE TABLE IF NOT EXISTS home_solutions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  icon VARCHAR(100) DEFAULT '💬',
  button_text VARCHAR(100) DEFAULT 'Explore Solutions',
  button_link VARCHAR(255) DEFAULT '/services.php',
  sort_order INT DEFAULT 0,
  is_active TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create home_process_steps table
CREATE TABLE IF NOT EXISTS home_process_steps (
  id INT AUTO_INCREMENT PRIMARY KEY,
  step_number INT DEFAULT 1,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  sort_order INT DEFAULT 0,
  is_active TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Seed Partner Logos if empty
INSERT INTO home_partners (company_name, logo, sort_order, is_active)
SELECT 'Client One', '/assets/images/client-1.png', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_partners WHERE company_name = 'Client One');

INSERT INTO home_partners (company_name, logo, sort_order, is_active)
SELECT 'Client Two', '/assets/images/client-2.png', 2, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_partners WHERE company_name = 'Client Two');

INSERT INTO home_partners (company_name, logo, sort_order, is_active)
SELECT 'Client Three', '/assets/images/client-3.png', 3, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_partners WHERE company_name = 'Client Three');

INSERT INTO home_partners (company_name, logo, sort_order, is_active)
SELECT 'Client Four', '/assets/images/client-4.png', 4, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_partners WHERE company_name = 'Client Four');

INSERT INTO home_partners (company_name, logo, sort_order, is_active)
SELECT 'Client Five', '/assets/images/client-5.png', 5, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_partners WHERE company_name = 'Client Five');

INSERT INTO home_partners (company_name, logo, sort_order, is_active)
SELECT 'Client Six', '/assets/images/client-6.png', 6, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_partners WHERE company_name = 'Client Six');


-- 6. Seed Core Solutions if empty
INSERT INTO home_solutions (title, description, icon, button_text, button_link, sort_order, is_active)
SELECT 'Customer Support Services', 'Multilingual inbound/outbound support, email and live chat operations with SLAs tailored to keep customer satisfaction high.', '💬', 'Explore Solutions', '/services.php?category=customer-support-services', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_solutions WHERE title = 'Customer Support Services');

INSERT INTO home_solutions (title, description, icon, button_text, button_link, sort_order, is_active)
SELECT 'Content & Moderation Services', '24/7 video, audio, image and social media review moderation. Keep your application community and brand reputation safe.', '🛡️', 'Explore Solutions', '/services.php?category=content-moderation-services', 2, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_solutions WHERE title = 'Content & Moderation Services');

INSERT INTO home_solutions (title, description, icon, button_text, button_link, sort_order, is_active)
SELECT 'E-Commerce Support', 'Optimize store operations: catalog product uploads, order tracking coordination, returns and marketplace support.', '🛒', 'Explore Solutions', '/services.php?category=e-commerce-support', 3, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_solutions WHERE title = 'E-Commerce Support');

INSERT INTO home_solutions (title, description, icon, button_text, button_link, sort_order, is_active)
SELECT 'Back Office & Data Management', 'High speed data entry, processing, and standardisation solutions to keep corporate records accurate and accessible.', '📂', 'Explore Solutions', '/services.php?category=back-office-data-management', 4, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_solutions WHERE title = 'Back Office & Data Management');

INSERT INTO home_solutions (title, description, icon, button_text, button_link, sort_order, is_active)
SELECT 'Finance & Accounting', 'Automate billing support, accounts receivable/payable, expense reconciliations and payroll auditing.', '💳', 'Explore Solutions', '/services.php?category=finance-accounting', 5, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_solutions WHERE title = 'Finance & Accounting');

INSERT INTO home_solutions (title, description, icon, button_text, button_link, sort_order, is_active)
SELECT 'Recruitment & HR Services', 'Recruitment process outsourcing (RPO), resume screening, talent sourcing and HR operations administration.', '👥', 'Explore Solutions', '/services.php?category=hr-solutions', 6, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_solutions WHERE title = 'Recruitment & HR Services');


-- 7. Seed How It Works if empty
INSERT INTO home_process_steps (step_number, title, description, sort_order, is_active)
SELECT 1, 'Tell Us What You Need', 'Share your business challenges, goals and outsourcing requirements.', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_process_steps WHERE title = 'Tell Us What You Need');

INSERT INTO home_process_steps (step_number, title, description, sort_order, is_active)
SELECT 2, 'We Deploy Your Team', 'Our experts create a customized support process for your company.', 2, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_process_steps WHERE title = 'We Deploy Your Team');

INSERT INTO home_process_steps (step_number, title, description, sort_order, is_active)
SELECT 3, 'Scale & Grow', 'Focus on growth while Clevora manages your operations.', 3, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM home_process_steps WHERE title = 'Scale & Grow');


-- 8. Seed Why Choose Us in homepage_sections if empty
INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'why_choose', 'Qualified Experts', 'Experienced teams trained for complex business operations.', 'fa-graduation-cap', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'why_choose' AND title = 'Qualified Experts');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'why_choose', 'Operational Quality', 'Structured workflows focused on accuracy and performance.', 'fa-certificate', 2, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'why_choose' AND title = 'Operational Quality');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'why_choose', 'Flexible & Scalable', 'Increase or reduce support capacity according to demand.', 'fa-clock', 3, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'why_choose' AND title = 'Flexible & Scalable');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'why_choose', 'Affordable Packages', 'Cost-effective outsourcing solutions.', 'fa-coins', 4, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'why_choose' AND title = 'Affordable Packages');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'why_choose', 'Data Security', 'Confidential processes and secure information handling.', 'fa-shield-halved', 5, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'why_choose' AND title = 'Data Security');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'why_choose', '24/7 Operations', 'Continuous support across multiple time zones.', 'fa-users', 6, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'why_choose' AND title = '24/7 Operations');


-- 9. Seed Industries in homepage_sections if empty
INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Gaming & Entertainment', 'Gaming support and content operations for immersive platforms.', 'fa-gamepad', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Gaming & Entertainment');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Education', 'Student support and digital learning platform administration.', 'fa-book-open', 2, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Education');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Retail & E-Commerce', 'Order workflows, listings, catalog management and helpdesk operations.', 'fa-cart-shopping', 3, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Retail & E-Commerce');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Hospitality', 'Booking, reservations, routing and continuous guest inquiries.', 'fa-hotel', 4, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Hospitality');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Staffing & HR', 'Sourcing, screening, data auditing and onboarding processes.', 'fa-user-tie', 5, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Staffing & HR');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Healthcare', 'Secure validation, scheduling and patient coordination services.', 'fa-heart-pulse', 6, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Healthcare');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Real Estate', 'Listings updates, database operations and lead processing support.', 'fa-building', 7, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Real Estate');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Financial Services', 'Accounts processing, invoice routing, data cleanup and reports.', 'fa-money-bill-trend-up', 8, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Financial Services');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'SaaS & Technology', 'Technical helpdesk, user onboarding and platform monitoring.', 'fa-laptop-code', 9, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'SaaS & Technology');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'industry', 'Telecommunications', 'Customer workflows, calling support and routing configurations.', 'fa-phone-volume', 10, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'industry' AND title = 'Telecommunications');


-- 10. Seed FAQs in homepage_sections if empty
INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'faq', 'What outsourcing services does Clevora offer?', 'Clevora provides customer support, content operations, data management, finance, HR, e-commerce, KPO and call center solutions.', '❓', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'faq' AND title = 'What outsourcing services does Clevora offer?');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'faq', 'How quickly can you deploy a team?', 'Deployment depends on process requirements and team size. We create flexible solutions based on business needs.', '❓', 2, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'faq' AND title = 'How quickly can you deploy a team?');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'faq', 'How do you ensure data security?', 'We use controlled workflows, access management and secure operational practices.', '❓', 3, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'faq' AND title = 'How do you ensure data security?');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'faq', 'Can I scale services?', 'Yes. Our teams can expand or adjust according to business demand.', '❓', 4, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'faq' AND title = 'Can I scale services?');

INSERT INTO homepage_sections (section_type, title, description, icon, sort_order, status)
SELECT 'faq', 'Where are your teams located?', 'Clevora operates from Delhi, India and supports global clients.', '❓', 5, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_type = 'faq' AND title = 'Where are your teams located?');


-- 11. Seed global CTA in site_settings
INSERT INTO site_settings (setting_key, setting_value)
SELECT 'cta_heading', 'Ready to become our next success story?'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM site_settings WHERE setting_key = 'cta_heading');

INSERT INTO site_settings (setting_key, setting_value)
SELECT 'cta_text', 'Tell us about your operational challenge. We will help build a scalable outsourcing solution.'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM site_settings WHERE setting_key = 'cta_text');

INSERT INTO site_settings (setting_key, setting_value)
SELECT 'cta_button_text', 'Get a Free Quote'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM site_settings WHERE setting_key = 'cta_button_text');

INSERT INTO site_settings (setting_key, setting_value)
SELECT 'cta_button_url', 'contact.php'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM site_settings WHERE setting_key = 'cta_button_url');
