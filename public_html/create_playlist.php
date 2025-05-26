<?php
ob_start(); // Αυτό μπαίνει πριν από ΟΠΟΙΟΔΗΠΟΤΕ output
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';
require_once 'includes/navbar.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.html");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST['title'];
  $is_public = isset($_POST['is_public']) ? 1 : 0;
  $user_id = $_SESSION['user']['id'];

  $stmt = $pdo->prepare("INSERT INTO playlists (user_id, title, is_public) VALUES (?, ?, ?)");
  $stmt->execute([$user_id, $title, $is_public]);

  header("Location: view_playlists.php?msg=created");
  exit;
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Δημιουργία Λίστας</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body.light { background-color: #fff; color: #000; }
    body.dark { background-color: #121212; color: #f0f0f0; }
    .accordion { cursor: pointer; padding: 1em; border: none; outline: none; width: 100%; text-align: left; background: #eee; }
    .panel { display: none; padding: 0 1em; }
    .active + .panel { display: block; }
    .theme-toggle { float: right; cursor: pointer; margin-top: -2em; }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h2 class="mb-0">Δημιουργία νέας λίστας</h2>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label for="title" class="form-label">Τίτλος Λίστας</label>
          <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_public" id="is_public" checked>
          <label class="form-check-label" for="is_public">
            Δημόσια λίστα
          </label>
        </div>
        <button type="submit" class="btn btn-success">Αποθήκευση</button>
        <a href="index.php" class="btn btn-secondary">Επιστροφή στην αρχική</a>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Toggle Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const themeToggle = document.getElementById("theme-toggle");
    const body = document.body;

    const currentTheme = localStorage.getItem("theme");
    if (currentTheme === "dark") {
      body.classList.add("dark-theme");
    }

    if (themeToggle) {
      themeToggle.addEventListener("click", function () {
        body.classList.toggle("dark-theme");
        const theme = body.classList.contains("dark-theme") ? "dark" : "light";
        localStorage.setItem("theme", theme);
      });
    }
  });
</script>

</body>
</html>
