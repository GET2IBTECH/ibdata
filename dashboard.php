<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT full_name, wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>IBT SUB - Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 0;
      transition: background 0.3s ease, color 0.3s ease;
    }

    header {
      background-color: #0d6efd;
      color: white;
      padding: 25px;
      text-align: center;
    }

    .container {
      padding: 30px 20px;
      max-width: 1100px;
      margin: auto;
    }

    .welcome-card {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      margin-bottom: 30px;
      text-align: center;
    }

    .welcome-card h2 {
      margin: 0 0 10px;
      font-size: 26px;
    }

    .wallet {
      font-size: 18px;
      margin-top: 10px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 20px;
    }

    .service {
      background-color: white;
      padding: 20px;
      text-align: center;
      border-radius: 12px;
      text-decoration: none;
      color: #0d6efd;
      font-weight: 600;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.07);
      transition: transform 0.2s ease, background 0.3s ease;
    }

    .service:hover {
      transform: translateY(-5px);
      background-color: #eaf3ff;
    }

    .service i {
      font-size: 26px;
      margin-bottom: 10px;
      display: block;
    }

    .dropdown {
      position: relative;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      left: 0;
      top: 100%;
      background-color: #fff;
      min-width: 200px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
      border-radius: 6px;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown-content a {
      color: #0d6efd;
      padding: 10px 15px;
      display: block;
      text-decoration: none;
    }

    .dropdown-content a:hover {
      background-color: #f1f1f1;
    }

    .logout {
      margin-top: 30px;
      text-align: center;
    }

    .logout a {
      padding: 10px 20px;
      background: #dc3545;
      color: white;
      border-radius: 6px;
      text-decoration: none;
    }

    .logout a:hover {
      background: #bb2d3b;
    }

    .dark-mode {
      background-color: #1e1e1e;
      color: #f0f0f0;
    }

    .dark-mode .service {
      background-color: #2c2c2c;
      color: #f0f0f0;
    }

    .dark-mode .welcome-card {
      background-color: #2c2c2c;
      color: #f0f0f0;
    }

    .dark-mode .dropdown-content {
      background-color: #333;
    }

    .dark-mode .dropdown-content a {
      color: #ddd;
    }

    .dark-mode .dropdown-content a:hover {
      background-color: #444;
    }

    .dark-toggle {
      text-align: center;
      margin-top: 20px;
    }

    .dark-toggle button {
      background: #0d6efd;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    @media (max-width: 600px) {
      .welcome-card h2 {
        font-size: 20px;
      }
      .wallet {
        font-size: 16px;
      }
      .grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      }
      .service i {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Welcome to IBT SUB</h1>
</header>

<div class="container">
  <div class="welcome-card">
    <h2>Hello <?php echo htmlspecialchars($user['full_name']); ?></h2>
    <p class="wallet">Wallet Balance: ₦<?php echo number_format($user['wallet_balance'], 2); ?></p>
  </div>

  <div class="grid">
    <a href="buy-data.php" class="service"><i class="fas fa-signal"></i>Buy Data</a>
    <a href="buy-airtime.php" class="service"><i class="fas fa-phone"></i>Buy Airtime</a>
    <a href="buy-cable.php" class="service"><i class="fas fa-tv"></i>Cable TV</a>
    <a href="buy-electricity.php" class="service"><i class="fas fa-bolt"></i>Electricity Bill</a>
    <a href="sell-airtime.php" class="service"><i class="fas fa-money-bill-transfer"></i>Airtime to Cash</a>

    <div class="service dropdown">
      <i class="fas fa-graduation-cap"></i>Education Pins ▾
      <div class="dropdown-content">
        <a href="#">WAEC Registration PIN</a>
        <a href="#">NECO Scratch Token</a>
        <a href="#">WAEC Scratch Card</a>
      </div>
    </div>

    <a href="fund-wallet.php" class="service"><i class="fas fa-wallet"></i>Fund Wallet</a>
    <a href="transactions.php" class="service"><i class="fas fa-receipt"></i>Transactions</a>
  </div>
  <?php if (isset($_GET['success'])): ?>
<script>
    alert("🎉 Data purchase successful! Your wallet has been updated.");
</script>
<?php endif; ?>


  <div class="logout">
    <a href="logout.php">Logout</a>
  </div>

  <div class="dark-toggle">
    <button onclick="toggleDarkMode()">🌙 Toggle Dark Mode</button>
  </div>
</div>

<script>
  function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
  }
</script>


</body>
</html>
