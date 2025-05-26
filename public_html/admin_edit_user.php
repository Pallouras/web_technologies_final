<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/init.php';

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    die("Δεν έχετε πρόσβαση σε αυτή τη σελίδα.");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];

    $update = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    $update->execute([$first_name, $last_name, $email, $id]);

    header("Location: admin_panel.php?msg=updated");
    exit();
}
?>

<head>
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



<?php include 'includes/navbar.php'; ?>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Επεξεργασία Χρήστη</h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Όνομα</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Επώνυμο</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Αποθήκευση</button>
                <a href="admin_panel.php" class="btn btn-secondary">Πίσω</a>
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
