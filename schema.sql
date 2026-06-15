-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  UNIQUE NOT NULL,
  password   VARCHAR(255) NOT NULL  -- bcrypt hashed
) ENGINE=InnoDB;

-- Insert default admin user if not exists
INSERT INTO admin_users (username, password)
VALUES ('admin', '$2y$10$h/np1umfd9JxlULkf/VwI.89deQZLGbyGblXXsR4h2EmWycrDMcey')
ON DUPLICATE KEY UPDATE username=username;

-- Services table
CREATE TABLE IF NOT EXISTS services (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  slug        VARCHAR(80)  UNIQUE NOT NULL,
  name        VARCHAR(150) NOT NULL,
  icon_url    VARCHAR(255),
  intro       TEXT,
  full_content LONGTEXT,   -- For deep-dive pages
  features    JSON,
  benefits    JSON,
  is_active   TINYINT DEFAULT 1,
  sort_order  INT DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Clear services to avoid duplicates during setup
TRUNCATE TABLE services;

-- Insert services
INSERT INTO services (slug, name, icon_url, intro, full_content, features, benefits, is_active, sort_order) VALUES
('database-management', 'Database Management', '/assets/images/service-db.svg', 'Preventing Oversized Non-Standard Data Formats, Multiple Sourcing and Non-Standard Data systems, Address Verification, Postal Code Correction, NCOA and Standardization. Cleaning Databases, Address Checking, Output to Printer, E-Mailer, or Printed Mailing Catalog.', 'Clevora specializes in comprehensive Database Management. We handle tasks such as Address Verification, Postal Code Correction, NCOA, and Standardization. We clean databases, perform address checking, and format outputs to printers, e-mailers, or printed mailing catalogs. Our processes prevent oversized non-standard data formats and consolidate multiple sourcing pipelines into clean, structured datasets that fuel your business decision-making and marketing automation engines.', '["Database Cleaning", "Address Checking", "Postal Code Standardization", "Consolidation of Multiple Sources"]', '["High data accuracy", "Reduced bounce rates", "Optimized marketing spend"]', 1, 1),

('content-moderation', 'Content Moderation', '/assets/images/service-moderation.svg', 'Protect your brand reputation and build user trust with our global content moderation services. We monitor and filter text, images, and videos 24/7.', 'Clevora takes its technological capabilities very seriously and has acquired all the technologies that are required for running a successful call center in this competitive market segment.\n\nOur Content Moderation services ensure your website or application remains safe, clean, and welcoming for all users. We monitor and filter user-generated text, image, and video content 24 hours a day, 7 days a week. We combine automated systems with experienced human moderators to check compliance, block toxic or illegal materials, and shield your brand from unwanted exposure.', '["Image & Video Moderation", "Text & Comment Filtering", "Profile Verification", "Social Media Monitoring"]', '["Safe community environment", "Stronger brand protection", "24/7 moderation coverage"]', 1, 2),

('digital-marketing', 'Digital Marketing', '/assets/images/service-marketing.svg', 'Enhance your digital footprint with SEO, PPC, and social media campaigns designed to generate high-quality leads.', 'Our Digital Marketing division helps businesses acquire, retain, and grow their customer base online. We provide SEO optimization, search engine marketing, Google Ads management, social media optimization, content creation, and email marketing. All campaigns are monitored with detailed analytics, ensuring a continuous feedback loop that maximizes return on investment (ROI).', '["Search Engine Optimization", "Pay Per Click Advertising", "Social Media Marketing", "Email Marketing"]', '["Higher conversion", "Increased visibility", "Better ROI"]', 1, 3),

('software-solutions', 'Software Solutions', '/assets/images/service-software.svg', 'Custom software development, web applications, and enterprise solutions built to scale with your growing business.', 'We offer bespoke Software Solutions, ranging from web-based business portals to mobile applications and third-party integrations. Our engineering team uses robust frameworks to create stable, scalable platforms that streamline workflows and automate core operations. We support the entire software development lifecycle, from scoping to maintenance.', '["Web App Development", "Mobile App Development", "Cloud Solutions", "API Integrations"]', '["Custom designs", "Scalable architecture", "Dedicated support"]', 1, 4),

('business-outsourcing', 'Business Outsourcing', '/assets/images/service-bpo.svg', 'Streamline your operations with our business process outsourcing (BPO) solutions, from front-office to back-office tasks.', 'Clevora’s Business Process Outsourcing (BPO) services cover customer experience management, helpdesk support, technical troubleshooting, and lead generation. We provide well-trained, professional agents who represent your company with dedication, providing standard-setting support across voice, email, and live chat channels.', '["Customer Support", "Technical Helpdesk", "Virtual Assistants", "Data Management"]', '["Operational efficiency", "Focus on core tasks", "Cost reduction"]', 1, 5),

('mortgage-services', 'Mortgage Services', '/assets/images/service-mortgage.svg', 'Accurate and fast mortgage processing support, document indexing, and validation for lenders and brokers.', 'We offer reliable, secure support for mortgage originators, lenders, and brokers. Our teams handle document indexing, loan file verification, tax transcript verification, and title search support. We help streamline the mortgage closing timeline by handling the labor-intensive backend tasks with accuracy.', '["Loan Processing Support", "Document Indexing", "Underwriting Assistance", "Title Search Support"]', '["Faster turnaround", "Reduced processing costs", "Compliance adherence"]', 1, 6),

('foreign-language-support', 'Foreign Language Support', '/assets/images/service-language.svg', 'Connect with global clients through multilingual customer support, translation, and localized services.', 'Reach new markets and engage global customers with our multilingual support solutions. We offer fluent customer support and technical assistance in multiple international languages, helping you bridge language barriers and establish trust with clients around the globe.', '["Multilingual Helpdesk", "Document Translation", "Localisation Support", "Bilingual Agents"]', '["Global reach", "Native speakers", "Enhanced satisfaction"]', 1, 7),

('data-validation', 'Data Validation', '/assets/images/service-validation.svg', 'Maintain a high-quality database with real-time validation, address verification, and database scrubbing.', 'Ensure the integrity of your contact list with data validation services. We scrub database tables, verify email formats and deliverability, and check phone number status. This limits waste and ensures marketing lists remain highly receptive and accurate.', '["Real-time Validation", "Email Verification", "Phone Number Scrubbing", "Address Standardisation"]', '["High data quality", "Effective campaigns", "Reduced bounces"]', 1, 8),

('inbound-outbound', 'Inbound & Outbound Call Center', '/assets/images/service-callcenter.svg', 'Drive sales and support customers with professional inbound and outbound tele-calling services.', 'A complete suite of telephone contact solutions. Inbound call center services manage booking requests, hotlines, and standard customer support queries. Outbound call center services handle lead verification, outbound surveys, and telesales campaigns, operated by agents using high-quality dialer software.', '["Customer Care", "Lead Generation", "Telemarketing", "Market Surveys"]', '["Increased sales", "Better retention", "Detailed reporting"]', 1, 9),

('conversion-catalyst', 'Conversion Catalyst', '/assets/images/service-catalyst.svg', 'Boost your website''s conversion rate through user experience design auditing and conversion rate optimization (CRO).', 'Our CRO specialists analyze user behavior on your digital platforms to identify friction points. We use heatmaps, user session recordings, and A/B test experiments to redesign layouts and funnels, boosting conversion rates and lowering the customer acquisition cost.', '["UX Audits", "A/B Testing", "Heatmap Analysis", "Funnel Optimization"]', '["Increased sales", "Better UX", "Lower acquisition cost"]', 1, 10),

('back-office', 'Back Office Support', '/assets/images/service-backoffice.svg', 'Efficient data entry, bookkeeping, processing invoices, and document classification services for your backend teams.', 'Clevora’s Back Office support handles administrative tasks, including manual data entry, processing supplier invoices, payroll processing support, bookkeeping, and document filing. We keep your administrative engine running smoothly behind the scenes.', '["Data Entry", "Invoicing & Billing", "Bookkeeping Support", "Document Management"]', '["Accurate processing", "Fast turnaround", "Secure handling"]', 1, 11),

('publishing-solutions', 'Publishing Solutions', '/assets/images/service-publishing.svg', 'Professional formatting, layout typesetting, proofreading, and e-book conversion services.', 'We offer digital typesetting, copyediting, indexing, graphic layout creation, and multi-format e-book conversion (EPUB, MOBI, PDF) for publishers, self-publishing authors, and corporate documentation departments.', '["Typesetting & Layout", "E-book Conversion", "Copyediting & Proofreading", "Graphic Design Support"]', '["Print-ready quality", "Multi-format outputs", "Experienced editors"]', 1, 12);

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  location   VARCHAR(100),
  photo_url  VARCHAR(255),
  quote      TEXT NOT NULL,
  is_active  TINYINT DEFAULT 1
) ENGINE=InnoDB;

TRUNCATE TABLE testimonials;

INSERT INTO testimonials (name, location, photo_url, quote) VALUES
('Rahul Kumar Gupta', 'Karnal, India', '/assets/images/testimonial-1.jpg', 'This is very satisfied that Clevora provides us with great satisfaction and we are extremely happy with their operations and quality standards.'),
('Patrick John',      'California, USA', '/assets/images/testimonial-2.jpg', 'I am most satisfied customer after working with clevora. Their round-the-clock availability has changed how we do our business operations.'),
('John Andrea',       'Berlin, Germany', '/assets/images/testimonial-3.jpg', 'We have been running outsourcing through Clevora for some time now. Their professional multilingual team handles our tickets efficiently.');

-- Gallery table
CREATE TABLE IF NOT EXISTS gallery (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  image_url  VARCHAR(255) NOT NULL,
  caption    VARCHAR(150),
  sort_order INT DEFAULT 0
) ENGINE=InnoDB;

TRUNCATE TABLE gallery;

INSERT INTO gallery (image_url, caption, sort_order) VALUES
('/assets/images/gallery-1.jpg', 'Our Modern Workspace', 1),
('/assets/images/gallery-2.jpg', 'Server Room & Infrastructure', 2),
('/assets/images/gallery-3.jpg', 'Team Collaboration Session', 3),
('/assets/images/gallery-4.jpg', 'Training Workshop', 4),
('/assets/images/gallery-5.jpg', 'Recreation & Play Area', 5),
('/assets/images/gallery-6.jpg', 'Client Visit and Discussion', 6);

-- Clients table
CREATE TABLE IF NOT EXISTS clients (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  logo_url   VARCHAR(255) NOT NULL,
  name       VARCHAR(100)
) ENGINE=InnoDB;

TRUNCATE TABLE clients;

INSERT INTO clients (logo_url, name) VALUES
('/assets/images/client-1.png', 'Client One'),
('/assets/images/client-2.png', 'Client Two'),
('/assets/images/client-3.png', 'Client Three'),
('/assets/images/client-4.png', 'Client Four'),
('/assets/images/client-5.png', 'Client Five'),
('/assets/images/client-6.png', 'Client Six');

-- Leads table
CREATE TABLE IF NOT EXISTS leads (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  phone      VARCHAR(20),
  interest   VARCHAR(100),
  message    TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Site settings table
CREATE TABLE IF NOT EXISTS site_settings (
  setting_key   VARCHAR(100) PRIMARY KEY,
  setting_value LONGTEXT
) ENGINE=InnoDB;

TRUNCATE TABLE site_settings;

INSERT INTO site_settings (setting_key, setting_value) VALUES
('hero_headline',       'Database Management'),
('hero_bullets',        'Preventing Oversized Non-Standard Data Formats\nMultiple Sourcing and Non-Standard Data systems\nAddress Verification, Postal Code Correction, NCOA and Standardization\nCleaning Databases, Address Checking, Output to Printer, E-Mailer, or Printed Mailing Catalog'),
('hero_cta_text',       'Contact Us'),
('about_home_text',     'Clevora is a privately held organization and was incorporated in 2011. Since then Clevora has gained deep business insights and expertise in the outsourcing industry. We are proud of our client-centric models and dynamic execution strategies that yield concrete results. Our state-of-the-art infrastructure enables seamless remote team integrations and guarantees continuous business performance.'),
('about_full_history',  'Clevora was established in 2011 in Delhi, India, with a vision to provide world-class business process outsourcing solutions. Over the last decade, we have expanded from a small team of 10 agents to a global enterprise service provider. Our operations span across call center support, technical helpdesks, data entry services, content moderation, and custom software engineering. We maintain high standards of quality assurance and data security, keeping in line with modern compliance frameworks.'),
('about_mission',       'To empower organizations globally by providing high-quality, secure, and cost-effective outsourcing services that maximize operating efficiency.'),
('about_vision',        'To be the preferred global outsourcing partner recognized for operational excellence, integrity, and innovative business process solutions.'),
('stats_projects',      '5500'),
('stats_industries',    '50'),
('stats_resumes',       '1670'),
('stats_clients',       '5000'),
('contact_phone',       '+91 9953310085'),
('contact_email',       'info@clevora.in'),
('contact_address',     'Delhi, India'),
('footer_subscribe_text','Clevora takes its technological capabilities very seriously and has acquired all the technologies that are required for running a successful call center in this competitive market segment.'),
('tech_infrastructure', 'Our production servers are housed in a Tier-3 secure data center in Delhi. We maintain redundant fiber-optic connectivity from multiple internet service providers, automated daily backup protocols, and UPS backup battery arrays alongside on-site diesel generators to guarantee 99.9% network availability. Physical access to our production rooms is restricted with biometric authorization.'),
('management_founder_name', 'Mayank Chandhok'),
('management_founder_role', 'Founder & Managing Director'),
('management_founder_bio',  'Mayank Chandhok founded Clevora in 2011 with the goal of bridging the gap between global enterprises and skilled talent in India. Under his leadership, Clevora has grown into a trusted outsourcing partner for hundreds of organizations globally.');
