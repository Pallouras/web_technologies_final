<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';


if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user_id = $_SESSION['user']['id'];

  // Διαγράφουμε τον χρήστη από τη βάση
  $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
  $stmt->execute([$user_id]);

  // Καθαρίζουμε το session
  session_unset();
  session_destroy();

  // Πάμε πίσω στην αρχική σελίδα
  header("Location: index.php");
  exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Account</title>
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
<body class="bg-light">
  <div class="container mt-5 text-center">
    <h2 class="mb-4">Are you sure you want to delete your account?</h2>
    <form action="delete_account.php" method="post" class="d-inline">
      <button type="submit" class="btn btn-danger">Yes, Delete</button>
    </form>
    <a href="profile.php" class="btn btn-secondary ms-2">Cancel</a>
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
