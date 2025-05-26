<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT * FROM playlists WHERE user_id = ?");
$stmt->execute([$user_id]);
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Οι Λίστες Μου</title>
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
  <h2 class="mb-4">🎵 Οι Λίστες Μου</h2>

  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-warning">Η λίστα διαγράφηκε με επιτυχία.</div>
  <?php endif; ?>

  <?php if (empty($playlists)): ?>
    <p>Δεν έχετε δημιουργήσει λίστες ακόμη.</p>
  <?php else: ?>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Τίτλος</th>
          <th>Ιδιωτικότητα</th>
          <th>Ημερομηνία</th>
          <th>Ενέργειες</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($playlists as $pl): ?>
          <tr>
            <td><?php echo htmlspecialchars($pl['title']); ?></td>
            <td><?php echo $pl['is_public'] ? 'Δημόσια' : 'Ιδιωτική'; ?></td>
            <td><?php echo date('d/m/Y', strtotime($pl['created_at'])); ?></td>
            <td>
              <a href="playlist.php?id=<?php echo $pl['id']; ?>" class="btn btn-primary btn-sm">Προβολή</a>
              <a href="edit_playlist.php?id=<?php echo $pl['id']; ?>" class="btn btn-warning btn-sm">Επεξεργασία</a>
              <form action="delete_playlist.php?id=<?php echo $pl['id']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('Να διαγραφεί η λίστα;');">
                <button type="submit" class="btn btn-danger btn-sm">Διαγραφή</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <a href="create_playlist.php" class="btn btn-success mt-3">➕ Δημιουργία Νέας Λίστας</a>
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
