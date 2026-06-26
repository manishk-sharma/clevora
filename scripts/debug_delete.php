<?php
require_once __DIR__ . '/../includes/db.php';
// Clean up XSS test records
$pdo->query("DELETE FROM hero_sliders WHERE main_heading LIKE '%XSS%' OR main_heading LIKE '%test%alert%'");
echo "Cleaned XSS test data.\n";

// Now test that the delete form actually works by checking CSRF
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
echo "CSRF token: " . $_SESSION['csrf_token'] . "\n";

// Show current sliders
$sliders = $pdo->query("SELECT id, main_heading FROM hero_sliders ORDER BY id")->fetchAll();
echo "Current sliders:\n";
foreach ($sliders as $s) {
    echo "  ID={$s['id']} - {$s['main_heading']}\n";
}
