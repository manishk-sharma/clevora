<?php
/**
 * Admin Authentication Middleware
 * Include at the top of every admin page (except index.php/login).
 * Provides: session validation, CSRF helpers, input sanitization, upload validation.
 */

// ── Session Management ──────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session timeout: 30 minutes of inactivity
$session_timeout = 1800;
if (isset($_SESSION['clevora_last_activity'])) {
    if (time() - $_SESSION['clevora_last_activity'] > $session_timeout) {
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['login_message'] = 'Session expired. Login again.';
        header('Location: /admin/index.php');
        exit;
    }
}
$_SESSION['clevora_last_activity'] = time();

// Check authentication
if (empty($_SESSION['clevora_admin'])) {
    header('Location: /admin/index.php');
    exit;
}

// Load database connection
require_once __DIR__ . '/../../includes/db.php';

// ── CSRF Token Helpers ──────────────────────────────────

/**
 * Generate or return the current CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output a hidden CSRF input field for forms
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Validate the CSRF token from a POST request
 */
function verify_csrf(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

// ── Input Sanitization ──────────────────────────────────

/**
 * Clean user input — strip tags, trim, and prevent XSS
 */
function clean_input(string $value): string {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

/**
 * Clean input but allow basic HTML (from WYSIWYG)
 * Only allows: <b>, <i>, <strong>, <em>, <ul>, <ol>, <li>, <a>, <br>, <p>
 */
function clean_rich_input(string $value): string {
    return strip_tags(trim($value), '<b><i><strong><em><ul><ol><li><a><br><p>');
}

// ── Upload Validation ───────────────────────────────────

/**
 * Validate an uploaded file
 * @return string|true Returns error message on failure, true on success
 */
function validate_upload(array $file, array $allowed_types = ['jpg','jpeg','png','svg','webp'], int $max_size_mb = 5) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds server upload limit.',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form upload limit.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
        ];
        return $errors[$file['error']] ?? 'Unknown upload error.';
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_types)) {
        return 'Invalid file type. Allowed: ' . implode(', ', $allowed_types);
    }
    
    $max_bytes = $max_size_mb * 1024 * 1024;
    if ($file['size'] > $max_bytes) {
        return "File too large. Maximum: {$max_size_mb}MB.";
    }
    
    return true;
}

/**
 * Process and save an uploaded file
 * @return string|false Returns the public URL on success, false on failure
 */
function save_upload(array $file, string $prefix = 'upload'): string|false {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = $prefix . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
    
    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0775, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], UPLOAD_PATH . $filename)) {
        // Also record in media_library
        global $pdo;
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO media_library (filename, original_name, mime_type, size, url, alt_text) VALUES (?, ?, ?, ?, ?, '')");
                $stmt->execute([$filename, $file['name'], $file['type'], $file['size'], '/assets/images/uploads/' . $filename]);
            } catch (Exception $e) {
                // Non-critical — just log
                error_log('Media library insert failed: ' . $e->getMessage());
            }
        }
        return '/assets/images/uploads/' . $filename;
    }
    
    return false;
}

// ── Flash Messages ──────────────────────────────────────

/**
 * Set a flash message
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear the flash message
 */
function get_flash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

// ── Contact Setting Helper ──────────────────────────────

/**
 * Get a contact setting value
 */
function contact_setting(string $key, ?PDO $pdo): string {
    if (!$pdo) return '';
    try {
        $s = $pdo->prepare("SELECT setting_value FROM contact_settings WHERE setting_key = ?");
        $s->execute([$key]);
        $val = $s->fetchColumn();
        return $val !== false ? $val : '';
    } catch (Exception $e) {
        return '';
    }
}
