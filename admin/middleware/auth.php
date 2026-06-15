<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['clevora_admin'])) {
    header('Location: /admin/index.php');
    exit;
}
require_once __DIR__ . '/../../includes/db.php';
