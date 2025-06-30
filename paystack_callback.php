<?php
require 'config/db.php'; // Connect to your database

if (!isset($_GET['reference'])) {
    die("No reference provided.");
}

$reference = $_GET['reference'];

// ✅ Your Paystack Secret Key again
$secret_key = "sk_live_de38568554503860539b8f746c6f9aa16f0363a3";

// Verify transaction
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . $reference);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $secret_key",
    "Content-Type: application/json",
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result['status'] && $result['data']['status'] === 'success') {
    $email = $result['data']['customer']['email'];
    $amount_paid = $result['data']['amount'] / 100;

    // ✅ Update user's wallet in DB
    $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE email = ?");
    $stmt->execute([$amount_paid, $email]);

    echo "✅ Payment successful. ₦$amount_paid added to your wallet.";
} else {
    echo "❌ Payment failed or not verified.";
}
?>
