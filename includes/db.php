<?php
require_once __DIR__ . '/../config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // If the database does not exist or isn't running, we catch the exception.
    // For local development robustness, we can define a mock $pdo or output the error.
    // We die if it's a critical production error, but locally let's log the error.
    error_log('DB Connection failed: ' . $e->getMessage());
    $pdo = null;
}

/**
 * Helper: get a single site setting
 */
function setting(string $key, ?PDO $pdo): string {
    if (!$pdo) {
        return get_default_setting($key);
    }
    try {
        $s = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $s->execute([$key]);
        $val = $s->fetchColumn();
        return $val !== false ? $val : get_default_setting($key);
    } catch (Exception $e) {
        return get_default_setting($key);
    }
}

/**
 * Default fallback settings
 */
function get_default_setting(string $key): string {
    $defaults = [
        'hero_headline' => 'Database Management',
        'hero_bullets' => "Preventing Oversized Non-Standard Data Formats\nMultiple Sourcing and Non-Standard Data systems\nAddress Verification, Postal Code Correction, NCOA and Standardization\nCleaning Databases, Address Checking, Output to Printer, E-Mailer, or Printed Mailing Catalog",
        'hero_cta_text' => 'Contact Us',
        'about_home_text' => 'Clevora is a privately held organization and was incorporated in 2011. Since then Clevora has gained deep business insights and expertise in the outsourcing industry.',
        'about_full_history' => 'Clevora was established in 2011 in Delhi, India, with a vision to provide world-class business process outsourcing solutions.',
        'about_mission' => 'To empower organizations globally by providing high-quality, secure, and cost-effective outsourcing services.',
        'about_vision' => 'To be the preferred global outsourcing partner recognized for operational excellence.',
        'stats_projects' => '5500',
        'stats_industries' => '50',
        'stats_resumes' => '1670',
        'stats_clients' => '5000',
        'contact_phone' => '+91 9953310085',
        'contact_email' => 'info@clevora.in',
        'contact_address' => 'Delhi, India',
        'footer_subscribe_text' => 'Clevora takes its technological capabilities very seriously and has acquired all the technologies required.',
        'tech_infrastructure' => 'Our production servers are housed in a secure data center environment with high redundancy.',
        'management_founder_name' => 'Mayank Chandhok',
        'management_founder_role' => 'Founder & Managing Director',
        'management_founder_bio' => 'Mayank Chandhok founded Clevora in 2011 with the goal of bridging the gap between global enterprises and skilled talent in India.'
    ];
    return $defaults[$key] ?? '';
}
