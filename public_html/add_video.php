<?php

require_once(__DIR__ . '/includes/init.php');
require_once 'includes/auth.php';
require_once __DIR__ . '/vendor/autoload.php'; // Χρήση Google API Client
require_once 'config.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.html");
  exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    
}

if (!isset($_SESSION['id'])) {
    echo "Ο χρήστης δεν είναι συνδεδεμένος.";
    exit;
}


// =======================
// YOUTUBE OAUTH + SEARCH
// =======================

// Από εδώ και πέρα μπορείς να χρησιμοποιήσεις:
// - $client για YouTube OAuth
// - $youtube_api_key για direct API access


$client->setRedirectUri('http://localhost:8000/add_video.php');
$client->addScope('https://www.googleapis.com/auth/youtube.readonly');
$client->setAccessType('offline');
$client->setPrompt('consent');

// Χειρισμός callback από Google (π.χ. ?code=...)
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    header('Location: add_video.php');
    exit;
}

// Έλεγχος πρόσβασης
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    if ($client->isAccessTokenExpired()) {
        unset($_SESSION['access_token']);
        header('Location: ' . $client->createAuthUrl());
        exit;
    }
} else {
    header('Location: ' . $client->createAuthUrl());
    exit;
}

$youtube = new Google_Service_YouTube($client);

// Query αναζήτησης
$q = $_GET['q'] ?? '';
$results = [];

if (!empty($q)) {
    $searchResponse = $youtube->search->listSearch('snippet', [
        'q' => $q,
        'type' => 'video',
        'maxResults' => 10
    ]);
    $results = $searchResponse->getItems();
}

// Χειρισμός αποσύνδεσης
if (isset($_POST['logout'])) {
    unset($_SESSION['access_token']);
    header("Location: add_video.php");
    exit;
}

// Χειρισμός προσθήκης βίντεο σε λίστα
if (isset($_POST['add_video'])) {
    $playlist_id = $_POST['playlist_id'];
    $title = $_POST['video_title'];
    $youtube_id = $_POST['youtube_id'];

    $stmt = $pdo->prepare("INSERT INTO playlist_items (playlist_id, video_title, youtube_id, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$playlist_id, $title, $youtube_id]);

  

    echo "<div class='alert alert-success'>Το βίντεο προστέθηκε με επιτυχία!</div>";
}

// Ανάκτηση λιστών για dropdown
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM playlists WHERE user_id = ?");
$stmt->execute([$user_id]);
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Αναζήτηση Video Στο YouTube</title>
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
<div class="container mt-4">
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Αναζήτηση στο YouTube..." required>
            <button type="submit" class="btn btn-primary">Αναζήτηση</button>
        </div>
    </form>

    <?php if (isset($_SESSION['access_token'])): ?>
        <form method="post" class="mb-4">
            <button type="submit" name="logout" class="btn btn-danger">Αποσύνδεση από Google</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <div class="row">
            <?php foreach ($results as $item):
                $id = $item->getId();
                $snippet = $item->getSnippet();
                if ($id->getKind() !== 'youtube#video') continue;

                $vid = $id->getVideoId();
                $title = $snippet->getTitle();
                $thumbnail = $snippet->getThumbnails()->getMedium()->getUrl();
                $channel = $snippet->getChannelTitle();
                $full_title = $title . " [" . $channel . "]";
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="<?= $thumbnail ?>" class="card-img-top" alt="thumb">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($title) ?></h5>
                            <small class="text-muted">Κανάλι: <?= htmlspecialchars($channel) ?></small>

                            <form method="POST">
                                <div class="mb-2 mt-2">
                                    <select name="playlist_id" class="form-select" required>
                                        <option value="">Επιλέξτε λίστα...</option>
                                        <?php foreach ($playlists as $pl): ?>
                                            <option value="<?= $pl['id'] ?>"><?= htmlspecialchars($pl['title']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="video_title" value="<?= htmlspecialchars($full_title) ?>">
                                <input type="hidden" name="youtube_id" value="<?= $vid ?>">
                                <button type="submit" name="add_video" value="1" class="btn btn-success">Προσθήκη σε λίστα</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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