<?php
session_start();
require '../config/db.php'; // Adjust path if needed

// Ensure only admin can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.html");
    exit();
}

// Fetch all users
$stmt = $pdo->query("SELECT id, full_name, email, wallet_balance FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #0f172a;
      color: #ffffff;
      padding: 30px;
    }
    h1 {
      text-align: center;
      color: #38bdf8;
    }
    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
      background-color: #1e293b;
    }
    th, td {
      padding: 12px;
      border: 1px solid #334155;
      text-align: left;
    }
    th {
      background-color: #334155;
      color: #facc15;
    }
    td {
      color: #e2e8f0;
    }
    .delete-btn {
      background-color: #dc3545;
      padding: 6px 10px;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .delete-btn:hover {
      background-color: #bb2d3b;
    }
    a.back {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #38bdf8;
      color: #0f172a;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h1>Manage Users</h1>

  <table>
    <thead>
      <tr>
        <th>Full Name</th>
        <th>Email</th>
        <th>Wallet Balance (₦)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['full_name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= number_format($user['wallet_balance'], 2) ?></td>
        <td>
          <form action="delete-user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a class="back" href="admin-dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</body>
</html>
