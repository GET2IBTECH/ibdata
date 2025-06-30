<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: admin-users.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
