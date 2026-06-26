<?php
require 'includes/db.php';
try {
    $stmt = $pdo->prepare('DELETE FROM hero_sliders WHERE id = ?');
    $stmt->execute([6]);
    echo 'Deleted: ' . $stmt->rowCount();
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}