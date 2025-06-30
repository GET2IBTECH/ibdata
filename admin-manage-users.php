<?php
session_start();
require '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.html");
    exit();
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();

// Count users
$totalUsers = count($users);
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
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9fafb;
    }

    header {
      background-color: #0f172a;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container {
      padding: 30px;
      max-width: 1000px;
      margin: auto;
    }

    .summary {
      background: #1e3a8a;
      color: #fff;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 18px;
      font-weight: bold;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 14px 16px;
      text-align: left;
    }

    th {
      background-color: #0f172a;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f1f5f9;
    }

    tr:hover {
      background-color: #e2e8f0;
    }

    .actions a {
      margin-right: 8px;
      text-decoration: none;
      font-weight: bold;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
    }

    .delete {
      background-color: #ef4444;
      color: white;
    }

    .view {
      background-color: #0ea5e9;
      color: white;
    }

    .actions a:hover {
      opacity: 0.85;
    }

    @media screen and (max-width: 600px) {
      th, td {
        font-size: 14px;
        padding: 10px;
      }

      .summary {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Admin Panel - Manage Users</h1>
</header>

<div class="container">
  <div class="summary">
    Total Registered Users: <?php echo $totalUsers; ?>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Wallet (₦)</th>
        <th>Date Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td><?php echo number_format($user['wallet_balance'], 2); ?></td>
        <td><?php echo $user['created_at']; ?></td>
        <td class="actions">
          <a class="view" href="admin-view-user.php?id=<?php echo $user['id']; ?>"><i class="fas fa-eye"></i> View</a>
          <a class="delete" href="admin-delete-user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i> Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
