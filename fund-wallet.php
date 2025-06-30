<?php
session_start();
require 'config/db.php'; // Make sure this file connects to your database

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get the Paystack reference from URL
if (!isset($_GET['reference'])) {
    die("No transaction reference found.");
}

$reference = $_GET['reference'];

// VERIFY TRANSACTION VIA PAYSTACK
$secret_key = "sk_live_de38568554503860539b8f746c6f9aa16f0363a3"; // Replace with your secret key

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $secret_key",
        "Cache-Control: no-cache"
    ],
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    die("cURL Error: " . $err);
}

$result = json_decode($response, true);

// If successful
if ($result['data']['status'] === 'success') {
    $user_id = $_SESSION['user_id'];
    $amount = $result['data']['amount'] / 100; // Convert Kobo to Naira
    $email = $result['data']['customer']['email'];

    // Update wallet balance
    $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?");
    $stmt->execute([$amount, $user_id]);

    // Insert transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, phone, status, message) VALUES (?, 'funding', ?, ?, 'success', 'Wallet funded via Paystack')");
    $stmt->execute([$user_id, $amount, $email]);

    echo "
    <div style='font-family:sans-serif;padding:40px;text-align:center;background:#0f172a;color:white;'>
        <h2 style='color:#38bdf8;'>✅ Wallet Funded Successfully!</h2>
        <p>You have funded ₦{$amount} into your wallet.</p>
        <a href='dashboard.php' style='display:inline-block;margin-top:20px;background:#38bdf8;color:#0f172a;padding:10px 20px;border-radius:8px;text-decoration:none;'>Go to Dashboard</a>
    </div>";
} else {
    echo "<script>alert('Transaction failed or not found.'); window.location='fund-wallet.html';</script>";
}
?>
