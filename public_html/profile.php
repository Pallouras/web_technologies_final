<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';
include 'includes/navbar.php';


if (!isset($_SESSION["user"])) {
    header("Location: login.html");
    exit;
}
$user = $_SESSION["user"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
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
  <div class="container mt-5">
    <div class="card p-4 shadow-sm">
      <h2 class="mb-4">Your Profile</h2>
      <p><strong>First Name:</strong> <?php echo htmlspecialchars($user["first_name"]); ?></p>
      <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user["last_name"]); ?></p>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($user["username"]); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user["email"]); ?></p>

      <a href="edit_profile.php" class="btn btn-primary mt-3">Edit Profile</a>
      <a href="delete_account.php" class="btn btn-danger mt-3 ms-2">Delete Account</a>
      <a href="logout.php" class="btn btn-secondary mt-3 ms-2">Logout</a>
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