<?php
if (!headers_sent()) {
    ob_start(); // Buffer για να μη σταλούν headers πρόωρα

    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
}
?>



<nav class="navbar navbar-expand-lg bg-dark navbar-dark" id="mainNavbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">FactorStream</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">   
      <?php if (isset($_SESSION['user'])): ?>
          <?php if ($_SESSION['user']['username'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="/admin_panel.php">🛠 Admin Panel</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="/profile.php">Προφίλ</a></li>
          <li class="nav-item"><a class="nav-link" href="/create_playlist.php">➕ Δημιουργία Λίστας</a></li>
          <li class="nav-item"><a class="nav-link" href="/view_playlists.php">🎵 Οι Λίστες Μου</a></li>
          <li class="nav-item"><a class="nav-link" href="/add_video.php">🎥 Προσθήκη Βίντεο</a></li>
          <li class="nav-item"><a class="nav-link" href="/explore.php">🌍 Ανακάλυψε</a></li>
          <a class="nav-link" href="open_data.php">Open Data</a>
          <li class="nav-item">
            <button class="btn btn-outline-secondary nav-link" onclick="toggleTheme()">Theme</button>
          </li>
          <li class="nav-item"><a class="nav-link" href="/logout.php">Αποσύνδεση</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/register.html">Εγγραφή</a></li>
          <li class="nav-item"><a class="nav-link" href="/login.html">Σύνδεση</a></li>
          <button class="btn btn-outline-secondary nav-link" onclick="toggleTheme()">Theme</button>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<script>
function toggleTheme() {
  const currentTheme = document.body.classList.contains("light") ? "light" : "dark";
  const newTheme = currentTheme === "light" ? "dark" : "light";
  document.body.className = newTheme;
  document.cookie = "theme=" + newTheme + "; path=/";

  const navbar = document.getElementById("mainNavbar");
  if (navbar) {
    navbar.classList.remove("bg-light", "bg-dark", "navbar-light", "navbar-dark");
    if (newTheme === "dark") {
      navbar.classList.add("bg-dark", "navbar-dark");
    } else {
      navbar.classList.add("bg-light", "navbar-light");
    }
  }
}

window.onload = function () {
  const cookieTheme = document.cookie.split("; ").find(row => row.startsWith("theme="));
  if (cookieTheme) {
    const savedTheme = cookieTheme.split("=")[1];
    document.body.className = savedTheme;

    const navbar = document.getElementById("mainNavbar");
    if (navbar) {
      navbar.classList.remove("bg-light", "bg-dark", "navbar-light", "navbar-dark");
      if (savedTheme === "dark") {
        navbar.classList.add("bg-dark", "navbar-dark");
      } else {
        navbar.classList.add("bg-light", "navbar-light");
      }
    }
  }
};



</script>


<script>
  // ΓΙΑ ΤΟ RESPONSIVE NAVIGATION MENU
  document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("menu-toggle");
    const navLinks = document.getElementById("nav-links");

    if (toggleButton && navLinks) {
      toggleButton.addEventListener("click", function () {
        navLinks.classList.toggle("active");
      });
    }
  });

  // ΓΙΑ ACCORDION (αν το χρησιμοποιείς για άλλες πληροφορίες)
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