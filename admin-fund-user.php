<?php
session_start();
require '../config/db.php'; // Update path if needed

// Admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.html");
    exit();
}

$success = $error = "";
$userData = null;

// Handle fund action
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['fund_user'])) {
    $user_id = $_POST['user_id'];
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $error = "Invalid amount entered.";
    } else {
        // Prepare and execute the update statement
        $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?");
        if ($stmt->execute([$amount, $user_id])) {
            $success = "₦" . number_format($amount, 2) . " added successfully!";
        } else {
            $error = "Failed to fund wallet.";
        }
    }
}

// Handle search
if (isset($_GET['search'])) {
    $query = trim($_GET['search']);
    // Prepare and execute the select statement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? OR email = ?");
    $stmt->execute([$query, $query]);
    $userData = $stmt->fetch(); // Fetch the user data
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fund User Wallet - Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Your CSS styles are embedded here */
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #0f172a;
      color: #f8fafc;
      padding: 40px;
    }
    .container {
      background: #1e293b;
      max-width: 500px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #38bdf8;
    }
    label {
      display: block;
      margin: 10px 0 6px;
      font-weight: 600;
    }
    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      background: #334155;
      color: white;
    }
    input:focus {
      outline: none;
      background: #475569;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background: #0ea5e9;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      color: #0f172a;
      cursor: pointer;
    }
    button:hover {
      background: #0284c7;
    }
    .success, .error {
      margin-top: 15px;
      padding: 12px;
      border-radius: 8px;
      text-align: center;
    }
    .success {
      background-color: #16a34a;
      color: white;
    }
    .error {
      background-color: #dc2626;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2><i class="fas fa-user-plus"></i> Fund User Wallet</h2>

    <form method="GET" action="admin-fund-users.php">
      <label>Search by User ID or Email:</label>
      <input type="text" name="search" placeholder="Enter user ID or email" required>
      <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>

    <?php if ($userData): // This entire block only shows if a user is found ?>
      <hr style="margin: 30px 0; border-color: #334155;">
      <form method="POST">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($userData['id']) ?>">
        <p><strong>User:</strong> <?= htmlspecialchars($userData['full_name']) ?> (<?= $userData['email'] ?>)</p>
        <p><strong>Current Wallet:</strong> ₦<?= number_format($userData['wallet_balance'], 2) ?></p>

        <label>Amount to Fund:</label>
        <input type="number" name="amount" placeholder="Enter amount" required>

        <button type="submit" name="fund_user"><i class="fas fa-wallet"></i> Fund Wallet</button>
      </form>
    <?php endif; ?>

    <?php if ($success): // Displays success message ?>
      <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): // Displays error message ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
  </div>
</body>
</html>