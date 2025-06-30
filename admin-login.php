<?php
session_start();
require '../config/db.php'; // Adjust path if not inside /admin folder

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Example: Only one admin (You can change this to a database system)
    $admin_username = "admin";
    $admin_password = "admin123"; // Change this to something secure

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid admin credentials!'); window.location.href='admin-login.html';</script>";
        exit();
    }
} else {
    header("Location: admin-login.html");
    exit();
}
?>
