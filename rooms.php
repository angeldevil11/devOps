<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.html');
  exit();
}

// Tambah ruangan
if (isset($_POST['add'])) {
  $building = $_POST['building_name'];
  $room = $_POST['room_number'];

  // Check if the room already exists
  $stmt = $conn->prepare("SELECT COUNT(*) FROM rooms WHERE building_name = ? AND room_number = ?");
  $stmt->bind_param("ss", $building, $room);
  $stmt->execute();
  $stmt->bind_result($room_exists);
  $stmt->fetch();
  $stmt->close();

  if ($room_exists > 0) {
    // Room already exists, show an error message
    $error_message = "Ruangan sudah ada di database!";
  } else {
    // If the room doesn't exist, insert the new room
    $stmt = $conn->prepare("INSERT INTO rooms (building_name, room_number) VALUES (?, ?)");
    $stmt->bind_param("ss", $building, $room);
    $stmt->execute();
    $stmt->close();
    $success_message = "Ruangan berhasil ditambahkan!";
  }
}

// Hapus ruangan
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM rooms WHERE id = $id");
}

$rooms = $conn->query("SELECT * FROM rooms ORDER BY building_name, room_number");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Ruangan</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      background-color: #dfe6ec;
      margin: 0;
      padding: 40px;
    }

    h2 {
      color: #2f4050;
      text-align: center;
      margin-bottom: 30px;
    }

    .layout-wrapper {
      max-width: 1000px;
      margin: 0 auto;
      display: flex;
      gap: 40px;
      justify-content: center;
      align-items: flex-start;
    }

    .form-section {
      flex: 1;
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .form-section label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }

    .form-section input[type="text"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    .form-section button {
      margin-top: 15px;
      background-color: #2f4050;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 20px;
      cursor: pointer;
    }

    .form-section button:hover {
      background-color: #3e556b;
    }

    .room-list {
      flex: 1;
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .scroll-box {
      max-height: 400px;
      overflow-y: auto;
      padding-right: 10px;
      margin-top: 10px;
    }

    ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    ul li {
      background-color: #ffffff;
      padding: 12px;
      margin-bottom: 8px;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ddd;
    }

    a {
      color: #c0392b;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    .dashboard-button {
      display: flex;
      justify-content: flex-end;
      margin-top: 40px;
      padding-right: 60px;
    }
  </style>
</head>
<body>
  <h2>Manajemen Ruangan</h2>
  <div class="layout-wrapper">
    <div class="room-list">
      <h3>Daftar Ruangan</h3>
      <div class="scroll-box">
        <ul>
          <?php while($r = $rooms->fetch_assoc()): ?>
            <li>
              <?= htmlspecialchars($r['building_name']) . ' - ' . htmlspecialchars($r['room_number']) ?>
              <span>
                <a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Yakin ingin menghapus ruangan ini?')">[Hapus]</a>
              </span>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>

    <div class="form-section">
      <?php if (isset($error_message)): ?>
        <div style="color: red; font-weight: bold;"><?= $error_message ?></div>
      <?php elseif (isset($success_message)): ?>
        <div style="color: green; font-weight: bold;"><?= $success_message ?></div>
      <?php endif; ?>
      <form method="POST">
        <label>Gedung</label>
        <input type="text" name="building_name" placeholder="Contoh: GK1" required>

        <label>Nomor Ruangan</label>
        <input type="text" name="room_number" placeholder="Contoh: 101" required>

        <button type="submit" name="add">+ Tambah Ruangan</button>
      </form>
      <form action="dashboard_admin.php" method="get">
        <button type="submit">Dashboard</button>
      </form>
    </div>
  </div>
</body>
</html>