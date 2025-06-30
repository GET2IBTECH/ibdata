<?php
session_start();

// Restrict access if admin is not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.html"); // Assuming your login page is still HTML
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - IBT SUB</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #0f172a;
      color: #fff;
    }
    header {
      background-color: #1e3a8a;
      padding: 20px;
      text-align: center;
    }
    header h1 {
      margin: 0;
      color: #38bdf8;
    }
    .container {
      padding: 30px;
    }
    .logout {
      text-align: center;
      margin-top: 20px;
    }
    .logout a {
      padding: 10px 20px;
      background-color: #ef4444;
      color: white;
      border-radius: 6px;
      text-decoration: none;
    }
    .logout a:hover {
      background-color: #dc2626;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background: #1e293b;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      text-align: center;
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card i {
      font-size: 2rem;
      color: #38bdf8;
      margin-bottom: 10px;
    }
    .card h3 {
      margin: 0;
      font-size: 1.2rem;
      color: #facc15;
    }
    .card a {
      color: #60a5fa;
      display: block;
      margin-top: 10px;
      text-decoration: none;
    }
  </style>
</head>
<body>

<header>
  <h1>Admin Dashboard - IBT SUB</h1>
</header>

<div class="container">

  <div class="grid">
    <div class="card">
      <i class="fas fa-users"></i>
      <h3>View Users</h3>
      <a href="admin-manage-users.php">Manage Users</a>
    </div>
    <div class="card">
      <i class="fas fa-money-check-alt"></i>
      <h3>Transactions</h3>
      <a href="admin-transactions.php">View Transactions</a>
    </div>
    <div class="card">
      <i class="fas fa-wallet"></i>
      <h3>Fund User Wallet</h3>
      <a href="admin-fund-users.php">Fund Wallets</a>
    </div>
    <div class="card">
      <i class="fas fa-money-bill-transfer"></i> <h3>Wallet Overview</h3>
      <a href="admin-wallet.php">Track Wallets</a>
    </div>
    <div class="card">
      <i class="fas fa-cogs"></i>
      <h3>Settings</h3>
      <a href="admin-settings.php">Admin Settings</a>
    </div>
  </div>

  <div class="logout">
    <a href="admin-logout.php">Logout</a>
  </div>

</div>

</body>
</html>