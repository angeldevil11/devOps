<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit();
}

$edit_booking = null;

// Proses edit booking
if (isset($_GET['edit'])) {
  $edit_id = intval($_GET['edit']);
  $stmt = $conn->prepare("SELECT * FROM bookings WHERE id=? AND user_id=?");
  $stmt->bind_param("ii", $edit_id, $_SESSION['user_id']);
  $stmt->execute();
  $edit_booking = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_booking'])) {
  $stmt = $conn->prepare("UPDATE bookings SET start_time=?, end_time=?, subject_name=?, lecturer_name=? WHERE id=? AND user_id=?");
  $stmt->bind_param("ssssii", $_POST['start_time'], $_POST['end_time'], $_POST['subject_name'], $_POST['lecturer_name'], $_POST['id'], $_SESSION['user_id']);
  if ($stmt->execute()) {
    echo "<script>alert('Booking berhasil diperbarui');window.location='history.php';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui booking');window.location='history.php';</script>";
  }
  exit();
}

// Untuk Admin: Menampilkan semua jadwal
if ($_SESSION['role'] === 'admin') {
  // Admin sees all bookings
  $result = $conn->query("SELECT bookings.*, rooms.building_name, rooms.room_number, users.name, bookings.user_id, bookings.lecturer_name
                          FROM bookings 
                          JOIN rooms ON bookings.room_id = rooms.id 
                          JOIN users ON bookings.user_id = users.id 
                          ORDER BY bookings.day, bookings.start_time");
} else {
  // Dosen only sees bookings for themselves
  $user_id = $_SESSION['user_id'];
  $lecturer_name = $_SESSION['name']; // Lecturer's name from the session
  $result = $conn->query("SELECT bookings.*, rooms.building_name, rooms.room_number, bookings.lecturer_name 
                          FROM bookings 
                          JOIN rooms ON bookings.room_id = rooms.id 
                          WHERE bookings.lecturer_name = '$lecturer_name' 
                          ORDER BY bookings.day, bookings.start_time");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Booking</title>
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      background-color: #cfd6dd;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .card {
      background-color: #fff;
      border-radius: 10px;
      padding: 40px 40px;
      width: 800px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2f4050;
      font-size: 34px;
    }

    .search-wrapper {
      text-align: left;
      margin-bottom: 20px;
    }

    #searchInput {
      display: inline-block;
      width: 100%;
      max-width: 400px;
      padding: 8px 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px 10px;
      font-size: 14px;
      text-align: center;
      white-space: nowrap;
    }

    th {
      background-color: #f0f3f6;
      color: #2f4050;
    }

    .btn-dashboard {
      display: block;
      margin: 0 auto;
      background-color: #2f4050;
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 20px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-dashboard:hover {
      background-color: #3e556b;
    }

    .action-btn {
      text-decoration: none;
      font-size: 14px;
      margin: 0 4px;
      color: #2f4050;
    }

    .action-btn:hover {
      color: red;
    }
    .history {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 50px 20px;
    }

    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: white;
      padding: 30px;
      border-radius: 8px;
      width: 400px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
<div class="history">
  <h2>Riwayat Booking</h2>
  <div class="card">
    <div class="search-wrapper">
      <input type="text" id="searchInput" placeholder="Cari berdasarkan Hari, Kelas, Dosen...">
    </div>
    <table id="bookingTable">
      <thead>
        <tr>
          <th>Hari</th>
          <th>Waktu</th>
          <th>Ruangan</th>
          <th>Kelas</th>
          <th>Dosen</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['day'] ?></td>
          <td><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td>
          <td><?= $row['building_name'] . ' – ' . $row['room_number'] ?></td>
          <td><?= $row['subject_name'] ?></td>
          <td><?= htmlspecialchars($row['lecturer_name']) ?></td>
          <td>
            <?php if ($_SESSION['role'] === 'admin' || $row['user_id'] == $_SESSION['user_id']): ?>
              <a href="history.php?edit=<?= $row['id'] ?>" class="action-btn">✏️</a>
              <a href="delete_booking.php?id=<?= $row['id'] ?>" class="action-btn" onclick="return confirm('Yakin ingin menghapus?')">❌</a>
            <?php else: ?>
              <span style="color: #aaa;">-</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <form action="<?= $_SESSION['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_dosen.php' ?>" method="get">
      <button type="submit" class="btn-dashboard">Dashboard</button>
    </form>
  </div>
</div>
<?php if ($edit_booking): ?>
<div class="modal">
  <div class="modal-content">
    <h3>Edit Booking</h3>
    <form method="POST">
      <input type="hidden" name="id" value="<?= $edit_booking['id'] ?>">
      <label>Jam Mulai:</label>
      <input type="time" name="start_time" value="<?= $edit_booking['start_time'] ?>" required>
      <label>Jam Selesai:</label>
      <input type="time" name="end_time" value="<?= $edit_booking['end_time'] ?>" required>
      <label>Nama Kelas:</label>
      <input type="text" name="subject_name" value="<?= htmlspecialchars($edit_booking['subject_name']) ?>" required>
      <label>Nama Dosen:</label>
      <input type="text" name="lecturer_name" value="<?= htmlspecialchars($edit_booking['lecturer_name']) ?>" required>
      <br><br>
      <button type="submit" name="update_booking">Simpan Perubahan</button>
    </form>
  </div>
</div>
<?php endif; ?>
<script>
  const searchInput = document.getElementById('searchInput');
  const rows = document.querySelectorAll('#bookingTable tbody tr');

  searchInput.addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(keyword) ? '' : 'none';
    });
  });
</script>
</body>
</html>