<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/db.php';

// Try to autoload composer libraries for PHPMailer if present
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'errors' => ['Invalid form data submitted.']]);
    exit;
}

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$phone = trim($input['phone'] ?? '');
$interest = trim($input['interest'] ?? '');
$message = trim($input['message'] ?? '');

$errors = [];

if (empty($name)) {
    $errors[] = 'Name field is required.';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}
if (empty($message)) {
    $errors[] = 'Message content is required.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// 1. Insert into Database leads table
$db_success = false;
if ($pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO leads (name, email, phone, interest, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $interest, $message]);
        $db_success = true;
    } catch(Exception $e) {
        error_log('Lead insertion error: ' . $e->getMessage());
        // We can still try to continue even if DB logging fails
    }
} else {
    // If DB is offline, we'll note it but still try to return success if we can process details
    error_log('Lead insertion failed: DB connection is offline.');
}

// 2. Send Email Notification via PHPMailer (with graceful fallback)
$email_sent = false;
$email_error = '';

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USER;
        $mail->Password   = MAIL_PASS;
        $mail->SMTPSecure = MAIL_PORT === 465 ? 'ssl' : 'tls';
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_USER, SITE_NAME);
        $mail->addAddress(MAIL_TO);
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'New Lead: ' . htmlspecialchars($interest ?: 'General Outsourcing');
        
        $body = "<h2>New Lead from " . htmlspecialchars(SITE_NAME) . "</h2>";
        $body .= "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
        $body .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        $body .= "<p><strong>Phone:</strong> " . htmlspecialchars($phone ?: 'Not provided') . "</p>";
        $body .= "<p><strong>Area of Interest:</strong> " . htmlspecialchars($interest ?: 'General inquiry') . "</p>";
        $body .= "<p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";
        
        $mail->Body = $body;
        $mail->send();
        $email_sent = true;
      } catch (Exception $e) {
        $email_error = $e->getMessage();
        error_log('PHPMailer failed: ' . $email_error);
    }
} else {
    // PHPMailer not installed, fallback to standard PHP mail()
    $to = MAIL_TO;
    $subject = 'New Lead: ' . ($interest ?: 'General Outsourcing');
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: <" . MAIL_USER . ">\r\n";
    $headers .= "Reply-To: <$email>\r\n";
    
    $body = "Name: " . htmlspecialchars($name) .
        "<br>Email: " . htmlspecialchars($email) .
        "<br>Phone: " . htmlspecialchars($phone) .
        "<br>Interest: " . htmlspecialchars($interest) .
        "<br>Message: " . nl2br(htmlspecialchars($message));
    
    // We suppress error just in case local environment lacks mail agent configuration
    $email_sent = @mail($to, $subject, $body, $headers);
    if (!$email_sent) {
        error_log('PHP mail() fallback failed to send.');
    }
}

if (!$db_success && !$email_sent) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'errors' => ['We could not process your message right now. Please try again later.']
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your message has been received successfully. Our team will contact you shortly.'
]);
exit;
