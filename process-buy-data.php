<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $network = $_POST['network'];
    $plan = $_POST['plan'];
    $phone = $_POST['phone'];

    // Validate inputs
    if (empty($network) || empty($plan) || empty($phone)) {
        echo "<h3 style='color:red;'>All fields are required!</h3>";
        exit();
    }

    // Simulate processing (you'll later replace this with VTU API integration)
    echo "<h2 style='color:green;'>✅ Data Purchase Request Received</h2>";
    echo "<p><strong>Network:</strong> " . htmlspecialchars($network) . "</p>";
    echo "<p><strong>Data Plan:</strong> " . htmlspecialchars($plan) . "</p>";
    echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($phone) . "</p>";

    // TODO: Deduct amount from wallet & call VTU.ng API here
    // TODO: Save transaction in database

    echo "<br><a href='dashboard.php'>⬅ Back to Dashboard</a>";
} else {
    header("Location: buy-data.php");
    exit();
}
?>
