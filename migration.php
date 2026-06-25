<?php
require_once __DIR__ . '/includes/db.php';

if (!$pdo) {
    die("Database connection failed. Make sure MySQL is running.\n");
}

echo "Starting migration...\n";

try {
    // 1. Create service_categories table
    $pdo->exec("CREATE TABLE IF NOT EXISTS service_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        slug VARCHAR(150) UNIQUE NOT NULL,
        description TEXT,
        icon VARCHAR(255),
        sort_order INT DEFAULT 0,
        is_active TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "Created table 'service_categories' or verified it exists.\n";

    // 2. Alter services table to add category_id and detailed_description
    // We check if they exist first to make this re-runnable
    $cols = $pdo->query("DESCRIBE services")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('category_id', $cols)) {
        $pdo->exec("ALTER TABLE services ADD COLUMN category_id INT DEFAULT NULL AFTER id;");
        echo "Added column 'category_id' to 'services' table.\n";
    }
    
    if (!in_array('detailed_description', $cols)) {
        $pdo->exec("ALTER TABLE services ADD COLUMN detailed_description LONGTEXT DEFAULT NULL AFTER intro;");
        echo "Added column 'detailed_description' to 'services' table.\n";
    }

    // Add Foreign Key constraint if not exists
    try {
        $pdo->exec("ALTER TABLE services ADD CONSTRAINT fk_services_category FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL;");
        echo "Added foreign key constraint fk_services_category.\n";
    } catch (Exception $e) {
        // Constraint might already exist
        echo "Foreign key constraint notice: " . $e->getMessage() . "\n";
    }

    // 3. Clear existing services and categories to prevent duplicates
    // We disable foreign key checks temporarily to safely truncate
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE service_categories;");
    $pdo->exec("TRUNCATE TABLE services;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "Cleaned existing categories and services data.\n";

    // 4. Seed categories
    $categories = [
        [
            'name' => 'Customer Support Services',
            'slug' => 'customer-support-services',
            'description' => 'Professional inbound, outbound, email, chat, and helpdesk solutions to elevate user engagement.',
            'icon' => '💬',
            'sort_order' => 1
        ],
        [
            'name' => 'Content & Moderation Services',
            'slug' => 'content-moderation-services',
            'description' => 'Safeguard your platform and engage audiences with high-quality content review and review moderation.',
            'icon' => '🛡️',
            'sort_order' => 2
        ],
        [
            'name' => 'E-Commerce Support',
            'slug' => 'e-commerce-support',
            'description' => 'Scale your online store with order processing, catalog listing, returns, and marketplace operations.',
            'icon' => '🛒',
            'sort_order' => 3
        ],
        [
            'name' => 'Back Office & Data Management',
            'slug' => 'back-office-data-management',
            'description' => 'Increase efficiency with professional data entry, bookkeeping, processing, and admin support.',
            'icon' => '📂',
            'sort_order' => 4
        ],
        [
            'name' => 'Finance & Accounting',
            'slug' => 'finance-accounting',
            'description' => 'Comprehensive billing support, general accounting operations, and financial processing.',
            'icon' => '💳',
            'sort_order' => 5
        ],
        [
            'name' => 'Recruitment & HR Services',
            'slug' => 'recruitment-hr-services',
            'description' => 'Recruitment process outsourcing, candidate screening, talent acquisition, and HR administration.',
            'icon' => '👥',
            'sort_order' => 6
        ],
        [
            'name' => 'Knowledge Process Outsourcing (KPO)',
            'slug' => 'knowledge-process-outsourcing-kpo',
            'description' => 'Advanced research support, business process design, and project management excellence.',
            'icon' => '🧠',
            'sort_order' => 7
        ],
        [
            'name' => 'Online Reputation Management',
            'slug' => 'online-reputation-management',
            'description' => 'Verify online reviews, manage ratings, and protect your global brand sentiment.',
            'icon' => '🌟',
            'sort_order' => 8
        ],
        [
            'name' => 'Call Center & BPO Services',
            'slug' => 'call-center-bpo-services',
            'description' => 'Inbound/outbound calling, domestic and international voice processes, and 24/7 engagement.',
            'icon' => '📞',
            'sort_order' => 9
        ]
    ];

    $stmt_cat = $pdo->prepare("INSERT INTO service_categories (name, slug, description, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $category_ids = [];
    foreach ($categories as $cat) {
        $stmt_cat->execute([$cat['name'], $cat['slug'], $cat['description'], $cat['icon'], $cat['sort_order']]);
        $category_ids[$cat['slug']] = $pdo->lastInsertId();
    }
    echo "Seeded 9 service categories.\n";

    // 5. Seed services
    $services = [
        // Category 1: Customer Support Services
        [
            'category_slug' => 'customer-support-services',
            'slug' => 'multilingual-inbound-customer-support',
            'name' => 'Multilingual Inbound Customer Support',
            'icon_url' => '/assets/images/service-language.svg',
            'intro' => 'Provide a seamless support experience for global customers in their native language.',
            'detailed_description' => 'Our multilingual inbound customer support connects your brand with a global customer base. We provide trained support agents fluent in multiple languages to resolve queries, address complaints, and deliver exceptional first-contact resolution. Your callers will enjoy speaking with native-level professionals who represent your brand with courtesy and efficiency.',
            'features' => json_encode(['24/7/365 Coverage', 'Multilingual Agents', 'CRM Logging', 'Escalation Workflows']),
            'benefits' => json_encode(['High customer satisfaction', 'Improved global trust', 'Faster issue resolution']),
            'sort_order' => 1
        ],
        [
            'category_slug' => 'customer-support-services',
            'slug' => 'multilingual-outbound-customer-support',
            'name' => 'Multilingual Outbound Customer Support',
            'icon_url' => '/assets/images/service-callcenter.svg',
            'intro' => 'Proactive outbound customer support, surveys, and follow-ups in international languages.',
            'detailed_description' => 'We offer proactive customer reach-out programs, customer feedback loops, and customer satisfaction surveys in multiple languages. Our outbound professionals handle service reminders, renewals, post-purchase surveys, and data verification to keep your database accurate and build long-term loyalty.',
            'features' => json_encode(['Automated & Manual Dialing', 'Feedback Tracking', 'Detailed Analytics', 'Multilingual Outreach']),
            'benefits' => json_encode(['Proactive engagement', 'Actionable feedback data', 'Increased retention']),
            'sort_order' => 2
        ],
        [
            'category_slug' => 'customer-support-services',
            'slug' => 'email-support',
            'name' => 'Email Support Services',
            'icon_url' => '/assets/images/service-backoffice.svg',
            'intro' => 'Accurate, timely, and branded email responses to resolve customer queries.',
            'detailed_description' => 'Email remains a core channel for detailed customer inquiries. We manage your customer service inbox with pre-defined response SLA standards. Our team handles ticketing queues, sorts complex requests, and drafts professional, personalized responses that represent your company policies accurately.',
            'features' => json_encode(['Ticket Queue Management', 'SLA Adherence', 'Template customization', 'Issue Tracking']),
            'benefits' => json_encode(['Reduced response latency', 'Consistent brand voice', 'Cost-effective support']),
            'sort_order' => 3
        ],
        [
            'category_slug' => 'customer-support-services',
            'slug' => 'chat-support',
            'name' => 'Live Chat Support',
            'icon_url' => '/assets/images/service-validation.svg',
            'intro' => 'Provide real-time help to visitors on your web, mobile app, or social store.',
            'detailed_description' => 'Instantly engage customers when they visit your site. Our live chat support team answers product questions, helps users navigate the purchase process, and resolves technical issues in real-time. We help you minimize shopping cart abandonment and turn website visitors into loyal customers.',
            'features' => json_encode(['Multi-platform Integration', 'Real-time Chat Logs', 'Co-browsing Support', 'Pre-sales Assistance']),
            'benefits' => json_encode(['Higher web conversion', 'Immediate problem solving', 'Lower bounce rates']),
            'sort_order' => 4
        ],
        [
            'category_slug' => 'customer-support-services',
            'slug' => 'technical-helpdesk-services',
            'name' => 'Technical & Non-Technical Helpdesk Services',
            'icon_url' => '/assets/images/service-software.svg',
            'intro' => 'L1 and L2 technical troubleshooting, software guidance, and helpdesk support.',
            'detailed_description' => 'Support your users through hardware, software, or account access challenges. Our helpdesk team is trained in Tier 1 and Tier 2 support levels. We resolve common issues, troubleshoot system bugs, reset credentials, and escalate complex issues systematically to your internal developers.',
            'features' => json_encode(['Tier 1 & Tier 2 Support', 'SLA Management', 'Knowledge Base Creation', 'Bug Logging']),
            'benefits' => json_encode(['Reduced dev team workload', 'Fast tech resolution', 'Enhanced user trust']),
            'sort_order' => 5
        ],

        // Category 2: Content & Moderation Services
        [
            'category_slug' => 'content-moderation-services',
            'slug' => 'content-writing-services',
            'name' => 'Content Writing Services',
            'icon_url' => '/assets/images/service-publishing.svg',
            'intro' => 'SEO-friendly copy, blogs, articles, and product copy drafted by expert writers.',
            'detailed_description' => 'Produce high-quality content that ranks and converts. Our writing team works on blogs, product descriptions, website landing pages, and newsletters. We ensure every piece of content matches your brand style guidelines and incorporates search optimization parameters.',
            'features' => json_encode(['SEO Optimization', 'Copyediting', 'Topic Research', 'Formatting & Typesetting']),
            'benefits' => json_encode(['Increased organic traffic', 'Better brand authority', 'Ready-to-publish copy']),
            'sort_order' => 6
        ],
        [
            'category_slug' => 'content-moderation-services',
            'slug' => 'video-content-moderation',
            'name' => 'Video Content Moderation',
            'icon_url' => '/assets/images/service-moderation.svg',
            'intro' => '24/7 video reviews to protect your app or web community from harmful uploads.',
            'detailed_description' => 'Protect your users from offensive, copyrighted, or malicious video uploads. Our 24/7 content operations team reviews user-generated videos, checks compliance with community guidelines, and filters out inappropriate submissions before they damage your brand reputation.',
            'features' => json_encode(['Frame-by-frame Review', 'Real-time Censorship', 'Compliance Tagging', '24/7 Coverage']),
            'benefits' => json_encode(['Brand safety', 'Safe user community', 'Compliance assurance']),
            'sort_order' => 7
        ],
        [
            'category_slug' => 'content-moderation-services',
            'slug' => 'live-streaming-content-moderation',
            'name' => 'Live Streaming Content Moderation',
            'icon_url' => '/assets/images/service-moderation.svg',
            'intro' => 'Real-time moderation of live audio and video streams to guarantee compliance.',
            'detailed_description' => 'Managing live content requires instant decisions. Our dedicated moderators monitor live streams to detect violation of terms, illegal activities, or explicit behaviors, immediately terminating or flagging accounts as necessary.',
            'features' => json_encode(['Low Latency Action', 'Audio Stream Monitoring', 'Chat Log Filtering', 'Automated Alerts Integration']),
            'benefits' => json_encode(['Immediate brand protection', 'Reduced legal liability', 'Safe online spaces']),
            'sort_order' => 8
        ],
        [
            'category_slug' => 'content-moderation-services',
            'slug' => 'social-media-content-review',
            'name' => 'Social Media Content Review',
            'icon_url' => '/assets/images/service-marketing.svg',
            'intro' => 'Monitor user reviews, comments, and tags on major social networks.',
            'detailed_description' => 'Maintain clean and positive social channels. We monitor your brand mentions, comments, and tags across platforms. We hide spam, reply to customer inquiries, and flag high-priority issues to your public relations team.',
            'features' => json_encode(['Spam Filtering', 'Multi-channel Coverage', 'Sentiment Analysis', 'Escalation Frameworks']),
            'benefits' => json_encode(['Clean social feeds', 'Faster response times', 'Brand preservation']),
            'sort_order' => 9
        ],

        // Category 3: E-Commerce Support
        [
            'category_slug' => 'e-commerce-support',
            'slug' => 'order-processing-management',
            'name' => 'Order Processing & Management',
            'icon_url' => '/assets/images/service-backoffice.svg',
            'intro' => 'Process and log online orders, verify payments, and coordinate shipping.',
            'detailed_description' => 'Keep your e-commerce operations moving smoothly. We process sales orders, check payment authorization, verify customer address details, and update inventory systems to coordinate fulfillment.',
            'features' => json_encode(['Order Entry Validation', 'Payment Status Checks', 'Inventory Updates', 'SLA Tracking']),
            'benefits' => json_encode(['Faster order dispatch', 'Fewer processing mistakes', 'Efficient inventory data']),
            'sort_order' => 10
        ],
        [
            'category_slug' => 'e-commerce-support',
            'slug' => 'order-tracking-support',
            'name' => 'Order Tracking Support',
            'icon_url' => '/assets/images/service-db.svg',
            'intro' => 'Help customers track shipments and handle carrier delay inquiries.',
            'detailed_description' => 'Respond to the common \"Where is my order?\" inquiry quickly. We track packages, interface with logistics providers, and inform customers about shipment delays or custom delivery requirements.',
            'features' => json_encode(['Tracking Status Checks', 'Logistics coordination', 'Automated Updates', 'Carrier Communication']),
            'benefits' => json_encode(['Reduced support tickets', 'Better post-purchase trust', 'Accurate delivery data']),
            'sort_order' => 11
        ],
        [
            'category_slug' => 'e-commerce-support',
            'slug' => 'product-listing-management',
            'name' => 'Product Listing Management',
            'icon_url' => '/assets/images/service-publishing.svg',
            'intro' => 'Create and maintain SKU lists, descriptions, and images in store databases.',
            'detailed_description' => 'Ensure your digital storefront is clean, structured, and search-optimized. We upload new product details, write features lists, optimize images, configure variants (size, color), and manage prices.',
            'features' => json_encode(['SKU Database Updates', 'Copywriting Optimization', 'Variant Setup', 'SEO Meta Tags']),
            'benefits' => json_encode(['High catalog accuracy', 'Reduced list time-to-market', 'Better search visibility']),
            'sort_order' => 12
        ],
        [
            'category_slug' => 'e-commerce-support',
            'slug' => 'returns-exchange-management',
            'name' => 'Returns & Exchange Management',
            'icon_url' => '/assets/images/service-validation.svg',
            'intro' => 'Log customer returns, authorize replacements, and issue credit refunds.',
            'detailed_description' => 'Make return processes simple and secure. We verify return conditions, coordinate return shipping tags, process store credits, and manage refunds following your store guidelines.',
            'features' => json_encode(['Return Status Tracking', 'Refund Verification', 'Replacement Authorization', 'Logistics Coordination']),
            'benefits' => json_encode(['Higher client loyalty', 'Better fraud prevention', 'Organized return flow']),
            'sort_order' => 13
        ],
        [
            'category_slug' => 'e-commerce-support',
            'slug' => 'marketplace-support-services',
            'name' => 'Marketplace Support Services',
            'icon_url' => '/assets/images/service-bpo.svg',
            'intro' => 'Support for Amazon, eBay, Shopify, Walmart, and custom online storefronts.',
            'detailed_description' => 'Manage listings, pricing alerts, and customer disputes across different online marketplaces. We ensure your business complies with marketplace standards to avoid listing penalties or account suspensions.',
            'features' => json_encode(['Dispute Resolution', 'Feed Quality Checks', 'Price Synchronization', 'Seller Dashboard Management']),
            'benefits' => json_encode(['Higher seller ratings', 'Multi-channel growth', 'Less admin burden']),
            'sort_order' => 14
        ],

        // Category 4: Back Office & Data Management
        [
            'category_slug' => 'back-office-data-management',
            'slug' => 'data-entry-services',
            'name' => 'Data Entry Services',
            'icon_url' => '/assets/images/service-db.svg',
            'intro' => 'Accurate, high-speed text, numeric, and database entry solutions.',
            'detailed_description' => 'Digitize your paperwork, documents, logs, and files with speed and accuracy. We enter customer records, capture scan data, transcribe forms, and build structured directories for your company.',
            'features' => json_encode(['99.9% Accuracy Guarantee', 'High-Speed Typing', 'Batch Formatting', 'Double-Key Validation']),
            'benefits' => json_encode(['Clean data assets', 'Lower operational overhead', 'Accelerated access to files']),
            'sort_order' => 15
        ],
        [
            'category_slug' => 'back-office-data-management',
            'slug' => 'data-processing',
            'name' => 'Data Processing',
            'icon_url' => '/assets/images/service-validation.svg',
            'intro' => 'Consolidate, parse, format, and organize raw text and numerical files.',
            'detailed_description' => 'Turn unstructured logs, spreadsheets, and files into organized reports. We clean duplicate data points, map missing parameters, standardize inputs, and output databases ready for system importing.',
            'features' => json_encode(['CSV/JSON Mapping', 'Data Scrubbing', 'Regex Normalization', 'Structured Reporting']),
            'benefits' => json_encode(['Actionable business data', 'Reduced import errors', 'Enhanced database health']),
            'sort_order' => 16
        ],
        [
            'category_slug' => 'back-office-data-management',
            'slug' => 'data-management',
            'name' => 'Data Management',
            'icon_url' => '/assets/images/service-db.svg',
            'intro' => 'Long-term maintenance, formatting, backups, and security checks for data storage.',
            'detailed_description' => 'Secure and maintain your data assets. We manage databases, perform routine integrity checks, implement security profiles, coordinate backups, and structure access controls for team members.',
            'features' => json_encode(['Database Auditing', 'Secure Backups', 'Access Management', 'Compliance Compliance']),
            'benefits' => json_encode(['Reduced database errors', 'Better disaster readiness', 'Enhanced data protection']),
            'sort_order' => 17
        ],
        [
            'category_slug' => 'back-office-data-management',
            'slug' => 'bookkeeping-services',
            'name' => 'Bookkeeping Services',
            'icon_url' => '/assets/images/service-backoffice.svg',
            'intro' => 'Log daily financial entries, payments, invoices, and bank statements.',
            'detailed_description' => 'Keep your cash flows and accounts organized. We log supplier invoices, record client payments, map expenses, and reconcile bank statements following standard guidelines.',
            'features' => json_encode(['QuickBooks & Xero Mapping', 'Daily Expense Audits', 'Statement Reconciling', 'Tax Prep Support']),
            'benefits' => json_encode(['Accurate financial ledger', 'Faster tax filing', 'Lower accounting overhead']),
            'sort_order' => 18
        ],
        [
            'category_slug' => 'back-office-data-management',
            'slug' => 'administrative-support',
            'name' => 'Administrative Support',
            'icon_url' => '/assets/images/service-bpo.svg',
            'intro' => 'Support for business planning, calendar booking, and email management.',
            'detailed_description' => 'Get support for your daily administrative tasks. We manage executive calendars, draft standard memos, coordinate company travel details, and filter client messages.',
            'features' => json_encode(['Calendar Coordination', 'Email Filtering', 'Document Archiving', 'Meeting Scheduling']),
            'benefits' => json_encode(['Reduced desk overhead', 'Organized operations', 'More time for core growth']),
            'sort_order' => 19
        ],

        // Category 5: Finance & Accounting
        [
            'category_slug' => 'finance-accounting',
            'slug' => 'billing-support',
            'name' => 'Billing Support',
            'icon_url' => '/assets/images/service-backoffice.svg',
            'intro' => 'Create and dispatch client invoices, track dues, and manage bill processing.',
            'detailed_description' => 'Accelerate your invoice cycles. We generate accurate customer bills, dispatch invoice notices, verify payment terms, and track overdue balances proactively.',
            'features' => json_encode(['Invoice Creation', 'Ageing Balances Tracking', 'Dispute Resolution', 'Payment Link Integration']),
            'benefits' => json_encode(['Better cash flows', 'Fewer billing disputes', 'Faster client payments']),
            'sort_order' => 20
        ],
        [
            'category_slug' => 'finance-accounting',
            'slug' => 'accounts-management',
            'name' => 'Accounts Management',
            'icon_url' => '/assets/images/service-validation.svg',
            'intro' => 'Support for accounts receivable (AR) and accounts payable (AP) processes.',
            'detailed_description' => 'Manage incoming and outgoing payment pipelines. We process supplier bills, check purchase orders, reconcile ledger accounts, and ensure invoices are paid on time.',
            'features' => json_encode(['AP Invoice Indexing', 'AR Ledger Reconciliations', 'Vendor Communication', 'Payment Scheduling']),
            'benefits' => json_encode(['Lower ledger overhead', 'Improved vendor relationships', 'Fewer payment delays']),
            'sort_order' => 21
        ],
        [
            'category_slug' => 'finance-accounting',
            'slug' => 'financial-processing-services',
            'name' => 'Financial Processing Services',
            'icon_url' => '/assets/images/service-mortgage.svg',
            'intro' => 'Process expense claims, payroll, tax documentation, and merchant payments.',
            'detailed_description' => 'Outsource complex payment transaction processing. We audit employee business expense sheets, calculate payroll details, process merchant credit card transactions, and file standard tax documentation.',
            'features' => json_encode(['Expense Validation', 'Payroll Calculations', 'Compliance Auditing', 'Refund Processing']),
            'benefits' => json_encode(['Fewer payroll errors', 'Improved tax compliance', 'Quick merchant reconciliation']),
            'sort_order' => 22
        ],
        [
            'category_slug' => 'finance-accounting',
            'slug' => 'accounting-support',
            'name' => 'Accounting Support',
            'icon_url' => '/assets/images/service-db.svg',
            'intro' => 'Draft income statements, prepare balance sheets, and audit system entries.',
            'detailed_description' => 'Support your finance directors with organized ledgers. We prepare draft balance sheets, run reconciliation audits, format general ledgers, and compile files for tax audits.',
            'features' => json_encode(['Ledger Close Support', 'Reconcile Assets', 'Audit File prep', 'Financial Formatting']),
            'benefits' => json_encode(['Audit-ready accounts', 'Lower accountant fees', 'Clear business statistics']),
            'sort_order' => 23
        ],

        // Category 6: Recruitment & HR Services
        [
            'category_slug' => 'recruitment-hr-services',
            'slug' => 'recruitment-process-outsourcing',
            'name' => 'Recruitment Process Outsourcing (RPO)',
            'icon_url' => '/assets/images/service-bpo.svg',
            'intro' => 'Outsource candidate sourcing, interview booking, and recruiter support.',
            'detailed_description' => 'Scale your hiring capability quickly. We manage job listings, source active resumes from platforms, coordinate candidate communications, and book interviews for your team.',
            'features' => json_encode(['Resume Sourcing', 'ATS Logging', 'Interview Bookings', 'Feedback Coordination']),
            'benefits' => json_encode(['Faster hires', 'Lower cost-per-hire', 'Qualified talent pipelines']),
            'sort_order' => 24
        ],
        [
            'category_slug' => 'recruitment-hr-services',
            'slug' => 'candidate-screening',
            'name' => 'Candidate Screening',
            'icon_url' => '/assets/images/service-validation.svg',
            'intro' => 'Run resume filter audits, verify experiences, and check applicant references.',
            'detailed_description' => 'Ensure you hire the right fit. We check candidate employment histories, call reference lists, review certificates, and filter out unqualified applicants.',
            'features' => json_encode(['Reference Check Calls', 'Degree Verification', 'Skills Assessments', 'Profile Summarization']),
            'benefits' => json_encode(['Better hire quality', 'Lower hiring risks', 'Clean background data']),
            'sort_order' => 25
        ],
        [
            'category_slug' => 'recruitment-hr-services',
            'slug' => 'talent-acquisition-support',
            'name' => 'Talent Acquisition Support',
            'icon_url' => '/assets/images/service-language.svg',
            'intro' => 'Identify specialized profiles, build talent pools, and manage outreach.',
            'detailed_description' => 'Target and source talent for niche roles. We research industry professionals, run cold outreach campaigns, and present pre-screened talent cards to your hiring team.',
            'features' => json_encode(['Niche Role Sourcing', 'Outreach Writing', 'Talent Database Logs', 'Market Rate Audits']),
            'benefits' => json_encode(['Direct access to talent', 'Better industry insight', 'Reduced hire delays']),
            'sort_order' => 26
        ],
        [
            'category_slug' => 'recruitment-hr-services',
            'slug' => 'hr-operations-support',
            'name' => 'HR Operations Support',
            'icon_url' => '/assets/images/service-backoffice.svg',
            'intro' => 'Track worker attendance, manage employee files, and log HR requests.',
            'detailed_description' => 'Outsource your employee administration overhead. We manage employee files, log leave requests, monitor onboarding progress, and handle standard payroll documentation.',
            'features' => json_encode(['Leave System Checks', 'Onboarding Logs', 'Worker Record Audits', 'Benefits Administration']),
            'benefits' => json_encode(['Organized employee logs', 'Reduced compliance risk', 'Lower admin costs']),
            'sort_order' => 27
        ],

        // Category 7: Knowledge Process Outsourcing (KPO)
        [
            'category_slug' => 'knowledge-process-outsourcing-kpo',
            'slug' => 'research-support',
            'name' => 'Research Support',
            'icon_url' => '/assets/images/service-db.svg',
            'intro' => 'Market research, competitor analysis, database compilation, and trend research.',
            'detailed_description' => 'Make business decisions backed by clean, structured research. We compile market reports, map competitor pricing, organize target industry databases, and track online search trends.',
            'features' => json_encode(['Market Sizing Reports', 'Competitor Auditing', 'Database Compilations', 'Trend Tracking']),
            'benefits' => json_encode(['Data-driven strategies', 'Identified growth areas', 'Better competitive edge']),
            'sort_order' => 28
        ],
        [
            'category_slug' => 'knowledge-process-outsourcing-kpo',
            'slug' => 'business-process-management',
            'name' => 'Business Process Management',
            'icon_url' => '/assets/images/service-bpo.svg',
            'intro' => 'Design, map, optimize, and document your operational workflows.',
            'detailed_description' => 'Improve standard operating procedures. We audit current department workflows, identify bottlenecks, map standard flows, and draft clear training documents.',
            'features' => json_encode(['SOP Writing', 'Workflow Mapping', 'Efficiency Audits', 'Team Training Guides']),
            'benefits' => json_encode(['Increased operating speed', 'Consistent quality outputs', 'Faster staff onboarding']),
            'sort_order' => 29
        ],
        [
            'category_slug' => 'knowledge-process-outsourcing-kpo',
            'slug' => 'process-excellence-services',
            'name' => 'Process Excellence Services',
            'icon_url' => '/assets/images/service-validation.svg',
            'intro' => 'Six Sigma workflow design, quality assurance checks, and optimization audits.',
            'detailed_description' => 'Achieve operational excellence. We implement quality monitoring systems, review agent calls and files, log errors, and coordinate process improvements to lower mistakes.',
            'features' => json_encode(['Quality Audits', 'Error Logs Tracking', 'Six Sigma Methods', 'KPI Reports']),
            'benefits' => json_encode(['Lower mistake rates', 'Highly efficient workflows', 'Enhanced customer satisfaction']),
            'sort_order' => 30
        ],
        [
            'category_slug' => 'knowledge-process-outsourcing-kpo',
            'slug' => 'project-management-support',
            'name' => 'Project Management Support',
            'icon_url' => '/assets/images/service-software.svg',
            'intro' => 'Project tracking, milestone scheduling, task updates, and report delivery.',
            'detailed_description' => 'Keep complex projects on track. We manage task updates in software (Jira, Trello, Asana), track milestones, coordinate client updates, and compile progress reports.',
            'features' => json_encode(['Milestone Tracking', 'PM Software Logs', 'Status Reporting', 'Team coordination']),
            'benefits' => json_encode(['On-time delivery', 'Transparent timelines', 'Reduced coordination costs']),
            'sort_order' => 31
        ],

        // Category 8: Online Reputation Management
        [
            'category_slug' => 'online-reputation-management',
            'slug' => 'online-reviews-verification-services',
            'name' => 'Online Reviews Verification Services',
            'icon_url' => '/assets/images/service-validation.svg',
            'intro' => 'Audit review sites for spam, identify fake comments, and report violations.',
            'detailed_description' => 'Ensure your online ratings remain genuine. We audit platforms (Google Business, Trustpilot, App Store) for spam or fake negative reviews, file disputes, and track resolution progress.',
            'features' => json_encode(['Review Auditing', 'Platform Dispute Filings', 'Spam detection', 'Status Logs']),
            'benefits' => json_encode(['Higher rating trust', 'Spam protection', 'Improved search CTR']),
            'sort_order' => 32
        ],
        [
            'category_slug' => 'online-reputation-management',
            'slug' => 'customer-feedback-management',
            'name' => 'Customer Feedback Management',
            'icon_url' => '/assets/images/service-moderation.svg',
            'intro' => 'Monitor ratings, draft replies, and compile customer issues reports.',
            'detailed_description' => 'Respond to customer reviews professionally. We draft custom replies to negative and positive reviews, help resolve client complaints, and log common issues for your product team.',
            'features' => json_encode(['Review Replies', 'Issue Escalations', 'Sentiment Analysis', 'Feedback Reports']),
            'benefits' => json_encode(['Better brand trust', 'Fewer churned customers', 'Identified product issues']),
            'sort_order' => 33
        ],

        // Category 9: Call Center & BPO Services
        [
            'category_slug' => 'call-center-bpo-services',
            'slug' => 'domestic-voice-processes',
            'name' => 'Domestic Voice Processes',
            'icon_url' => '/assets/images/service-callcenter.svg',
            'intro' => 'High-quality customer support and sales operations for local audiences.',
            'detailed_description' => 'Reach and support your national customer base. We provide call center operations optimized for domestic customers, offering support, lead verification, telesales, and account help.',
            'features' => json_encode(['Local Support Agents', 'High Call Capacity', 'CRM Integrations', 'Call Recording']),
            'benefits' => json_encode(['Lower call queue times', 'Optimized localized support', 'Efficient sales dialing']),
            'sort_order' => 34
        ],
        [
            'category_slug' => 'call-center-bpo-services',
            'slug' => 'international-voice-processes',
            'name' => 'International Voice Processes',
            'icon_url' => '/assets/images/service-language.svg',
            'intro' => 'Global outbound and inbound voice solutions with accent-trained agents.',
            'detailed_description' => 'Provide accent-trained, professional voice support to international callers. We handle queries, troubleshoot tech problems, confirm order details, and follow up on global leads 24/7.',
            'features' => json_encode(['Global dialer support', 'Accent Training', '24/7 Timezone coverage', 'International VoIP routes']),
            'benefits' => json_encode(['Improved global retention', 'Round-the-clock availability', 'Professional brand voice']),
            'sort_order' => 35
        ],
        [
            'category_slug' => 'call-center-bpo-services',
            'slug' => 'non-voice-processes',
            'name' => 'Non-Voice Processes',
            'icon_url' => '/assets/images/service-backoffice.svg',
            'intro' => 'Support for chat, email, ticket entry, and backend tasks.',
            'detailed_description' => 'Handle high-volume tickets without voice overhead. We manage customer support tickets, process application forms, log customer requests, and audit accounts via text channels.',
            'features' => json_encode(['Email & Chat queues', 'Form Transcriptions', 'High SLA compliance', 'Database Logging']),
            'benefits' => json_encode(['Lower cost per ticket', 'Efficient bulk handling', 'Flexible agent scaling']),
            'sort_order' => 36
        ],
        [
            'category_slug' => 'call-center-bpo-services',
            'slug' => 'shared-service-center-operations',
            'name' => 'Shared Service Center Operations',
            'icon_url' => '/assets/images/service-bpo.svg',
            'intro' => 'Consolidate multiple company departments into one shared operations center.',
            'detailed_description' => 'Improve operational efficiency by combining backend tasks. We set up shared services for data management, invoice logging, employee benefits, and basic customer help across your subsidiaries.',
            'features' => json_encode(['Cross-trained Agents', 'Standardized SOPs', 'Unified Reporting', 'Resource optimization']),
            'benefits' => json_encode(['Higher cost savings', 'Standardized quality', 'Simplified management']),
            'sort_order' => 37
        ],
        [
            'category_slug' => 'call-center-bpo-services',
            'slug' => '24x7-customer-engagement-solutions',
            'name' => '24x7 Customer Engagement Solutions',
            'icon_url' => '/assets/images/service-callcenter.svg',
            'intro' => 'Ensure continuous coverage for customer inquiries across all global timezones.',
            'detailed_description' => 'Keep your support channels active 24/7. We schedule day, night, and weekend shifts to monitor incoming chats, answer urgent calls, reply to emails, and ensure your customers always get assistance.',
            'features' => json_encode(['24/7 Shift Rotations', 'Cross-channel Monitoring', 'Emergency Escalations', 'Consistent SLAs']),
            'benefits' => json_encode(['Zero missed opportunities', 'Stronger global retention', 'Outstanding service reviews']),
            'sort_order' => 38
        ]
    ];

    $stmt_svc = $pdo->prepare("INSERT INTO services (category_id, slug, name, icon_url, intro, detailed_description, features, benefits, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)");
    foreach ($services as $svc) {
        $cat_id = $category_ids[$svc['category_slug']] ?? null;
        $stmt_svc->execute([
            $cat_id,
            $svc['slug'],
            $svc['name'],
            $svc['icon_url'],
            $svc['intro'],
            $svc['detailed_description'],
            $svc['features'],
            $svc['benefits'],
            $svc['sort_order']
        ]);
    }
    echo "Seeded " . count($services) . " services successfully!\n";

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
