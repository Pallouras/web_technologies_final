<?php
require_once(__DIR__ . '/includes/init.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = authenticateUser($username, $password, $pdo);

    if ($user) {
      $_SESSION["user"] = $user;
      $_SESSION["username"] = $user["username"];
      $_SESSION['id'] = $user['id'];
      $redirect = $_SESSION["redirect_after_login"] ?? "/profile.php";
      unset($_SESSION["redirect_after_login"]);
      header("Location: $redirect");
      exit;
  } else {
      $error = "Invalid username or password.";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Result</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center">
  <div class="container mt-5">
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
      <a href="login.html" class="btn btn-secondary mt-3">Back to Login</a>
    <?php endif; ?>
  </div>

<script>
  function toggleAccordion(btn) {
    btn.classList.toggle("active");
    var panel = btn.nextElementSibling;
    panel.style.display = panel.style.display === "block" ? "none" : "block";
  }

  function toggleTheme() {
    const body = document.body;
    const newTheme = body.classList.contains('light') ? 'dark' : 'light';
    body.className = newTheme;
    document.cookie = "theme=" + newTheme + "; path=/";
  }

  window.onload = function () {
    const cookieTheme = document.cookie.split('; ').find(row => row.startsWith('theme='));
    if (cookieTheme) {
      document.body.className = cookieTheme.split('=')[1];
    }
  }
</script>

</body>
</html>