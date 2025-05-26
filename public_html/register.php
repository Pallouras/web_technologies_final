<?php

require_once(__DIR__ . '/includes/init.php');

// Έλεγχος αν η φόρμα έχει υποβληθεί μέσω POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $success = false; // αρχικοποίηση

    // Έλεγχος ισχυρού κωδικού
    if (
        strlen($password) < 6 ||
        !preg_match('/\d/', $password) ||
        !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)
    ) {
        $error = "Ο κωδικός πρέπει να έχει τουλάχιστον 6 χαρακτήρες, έναν αριθμό και ένα ειδικό σύμβολο.";
    } else {
        $success = registerUser($first, $last, $username, $email, $password, $pdo);

        if ($success) {
            header("Location: login.html");
            exit;
        } else {
            $error = "Registration failed. Try a different username or email.";
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register Result</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center">
  <div class="container mt-5">
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
      <a href="register.html" class="btn btn-secondary mt-3">Back to Register</a>
    <?php endif; ?>
  </div>
</body>
</html>
