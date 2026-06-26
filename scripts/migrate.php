<?php
/**
 * Clevora — Unified Migration Runner
 * ====================================
 * Executes all numbered SQL migration files from the migrations/ directory
 * in sequential order (001_*.sql, 002_*.sql, ...).
 *
 * Usage:
 *   php scripts/migrate.php            — Run all migrations
 *   php scripts/migrate.php 003        — Run only migration 003_*.sql
 *   php scripts/migrate.php --list     — List available migrations
 */

require_once __DIR__ . '/../includes/db.php';

if (!$pdo) {
    fwrite(STDERR, "✗ Database connection failed. Make sure MySQL is running.\n");
    exit(1);
}

$migrationsDir = realpath(__DIR__ . '/../migrations');

if (!$migrationsDir || !is_dir($migrationsDir)) {
    fwrite(STDERR, "✗ Migrations directory not found.\n");
    exit(1);
}

// Discover all numbered migration files
$files = glob($migrationsDir . '/[0-9][0-9][0-9]_*.sql');
sort($files); // alphabetical = numerical with zero-padded prefixes

if (empty($files)) {
    echo "No migration files found in migrations/\n";
    exit(0);
}

// Parse CLI arguments
$filter = null;
$listOnly = false;

if (isset($argv[1])) {
    if ($argv[1] === '--list') {
        $listOnly = true;
    } else {
        $filter = $argv[1]; // e.g. "003" or "006"
    }
}

// --list mode
if ($listOnly) {
    echo "Available migrations:\n";
    foreach ($files as $file) {
        $name = basename($file);
        $size = round(filesize($file) / 1024, 1);
        echo "  ▸ {$name}  ({$size} KB)\n";
    }
    echo "\nTotal: " . count($files) . " migration(s)\n";
    exit(0);
}

// Filter to a single migration if specified
if ($filter !== null) {
    $files = array_filter($files, function ($f) use ($filter) {
        return strpos(basename($f), $filter) === 0;
    });
    if (empty($files)) {
        fwrite(STDERR, "✗ No migration file found matching '{$filter}'.\n");
        exit(1);
    }
}

echo "╔══════════════════════════════════════════╗\n";
echo "║     Clevora Migration Runner             ║\n";
echo "╚══════════════════════════════════════════╝\n\n";

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);

$succeeded = 0;
$failed    = 0;
$total     = count($files);

foreach ($files as $file) {
    $name = basename($file);
    echo "▸ Running {$name} ... ";

    try {
        $sql = file_get_contents($file);
        $pdo->exec($sql);
        echo "✓ OK\n";
        $succeeded++;
    } catch (Exception $e) {
        echo "✗ FAILED\n";
        echo "  Error: " . $e->getMessage() . "\n";
        $failed++;
    }
}

echo "\n────────────────────────────────────────────\n";
echo "Results: {$succeeded}/{$total} succeeded";
if ($failed > 0) {
    echo ", {$failed} failed";
}
echo "\n";

// Quick table verification
echo "\nTable verification:\n";
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "  Total tables in database: " . count($tables) . "\n";

// Verify admin login if admin_users exists
if (in_array('admin_users', $tables)) {
    $admin = $pdo->query("SELECT username, password FROM admin_users WHERE username = 'admin'")->fetch();
    if ($admin && password_verify('admin123', $admin['password'])) {
        echo "  Admin login: ✓ verified (admin / admin123)\n";
    } else {
        echo "  Admin login: ⚠ verification failed\n";
    }
}

echo "\nMigration complete!\n";
exit($failed > 0 ? 1 : 0);
