<?php
require_once __DIR__ . '/includes/db.php';

if (!$pdo) {
    die("Database connection failed. Make sure MySQL is running.\n");
}

try {
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    
    // We execute the multi-query SQL string
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $pdo->exec($sql);
    
    echo "Database clevora_db initialized and seeded successfully!\n";
} catch (Exception $e) {
    echo "Error seeding database: " . $e->getMessage() . "\n";
}
