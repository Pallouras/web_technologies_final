<?php
require_once "includes/init.php";
require_once "includes/navbar.php";

$stmt = $pdo->query("
  SELECT p.id, p.title, p.created_at, u.username
  FROM playlists p
  JOIN users u ON p.user_id = u.id
  WHERE p.is_public = 1
  ORDER BY p.created_at DESC
");

$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<div class="container mt-4">
  <h1 class="mb-4">🎁 Ανοιχτά Δεδομένα - Δημόσιες Λίστες</h1>

  <div class="mb-4">
    <a href="export_yaml.php" class="btn btn-outline-success" target="_blank">⬇️ Λήψη όλων (JSON)</a>
  </div>

  <?php foreach ($playlists as $pl): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($pl['title']); ?></h5>
        <p class="card-text">
          Δημιουργήθηκε: <?php echo $pl['created_at']; ?><br>
          Από: <strong><?php echo htmlspecialchars($pl['username']); ?></strong>
        </p>
        <?php
          $stmt2 = $pdo->prepare("
            SELECT video_title FROM playlist_items
            WHERE playlist_id = ?
            ORDER BY created_at ASC
            LIMIT 3
          ");
          $stmt2->execute([$pl['id']]);
          $videos = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        ?>
        <?php if ($videos): ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($videos as $v): ?>
              <li class="list-group-item"><?php echo htmlspecialchars($v); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-muted">(Δεν υπάρχουν βίντεο)</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
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