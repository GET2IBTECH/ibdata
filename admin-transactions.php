<?php
session_start();
require '../config/db.php'; // adjust if your config is in a different folder

// Ensure only admin can access this page
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.html");
    exit();
}

// Fetch all transactions from the database
$stmt = $pdo->prepare("SELECT * FROM transactions ORDER BY created_at DESC");
$stmt->execute();
$transactions = $stmt->fetchAll();
$total_count = count($transactions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Transactions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f1f5f9;
      margin: 0;
      padding: 0;
      color: #1e293b;
    }

    header {
      background-color: #0f172a;
      color: white;
      padding: 25px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
    }

    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .summary {
      font-size: 20px;
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 20px;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 14px 18px;
      text-align: left;
    }

    th {
      background-color: #1e293b;
      color: #fff;
      text-transform: uppercase;
      font-size: 14px;
    }

    tr:nth-child(even) {
      background-color: #f8fafc;
    }

    tr:hover {
      background-color: #e2e8f0;
      transition: 0.3s;
    }

    .status-success {
      color: green;
      font-weight: bold;
    }

    .status-failed {
      color: red;
      font-weight: bold;
    }

    @media screen and (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      th {
        display: none;
      }

      td {
        position: relative;
        padding-left: 50%;
        border-bottom: 1px solid #ddd;
      }

      td::before {
        position: absolute;
        top: 14px;
        left: 14px;
        width: 45%;
        white-space: nowrap;
        font-weight: bold;
        color: #64748b;
      }

      td:nth-of-type(1)::before { content: "ID"; }
      td:nth-of-type(2)::before { content: "User ID"; }
      td:nth-of-type(3)::before { content: "Type"; }
      td:nth-of-type(4)::before { content: "Amount"; }
      td:nth-of-type(5)::before { content: "Phone"; }
      td:nth-of-type(6)::before { content: "Status"; }
      td:nth-of-type(7)::before { content: "Date"; }
    }
  </style>
</head>
<body>

<header>
  Admin Panel – Transactions
</header>

<div class="container">
  <div class="summary">
    📄 Total Transactions: <?= $total_count ?>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Type</th>
        <th>Amount (₦)</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($transactions as $tx): ?>
        <tr>
          <td><?= htmlspecialchars($tx['id']) ?></td>
          <td><?= htmlspecialchars($tx['user_id']) ?></td>
          <td><?= htmlspecialchars(ucfirst($tx['type'])) ?></td>
          <td>₦<?= number_format($tx['amount'], 2) ?></td>
          <td><?= htmlspecialchars($tx['phone']) ?></td>
          <td class="<?= $tx['status'] === 'success' ? 'status-success' : 'status-failed' ?>">
            <?= htmlspecialchars(ucfirst($tx['status'])) ?>
          </td>
          <td><?= htmlspecialchars($tx['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
