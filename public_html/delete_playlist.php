<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.html");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['id'])) {
  $playlist_id = $_GET['id'];
  $user_id = $_SESSION['user']['id'];

  // Διαγράφει μόνο αν ανήκει στον χρήστη
  $stmt = $pdo->prepare("DELETE FROM playlists WHERE id = ? AND user_id = ?");
  $stmt->execute([$playlist_id, $user_id]);

  header("Location: view_playlists.php?msg=deleted");
  exit;
}
?>
