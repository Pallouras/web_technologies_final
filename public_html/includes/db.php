<?php
date_default_timezone_set('Europe/Athens');


try {
    $pdo = new PDO("mysql:host=db;dbname=testdb;charset=utf8", "root", "rootpassword");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: fetch results as associative arrays by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>