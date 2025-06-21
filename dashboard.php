<?php include "config.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .dashboard-container {
      max-width: 1000px;
      margin: 80px auto;
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
      padding: 80px 60px;
      text-align: center;
    }
    h2 {
      color: maroon;
      font-size: 32px;
      margin-bottom: 40px;
    }
    .nav-links {
      display: flex;
      flex-direction: column;
      gap: 30px;
      margin-top: 50px;
    }
    .nav-links a {
      display: block;
      padding: 24px 25px;
      font-size: 24px;
      width: 100%;
      background-color: maroon;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 12px;
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
    }
    .nav-links a:hover {
      background-color: #800000;
      transform: scale(1.07);
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
    <div class="nav-links">
      <a href="calendar.php">Academic Calendar</a>
      <a href="notices.php">Notice Board</a>
      <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="users.php">Manage Users</a>
      <?php endif; ?>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>