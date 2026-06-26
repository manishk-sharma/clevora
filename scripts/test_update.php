<?php
require 'includes/db.php';
try {
    $stmt = $pdo->prepare('UPDATE hero_sliders SET main_heading=? WHERE id=?');
    $stmt->execute(['Test Update', 1]);
    echo 'Updated: ' . $stmt->rowCount();
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}