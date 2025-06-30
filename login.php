<?php
session_start();
require 'config/db.php'; // Connect to DB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Get user info
    $stmt = $pdo->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        // Password is correct, log the user in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='login.html';</script>";
    }
} else {
    header("Location: login.html");
    exit();
}
?>
