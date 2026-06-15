<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clevora_db');
define('DB_USER', 'root');
define('DB_PASS', '');

define('MAIL_HOST',   'smtp.gmail.com');
define('MAIL_PORT',   587);
define('MAIL_USER',   'your@gmail.com');
define('MAIL_PASS',   'app_password');
define('MAIL_TO',     'info@clevora.in');

define('SITE_NAME',   'Clevora');

// Dynamic SITE_URL definition for local testing and production
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', $protocol . $host);

define('ADMIN_PATH',  '/admin');
define('UPLOAD_PATH', __DIR__ . '/assets/images/uploads/');
define('UPLOAD_URL',  SITE_URL . '/assets/images/uploads/');
