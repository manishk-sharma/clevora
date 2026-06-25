<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/db.php';

// Include PHPMailer manually
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'errors' => ['Invalid email submitted.']]);
    exit;
}

$email = trim($input['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'errors' => ['A valid email address is required.']]);
    exit;
}

// 1. Database Operations - Create table if not exists and Insert subscriber
$db_success = false;
if ($pdo) {
    try {
        // Create table if not exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS subscribers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(150) UNIQUE NOT NULL,
            status VARCHAR(20) DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // Check if subscriber exists
        $stmt = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
        $stmt->execute([$email]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Already subscribed, return early with success
            echo json_encode([
                'success' => true,
                'message' => 'Thank you! You are already subscribed to our newsletter.'
            ]);
            exit;
        }

        // Insert new subscriber
        $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->execute([$email]);
        $db_success = true;
    } catch(Exception $e) {
        error_log('Subscriber database error: ' . $e->getMessage());
    }
}

// 2. Send Notification Email to Admin
$email_sent = false;
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USER;
        $mail->Password   = MAIL_PASS;
        $mail->SMTPSecure = MAIL_PORT === 465 ? 'ssl' : 'tls';
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_USER, SITE_NAME);
        $mail->addAddress(MAIL_TO);

        $mail->isHTML(true);
        $mail->Subject = 'New Newsletter Subscription: ' . htmlspecialchars($email);
        
        $body = "<h2>New Newsletter Subscriber on " . htmlspecialchars(SITE_NAME) . "</h2>";
        $body .= "<p>A user has subscribed to the newsletter with the following email address:</p>";
        $body .= "<p><strong>Email:</strong> <a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></p>";
        $body .= "<p><strong>Date/Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
        
        $mail->Body = $body;
        $mail->send();
        $email_sent = true;
    } catch (Exception $e) {
        error_log('PHPMailer newsletter subscription alert failed: ' . $e->getMessage());
    }
}

// Return response
echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your subscription was successful. You will receive our newsletter shortly.'
]);
exit;
