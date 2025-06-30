<?php
require 'config/db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if user already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        echo "<script>alert('User already exists with this email!'); window.location.href='register.html';</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, wallet_balance) VALUES (?, ?, ?, 0.00)");
    $inserted = $stmt->execute([$full_name, $email, $hashed_password]);

    if ($inserted) {
        echo "<script>alert('Registration successful! You can now login.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Registration failed. Try again later.'); window.location.href='register.html';</script>";
    }
} else {
    // Invalid access
    header("Location: register.html");
    exit();
}
?>
