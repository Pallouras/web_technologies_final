<?php
require_once(__DIR__ . '/includes/init.php');
include 'includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>FactorStream - Κεντρική Σελίδα</title>
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

<body class="light">


<div class="container mt-5">
  <h1 class="text-center">
    Καλώς ήρθατε στο FactorStream
  </h1>

  <div class="text-center mt-3">
    <?php if (isset($_SESSION['user'])): ?>
      <p class="lead">Γεια σου, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>
    <?php else: ?>
      <p class="lead">Κάνε σύνδεση ή εγγραφή για να ξεκινήσεις.</p>
    <?php endif; ?>
  </div>

  <button class="accordion" onclick="toggleAccordion(this)">🎯 Σκοπός Ιστοτόπου</button>
  <div class="panel">
    <p>Το FactorStream είναι ένας δυναμικός ιστότοπος που επιτρέπει στους χρήστες να δημιουργούν λίστες αναπαραγωγής από βίντεο του YouTube, να τις διαμοιράζονται και να αλληλεπιδρούν με άλλους χρήστες.</p>
  </div>

  <button class="accordion" onclick="toggleAccordion(this)">👤 Πώς να εγγραφείτε</button>
  <div class="panel">
    <p>Μεταβείτε στη σελίδα εγγραφής και συμπληρώστε τα στοιχεία σας. Απαιτούνται όνομα, επώνυμο, μοναδικό username και email, καθώς και ασφαλής κωδικός πρόσβασης.</p>
  </div>

  <button class="accordion" onclick="toggleAccordion(this)">📌 Πώς μπορώ να εγγραφώ;</button>
  <div class="panel">
    <p>Πήγαινε στη σελίδα εγγραφής, συμπλήρωσε τα υποχρεωτικά πεδία και πάτησε "Εγγραφή". Θα δημιουργηθεί ο λογαριασμός σου και μπορείς να συνδεθείς αμέσως.</p>
  </div>

  <button class="accordion" onclick="toggleAccordion(this)">🎬 Πώς δημιουργώ λίστα περιεχομένου;</button>
  <div class="panel">
    <p>Αφού συνδεθείς, μπορείς να δημιουργήσεις λίστες και να αναζητήσεις βίντεο από το YouTube, προσθέτοντάς τα στη λίστα σου.</p>
  </div>

  <button class="accordion" onclick="toggleAccordion(this)">👥 Πώς μπορώ να ακολουθήσω άλλους χρήστες;</button>
  <div class="panel">
    <p>Μπορείς να επισκεφτείς τα δημόσια προφίλ άλλων χρηστών και να πατήσεις "Ακολούθησε". Οι δημόσιες λίστες τους θα εμφανίζονται και στο προφίλ σου.</p>
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


<!--Info accordion -->
<script>
  function toggleAccordion(button) {
    button.classList.toggle("active");
    const panel = button.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  }
</script>


</body>
</html>
