<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/init.php';

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    die("Δεν έχετε πρόσβαση σε αυτή τη σελίδα.");
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_panel.php?msg=deleted");
exit();
