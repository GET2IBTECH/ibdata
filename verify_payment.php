<?php
// verify_payment.php

if (!isset($_GET['reference'])) {
    die('No transaction reference supplied');
}

$reference = $_GET['reference'];
$paystackSecretKey = 'sk_live_de38568554503860539b8f746c6f9aa16f0363a3';

// Initialize cURL session
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $paystackSecretKey",
        "Cache-Control: no-cache"
    ],
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Handle errors
if ($err) {
    die("cURL Error: $err");
}

$result = json_decode($response, true);

// Check verification response
if ($result['status'] && $result['data']['status'] === 'success') {
    $amount = $result['data']['amount'] / 100; // Paystack amount is in kobo
    $email = $result['data']['customer']['email'];

    // Connect to database
    require 'config/db.php';

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, wallet_balance FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $newBalance = $user['wallet_balance'] + $amount;

        // Update wallet balance
        $update = $pdo->prepare("UPDATE users SET wallet_balance = ? WHERE id = ?");
        $update->execute([$newBalance, $user['id']]);

        // Optionally insert into transactions table
        $log = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status, reference) VALUES (?, 'credit', ?, 'success', ?)");
        $log->execute([$user['id'], $amount, $reference]);

        echo "Payment verified and wallet updated successfully!";
    } else {
        echo "User not found!";
    }

} else {
    echo "Payment verification failed!";
}
?>
