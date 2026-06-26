<?php
/**
 * Clevora — Database Seeder
 * ==========================
 * Seeds default/demo data into the database after migrations have been run.
 * Handles legacy gallery-to-album migration automatically.
 *
 * Usage:
 *   php scripts/seed.php             — Run all seeders
 *   php scripts/seed.php gallery     — Run only gallery seeder
 *   php scripts/seed.php --list      — List available seeders
 *
 * Safe to re-run — uses existence checks to avoid duplication.
 */

require_once __DIR__ . '/../includes/db.php';

if (!$pdo) {
    fwrite(STDERR, "✗ Database connection failed. Make sure MySQL is running.\n");
    exit(1);
}

// ─── Helpers ────────────────────────────────────────────────

function slugify(string $text, PDO $pdo): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text) ?: $text;
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);

    if (empty($text)) {
        $text = 'album-' . rand(100, 999);
    }

    $original = $text;
    $counter = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM gallery_albums WHERE slug = ?");
        $stmt->execute([$text]);
        if ($stmt->fetchColumn() == 0) break;
        $text = $original . '-' . ($counter++);
    }

    return $text;
}

// ─── Seeders ────────────────────────────────────────────────

/**
 * Gallery seeder — migrates legacy `gallery` table rows into album-based system.
 */
function seed_gallery(PDO $pdo): void {
    echo "  ▸ Gallery seeder\n";

    // Check if gallery_albums table exists
    $check = $pdo->query("SHOW TABLES LIKE 'gallery_albums'")->fetchAll();
    if (count($check) === 0) {
        echo "    ⚠ gallery_albums table does not exist. Run migrations first.\n";
        return;
    }

    $albumCount = (int) $pdo->query("SELECT COUNT(*) FROM gallery_albums")->fetchColumn();
    if ($albumCount > 0) {
        echo "    ⏩ Skipped — gallery_albums already has {$albumCount} album(s).\n";
        return;
    }

    // Try migrating from legacy gallery table
    $legacyExists = $pdo->query("SHOW TABLES LIKE 'gallery'")->fetchAll();
    if (count($legacyExists) > 0) {
        $legacyRows = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, id ASC")->fetchAll();

        if (count($legacyRows) > 0) {
            echo "    Migrating " . count($legacyRows) . " legacy gallery record(s)...\n";
            $migrated = 0;

            foreach ($legacyRows as $row) {
                $title = !empty(trim($row['title'] ?? ''))
                    ? trim($row['title'])
                    : (!empty(trim($row['caption'] ?? ''))
                        ? trim($row['caption'])
                        : 'Album ' . $row['id']);

                $slug        = slugify($title, $pdo);
                $description = !empty(trim($row['description'] ?? ''))
                    ? trim($row['description'])
                    : 'Legacy gallery item: ' . ($row['category'] ?? 'General');
                $coverImage  = $row['image_url'];
                $sortOrder   = $row['sort_order'];
                $isActive    = $row['status'] ?? 1;

                $stmtAlbum = $pdo->prepare(
                    "INSERT INTO gallery_albums (title, slug, description, cover_image, sort_order, is_active, created_at, updated_at)
                     VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())"
                );
                $stmtAlbum->execute([$title, $slug, $description, $coverImage, $sortOrder, $isActive]);
                $albumId = $pdo->lastInsertId();

                $stmtImg = $pdo->prepare(
                    "INSERT INTO gallery_images (album_id, image, caption, sort_order, is_active, created_at, updated_at)
                     VALUES (?, ?, ?, 1, 1, NOW(), NOW())"
                );
                $stmtImg->execute([$albumId, $row['image_url'], $row['caption'] ?? '']);

                $migrated++;
            }
            echo "    ✓ Migrated {$migrated} legacy item(s) into albums.\n";
            return;
        }
    }

    // No legacy data — seed defaults
    echo "    Seeding default albums...\n";

    $defaults = [
        [
            'title'       => 'Modern Operations Workspace',
            'description' => 'Collaborative spaces designed for productivity and global client support.',
            'cover_image' => '/assets/images/gallery-1.jpg',
            'images'      => [
                ['image' => '/assets/images/gallery-1.jpg', 'caption' => 'Open floor design with ergonomic seating'],
                ['image' => '/assets/images/gallery-3.jpg', 'caption' => 'Collaborative brainstorming area'],
            ]
        ],
        [
            'title'       => 'Server Room & Infrastructure',
            'description' => 'Technology environment supporting secure BPO and IT operations with redundancy.',
            'cover_image' => '/assets/images/gallery-2.jpg',
            'images'      => [
                ['image' => '/assets/images/gallery-2.jpg', 'caption' => 'High availability server racks'],
                ['image' => '/assets/images/gallery-5.jpg', 'caption' => 'Secure enterprise backup systems'],
            ]
        ],
        [
            'title'       => 'Corporate Training Center',
            'description' => 'Continuous learning and skill improvement sessions for professional excellence.',
            'cover_image' => '/assets/images/gallery-4.jpg',
            'images'      => [
                ['image' => '/assets/images/gallery-4.jpg', 'caption' => 'Interactive session in progress'],
            ]
        ]
    ];

    foreach ($defaults as $i => $def) {
        $slug = slugify($def['title'], $pdo);
        $stmt = $pdo->prepare(
            "INSERT INTO gallery_albums (title, slug, description, cover_image, sort_order, is_active, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())"
        );
        $stmt->execute([$def['title'], $slug, $def['description'], $def['cover_image'], $i * 10]);
        $albumId = $pdo->lastInsertId();

        foreach ($def['images'] as $img) {
            $stmtImg = $pdo->prepare(
                "INSERT INTO gallery_images (album_id, image, caption, sort_order, is_active, created_at, updated_at)
                 VALUES (?, ?, ?, 0, 1, NOW(), NOW())"
            );
            $stmtImg->execute([$albumId, $img['image'], $img['caption']]);
        }
    }
    echo "    ✓ Default albums seeded.\n";
}

/**
 * Verification summary — shows row counts for key tables.
 */
function seed_verify(PDO $pdo): void {
    echo "\n  ─── Data Summary ───\n";

    $checks = [
        'admin_users'         => "SELECT COUNT(*) FROM admin_users",
        'hero_sliders'        => "SELECT COUNT(*) FROM hero_sliders",
        'homepage_sections'   => "SELECT COUNT(*) FROM homepage_sections",
        'careers'             => "SELECT COUNT(*) FROM careers",
        'career_settings'     => "SELECT COUNT(*) FROM career_settings",
        'technology_sections' => "SELECT COUNT(*) FROM technology_sections",
        'technology_settings' => "SELECT COUNT(*) FROM technology_settings",
        'gallery_albums'      => "SELECT COUNT(*) FROM gallery_albums",
        'gallery_images'      => "SELECT COUNT(*) FROM gallery_images",
        'contact_settings'    => "SELECT COUNT(*) FROM contact_settings",
        'seo_settings'        => "SELECT COUNT(*) FROM seo_settings",
    ];

    foreach ($checks as $label => $query) {
        try {
            $count = $pdo->query($query)->fetchColumn();
            echo "    {$label}: {$count} row(s)\n";
        } catch (Exception $e) {
            echo "    {$label}: — (table missing)\n";
        }
    }
}

// ─── Main ───────────────────────────────────────────────────

$seeders = [
    'gallery' => 'seed_gallery',
];

// Parse CLI
$filter   = null;
$listOnly = false;

if (isset($argv[1])) {
    if ($argv[1] === '--list') {
        $listOnly = true;
    } else {
        $filter = strtolower($argv[1]);
    }
}

if ($listOnly) {
    echo "Available seeders:\n";
    foreach (array_keys($seeders) as $name) {
        echo "  ▸ {$name}\n";
    }
    exit(0);
}

echo "╔══════════════════════════════════════════╗\n";
echo "║     Clevora Database Seeder              ║\n";
echo "╚══════════════════════════════════════════╝\n\n";

if ($filter !== null) {
    if (!isset($seeders[$filter])) {
        fwrite(STDERR, "✗ Unknown seeder: '{$filter}'\n");
        exit(1);
    }
    $seeders[$filter]($pdo);
} else {
    foreach ($seeders as $name => $fn) {
        $fn($pdo);
    }
}

seed_verify($pdo);

echo "\nSeeding complete!\n";
