<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<?php
session_start();
require 'config/db.php'; // Connect to your database

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// ✅ Get form values
$user_id = $_SESSION['user_id'];
$phone = $_POST['phone'];
$network = $_POST['network'];
$amount = $_POST['amount']; // Amount user is charged
$plan_id = $_POST['plan_id']; // Legit Data plan ID

// ✅ Fetch user wallet balance
$stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$wallet_balance = $user['wallet_balance'];

// ✅ Check wallet balance
if ($wallet_balance < $amount) {
    echo "<script>alert('❌ Insufficient wallet balance!'); window.history.back();</script>";
    exit();
}

// ✅ Send request to Legit Data API
$api_key = "REPLACE_WITH_YOUR_LEGITDATA_API_KEY"; // 🔁 Replace with your real API key

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://legitdata.com/api/data',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'network' => $network,
        'mobile_number' => $phone,
        'plan' => $plan_id,
        'api_key' => $api_key
    ]),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error: $err";
    exit();
}

$result = json_decode($response, true);

// ✅ If purchase is successful
if (isset($result['success']) && $result['success'] == true) {
    // Deduct from wallet
    $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?");
    $stmt->execute([$amount, $user_id]);

    // Log transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, phone, status, message) VALUES (?, 'data', ?, ?, 'success', ?)");
    $stmt->execute([$user_id, $amount, $phone, $result['message']]);

    // ✅ Show receipt and success message
    echo "
    <div style='background:#0f172a;color:white;font-family:sans-serif;padding:30px;margin:50px auto;width:90%;max-width:420px;border-radius:12px;text-align:center;box-shadow:0 0 15px rgba(0,0,0,0.4);'>
        <h2 style='color:#38bdf8;margin-bottom:15px;'>✅ Data Purchase Successful</h2>
        <p><strong>Network:</strong> $network</p>
        <p><strong>Phone Number:</strong> $phone</p>
        <p><strong>Amount:</strong> ₦$amount</p>
        <p><strong>Status:</strong> Success</p>
        <p style='margin-top:15px;color:#facc15;'>Thank you for using IBT SUB</p>
        <a href='dashboard.php' style='margin-top:25px;display:inline-block;background:#38bdf8;padding:10px 20px;border-radius:8px;color:#0f172a;font-weight:bold;text-decoration:none;'>⬅ Back to Dashboard</a>
    </div>
    ";
} else {
    // ❌ Failed
    $error_msg = $result['message'] ?? 'Unknown error';
    echo "<script>alert('Failed to purchase data: $error_msg'); window.history.back();</script>";
}
?>
