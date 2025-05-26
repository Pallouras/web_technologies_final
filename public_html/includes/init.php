<?php
// Ορισμός σωστής ζώνης ώρας για όλη την εφαρμογή (πρέπει να είναι πολύ νωρίς)
date_default_timezone_set('Europe/Athens');

// Ξεκινά session με ασφάλεια
if (session_status() === PHP_SESSION_NONE) {
    session_start();

}

// Σύνδεση με βάση δεδομένων
$databasePath = __DIR__ . '/db.php';
if (file_exists($databasePath)) {
    require_once($databasePath);
} else {
    die("⚠️ Database connection file not found.");
}

// Συναρτήσεις αυθεντικοποίησης
$authPath = __DIR__ . '/auth.php';
if (file_exists($authPath)) {
    require_once($authPath);
} else {
    die("⚠️ Authentication functions file not found.");
}
?>
