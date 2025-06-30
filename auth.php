<?php
session_start();
require 'config/db.php';

// REGISTER LOGIC
if (isset($_POST['register'])) {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $state = $_POST['state'];
    $referral = $_POST['referral_source'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, state, referral_source, password) VALUES (?, ?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$name, $email, $phone, $state, $referral, $password]);
        echo "✅ Registration successful! <a href='login.html'>Login here</a>";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

// LOGIN LOGIC
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: dashboard.html");
    } else {
        echo "❌ Invalid login credentials.";
    }
}
?>
