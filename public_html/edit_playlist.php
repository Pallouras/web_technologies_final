<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Δεν καθορίστηκε λίστα.";
    exit;
}

$playlist_id = $_GET['id'];
$user_id = $_SESSION['user']['id'];

// Φέρνουμε τη λίστα
$stmt = $pdo->prepare("SELECT * FROM playlists WHERE id = ? AND user_id = ?");
$stmt->execute([$playlist_id, $user_id]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$playlist) {
    echo "Δεν έχετε πρόσβαση σε αυτή τη λίστα.";
    exit;
}

// Αν έγινε υποβολή
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE playlists SET title = ?, is_public = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $is_public, $playlist_id, $user_id]);

    header("Location: view_playlists.php?msg=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Επεξεργασία Λίστας</title>
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
<?php include 'includes/navbar.php'; ?>
<div class="container mt-5">
  <h2 class="mb-4">Επεξεργασία Λίστας</h2>

  <form method="POST">
    <div class="mb-3">
      <label for="title" class="form-label">Τίτλος Λίστας</label>
      <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($playlist['title']); ?>" required>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="is_public" id="is_public" <?php echo $playlist['is_public'] ? 'checked' : ''; ?>>
      <label class="form-check-label" for="is_public">
        Δημόσια λίστα
      </label>
    </div>

    <button type="submit" class="btn btn-success">Αποθήκευση Αλλαγών</button>
    <a href="view_playlists.php" class="btn btn-secondary">Ακύρωση</a>
  </form>
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
