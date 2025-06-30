<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE name = ?");
        $stmt->execute([$value, $key]);
    }
    $msg = "Settings updated successfully.";
}

// Fetch settings
$stmt = $pdo->query("SELECT * FROM settings");
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Settings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background: #f1f5f9;
      font-family: 'Segoe UI', sans-serif;
      padding: 30px;
    }
    .container {
      background: #fff;
      max-width: 600px;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #0f172a;
    }
    label {
      display: block;
      margin-top: 20px;
      color: #1e293b;
      font-weight: bold;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      margin-top: 25px;
      width: 100%;
      background: #0f172a;
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    .msg {
      background: #d1fae5;
      padding: 10px;
      color: #065f46;
      margin-top: 10px;
      border-radius: 6px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Admin Settings</h2>

  <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

  <form method="POST">
    <label for="site_name">Site Name</label>
    <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>">

    <label for="support_email">Support Email</label>
    <input type="email" name="support_email" value="<?= htmlspecialchars($settings['support_email'] ?? '') ?>">

    <label for="legitdata_api_key">LegitData API Key</label>
    <input type="text" name="legitdata_api_key" value="<?= htmlspecialchars($settings['legitdata_api_key'] ?? '') ?>">

    <label for="paystack_secret_key">Paystack Secret Key</label>
    <input type="text" name="paystack_secret_key" value="<?= htmlspecialchars($settings['paystack_secret_key'] ?? '') ?>">

    <button type="submit">Update Settings</button>
  </form>
</div>

</body>
</html>
