<?php
session_start();
require 'config/db.php'; // Connect to your database

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get amount and email from the form
$amount = $_POST['amount'];
$email = $_POST['email'];

// Sanitize
$amount = intval($amount) * 100; // Convert Naira to Kobo
$reference = uniqid("IBT_"); // Unique transaction reference

// Replace with your actual secret key
$secret_key = "sk_live_de38568554503860539b8f746c6f9aa16f0363a3";

// Paystack initialization
$fields = [
    'email' => $email,
    'amount' => $amount,
    'reference' => $reference,
    'callback_url' => 'http://localhost/IBDATA/fund-wallet.php' // <-- Change to your live domain if deployed
];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => http_build_query($fields),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $secret_key",
        "Cache-Control: no-cache"
    ]
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    die("cURL Error: " . $err);
}

$result = json_decode($response, true);

if ($result['status']) {
    // Redirect to Paystack payment page
    header("Location: " . $result['data']['authorization_url']);
    exit();
} else {
    die("Payment initialization failed: " . $result['message']);
}
?>
