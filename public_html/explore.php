<?php
require_once(__DIR__ . '/includes/init.php');

// Παίρνουμε τις δημόσιες λίστες και τα usernames
$search = $_GET['search'] ?? '';
$recent = isset($_GET['recent']) && $_GET['recent'] === '1';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$params = [];
$search_sql = '';

if (!empty($search)) {
  $search_sql .= " AND p.title LIKE ? ";
  $params[] = '%' . $search . '%';
}

if ($recent) {
  $search_sql .= " AND p.created_at >= ? ";
  $params[] = date('Y-m-d', strtotime('-7 days'));
}

$sql = "
  SELECT p.*, u.username 
  FROM playlists p 
  JOIN users u ON p.user_id = u.id 
  WHERE p.is_public = 1 $search_sql
  ORDER BY p.created_at DESC
  LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Για κάθε λίστα παίρνουμε video data
foreach ($playlists as &$playlist) {
    $stmt = $pdo->prepare("SELECT video_title, youtube_id FROM playlist_items WHERE playlist_id = ? LIMIT 3");
    $stmt->execute([$playlist['id']]);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $playlist['video_count'] = count($videos);
    $playlist['videos'] = $videos;
    $playlist['thumbnail'] = $videos[0]['youtube_id'] ?? null;
  }
  unset($playlist);

?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Ανακάλυψε Λίστες</title>
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
  <h2 class="mb-4">🌍 Δημόσιες Λίστες Χρηστών</h2>

  <form method="GET" class="mb-4 row g-2">
  <div class="col-md-4">
    <input type="text" name="search" class="form-control" placeholder="Αναζήτηση τίτλου..." value="<?php echo htmlspecialchars($search); ?>">
  </div>
  <div class="col-md-2">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="recent" value="1" id="recent" <?php if ($recent) echo 'checked'; ?>>
      <label class="form-check-label" for="recent">Τελευταίες 7 ημέρες</label>
    </div>
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary" type="submit">Φιλτράρισμα</button>
  </div>
</form>

<nav>
  <ul class="pagination justify-content-center mt-4">
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">« Προηγ.</a></li>
    <?php endif; ?>
    <li class="page-item active"><span class="page-link"><?php echo $page; ?></span></li>
    <?php if (count($playlists) === $limit): ?>
      <li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Επόμ. »</a></li>
    <?php endif; ?>
  </ul>
</nav>

  <?php if (empty($playlists)): ?>
    <p>Δεν υπάρχουν δημόσιες λίστες προς το παρόν.</p>
  <?php else: ?>
    <table class="table table-hover">
      <thead class="table-dark">
        <tr>
          <th>Τίτλος</th>
          <th>Δημιουργός</th>
          <th>Ημερομηνία</th>
          <th>Προβολή</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($playlists as $pl): ?>
          <tr>
          <td>
    <strong><?php echo htmlspecialchars($pl['title']); ?></strong><br>
    <small>🎬 Βίντεο: <?php echo $pl['video_count']; ?></small>
    <?php if (!empty($pl['videos'])): ?>
      <ul class="mb-0">
        <?php foreach ($pl['videos'] as $v): ?>
          <li style="font-size: 0.85em;"><?php echo htmlspecialchars($v['video_title']); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </td>
  <td><?php echo htmlspecialchars($pl['username']); ?></td>
  <td><?php echo date('d/m/Y', strtotime($pl['created_at'])); ?></td>
  <td>
    <?php if ($pl['thumbnail']): ?>
      <img src="https://img.youtube.com/vi/<?php echo $pl['thumbnail']; ?>/mqdefault.jpg" width="120" class="mb-2"><br>
    <?php endif; ?>
    <a href="playlist.php?id=<?php echo $pl['id']; ?>" class="btn btn-outline-primary btn-sm">Προβολή</a>
  </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
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
