<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';


if (!isset($_SESSION['user'])) {
  header("Location: login.html");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $user_id = $_SESSION['user']['id'];

  $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
  $stmt->execute([$first_name, $last_name, $user_id]);

  // Ενημερώνουμε και τα δεδομένα στο session
  $_SESSION['user']['first_name'] = $first_name;
  $_SESSION['user']['last_name'] = $last_name;

  header("Location: profile.php?msg=updated");
  exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
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
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form action="edit_profile.php" method="post" class="card p-4 shadow-sm">
          <h2 class="mb-4 text-center">Edit Profile</h2>
          <div class="mb-3">
            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
          </div>
          <div class="mb-3">
            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Save Changes</button>

          <a href="index.php" class="btn btn-secondary w-100 mt-2">Return to Homepage</a>
        
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