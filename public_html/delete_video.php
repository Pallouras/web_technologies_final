<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Μη έγκυρο video ID.");
}

$video_id = $_GET['id'];

// Ελέγχουμε αν ο χρήστης έχει δικαίωμα
$stmt = $pdo->prepare("SELECT p.user_id, pi.playlist_id FROM playlist_items pi
                       JOIN playlists p ON pi.playlist_id = p.id
                       WHERE pi.id = ?");
$stmt->execute([$video_id]);
$video = $stmt->fetch();

if (!$video || $_SESSION['user']['id'] !== $video['user_id']) {
  die("Δεν έχετε άδεια για αυτή την ενέργεια.");
}

// Διαγραφή
$stmt = $pdo->prepare("DELETE FROM playlist_items WHERE id = ?");
$stmt->execute([$video_id]);

header("Location: playlist.php?id=" . $video['playlist_id'] . "&msg=deleted");
exit;
