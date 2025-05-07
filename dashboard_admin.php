<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.html');
  exit();
}
$name = $_SESSION['name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      margin: 0;
      background-color: #dfe6ec;
    }

    .dashboard {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 50px 20px;
    }

    h2 {
      font-size: 28px;
      color: #2f4050;
      margin-bottom: 40px;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(2, 200px);
      gap: 40px;
      justify-content: center;
    }

    .menu-item {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.2s;
    }

    .menu-item:hover {
      transform: translateY(-5px);
    }

    .menu-item img {
      width: 64px;
      height: 64px;
      margin-bottom: 15px;
      background-color: #2f4050;
      border-radius: 6px;
    }

    .menu-item a {
      text-decoration: none;
      display: block;
      margin-top: 10px;
      font-weight: bold;
      color: #2f4050;
      font-size: 16px;
    }
    .menu-item img {
      width: 100px;
      height: 100px;
      object-fit: cover; 
      margin-bottom: 1px;
      /*background-color: #f0f0f0; /* opsional untuk latar */
    }
    .logout-bar {
      position: absolute;
      top: 85px; 
      right: 70px;
    }

    .logout-bar a {
      font-family: "Times New Roman", Times, serif;
      background-color: #2f4050;
      color: white;
      text-decoration: none;
      padding: 10px 35px;
      border-radius: 25px;
      font-size: 16px;
      transition: background 0.3s;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .logout-bar a:hover {
      background-color: #3e556b;
    }

  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Hallo, <?= $name ?></h2>
    <div class="menu-grid">
      <div class="menu-item">
        <img src="images/booking.png" alt="Booking Icon">
        <a href="booking.php">Booking Kelas</a>
      </div>
      <div class="menu-item">
        <img src="images/riwayat.png" alt="Booking Icon">
        <a href="history.php">Riwayat Booking</a>
      </div>
      <div class="menu-item">
        <img src="images/jadwal.png" alt="Booking Icon">
        <a href="weekly_schedule.php">Jadwal Mingguan</a>
      </div>
      <div class="menu-item">
        <img src="images/manage.png" alt="Booking Icon">
        <a href="rooms.php">Manajemen Ruangan</a>
      </div>
    </div>
  </div>
  <div class="logout-bar">
    <a href="login.php">Logout</a>
  </div>
</body>
</html>
