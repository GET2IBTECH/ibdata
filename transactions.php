<?php
session_start();
require 'config/db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transaction History - IBT SUB</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #0f172a;
      color: #ffffff;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #1e293b;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #38bdf8;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      text-align: left;
      padding: 14px;
      border-bottom: 1px solid #334155;
    }
    th {
      background-color: #0ea5e9;
      color: #0f172a;
    }
    tr:hover {
      background-color: #334155;
    }
    .status-success {
      color: #22c55e;
      font-weight: bold;
    }
    .status-failed {
      color: #ef4444;
      font-weight: bold;
    }
    a.back {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 16px;
      background: #38bdf8;
      color: #0f172a;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }
    a.back:hover {
      background: #0ea5e9;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Your Transaction History</h2>
    <?php if (count($transactions) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Type</th>
            <th>Phone</th>
            <th>Amount (₦)</th>
            <th>Status</th>
            <th>Date</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $txn): ?>
            <tr>
              <td><?= htmlspecialchars($txn['type']) ?></td>
              <td><?= htmlspecialchars($txn['phone']) ?></td>
              <td><?= number_format($txn['amount'], 2) ?></td>
              <td class="<?= $txn['status'] == 'success' ? 'status-success' : 'status-failed' ?>">
                <?= ucfirst($txn['status']) ?>
              </td>
              <td><?= date("d M Y - h:i A", strtotime($txn['created_at'])) ?></td>
              <td><?= htmlspecialchars($txn['message']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No transactions found.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
  </div>
</body>
</html>
