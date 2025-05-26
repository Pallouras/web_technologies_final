<?php
// Χειρίζεται login και εγγραφή χρηστών

function authenticateUser($username, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        return $user; // Αν το password ταιριάζει, επιστρέφουμε τα στοιχεία χρήστη
    }

    return false;
}

function registerUser($first, $last, $username, $email, $password, $pdo) {
    // Έλεγχος αν υπάρχει ήδη χρήστης
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        return false;
    }

    // Κρυπτογράφηση κωδικού
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Καταχώρηση νέου χρήστη
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, password) 
                           VALUES (:first, :last, :username, :email, :password)");
    return $stmt->execute([
        'first' => $first,
        'last' => $last,
        'username' => $username,
        'email' => $email,
        'password' => $hashed_password
    ]);
}
?>
