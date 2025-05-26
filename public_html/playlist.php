<?php
require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';

if (!isset($_GET['id'])) {
  echo "Λείπει το ID της λίστας.";
  exit;
}

$playlist_id = $_GET['id'];

// Φέρνουμε τη λίστα
$stmt = $pdo->prepare("SELECT * FROM playlists WHERE id = ?");
$stmt->execute([$playlist_id]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$playlist) {
  echo "Η λίστα δεν βρέθηκε.";
  exit;
}

// Έλεγχος ιδιοκτησίας ή δημοσιότητας
$can_view = $playlist['is_public'];
if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $playlist['user_id']) {
  $can_view = true;
}

if (!$can_view) {
  echo "Δεν έχετε πρόσβαση σε αυτή τη λίστα.";
  exit;
}

// Παίρνουμε τα videos
$stmt = $pdo->prepare("SELECT * FROM playlist_items WHERE playlist_id = ?");
$stmt->execute([$playlist_id]);
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($playlist['title']); ?> - Playlist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body.light { background-color: #fff; color: #000; }
    body.dark { background-color: #121212; color: #f0f0f0; }
    .accordion { cursor: pointer; padding: 1em; border: none; outline: none; width: 100%; text-align: left; background: #eee; }
    .panel { display: none; padding: 0 1em; }
    .active + .panel { display: block; }
    .theme-toggle { float: right; cursor: pointer; margin-top: -2em; }
    .card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 180px;
              }
  </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container mt-5">
  <h2><?php echo htmlspecialchars($playlist['title']); ?></h2>
  <p><strong>Ιδιωτικότητα:</strong> <?php echo $playlist['is_public'] ? 'Δημόσια' : 'Ιδιωτική'; ?></p>

  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
  <div class="alert alert-success">✅ Το video αφαιρέθηκε από τη λίστα.</div>
  <?php endif; ?>

  <?php if (empty($videos)): ?>
    <p>Δεν υπάρχουν βίντεο σε αυτή τη λίστα.</p>
  <?php else: ?>
    <div class="col-md-6 mb-4 d-flex align-items-stretch">
      <?php foreach ($videos as $video): ?>
        <div class="col-md-6 mb-4">
          <div class="card">
            <div class="ratio ratio-16x9">
              <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($video['youtube_id']); ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($video['video_title']); ?></h5>
                <small class="text-muted">
                  <?php
                      $dt = new DateTime($video['created_at']);
                      echo 'Προστέθηκε: ' . $dt->format('d/m/Y H:i');
                    ?>  
                  </small>

                
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $playlist['user_id']): ?>
                <a href="delete_video.php?id=<?php echo $video['id']; ?>" class="btn btn-danger btn-sm"
                  onclick="return confirm('Σίγουρα θέλεις να αφαιρέσεις αυτό το βίντεο;')">🗑 Αφαίρεση</a>
              <?php endif; ?>
              </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <a href="view_playlists.php" class="btn btn-secondary mt-3">⬅ Επιστροφή στις λίστες</a>
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
