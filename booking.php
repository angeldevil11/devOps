
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit();
}

$rooms = $conn->query("SELECT * FROM rooms ORDER BY building_name, room_number");
$selected_room_id = isset($_GET['filter_room']) ? $_GET['filter_room'] : '';

if ($selected_room_id) {
  $stmt = $conn->prepare("SELECT bookings.*, rooms.building_name, rooms.room_number 
                          FROM bookings 
                          JOIN rooms ON bookings.room_id = rooms.id 
                          WHERE room_id = ? 
                          ORDER BY day, start_time");
  $stmt->bind_param("i", $selected_room_id);
  $stmt->execute();
  $jadwal = $stmt->get_result();
} else {
  $jadwal = $conn->query("SELECT bookings.*, rooms.building_name, rooms.room_number 
                          FROM bookings 
                          JOIN rooms ON bookings.room_id = rooms.id 
                          ORDER BY day, start_time");
}

$rooms_for_form = $conn->query("SELECT * FROM rooms ORDER BY building_name, room_number");
$times = ['07:00', '08:40', '10:10', '13:00', '14:40', '16:10'];
$dashboard = $_SESSION['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_dosen.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Kelas</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="sidebar">
    <h2>Universitas Klabat</h2>
    <a href="<?= $dashboard ?>">Dashboard</a>
    <a href="history.php">Riwayat Booking</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="content">
    <div class="inner-content">
      <h2>Jadwal Booking</h2>

      <!-- Filter Dropdown -->
      <form method="GET" style="margin-bottom: 20px;">
        <label for="filter_room">Tampilkan Ruangan:</label>
        <select name="filter_room" onchange="this.form.submit()">
          <option value="">-- Semua Ruangan --</option>
          <?php
          $rooms_reset = $conn->query("SELECT * FROM rooms ORDER BY building_name, room_number");
          while ($r = $rooms_reset->fetch_assoc()):
          ?>
            <option value="<?= $r['id'] ?>" <?= $r['id'] == $selected_room_id ? 'selected' : '' ?>>
              <?= $r['building_name'] . ' - ' . $r['room_number'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </form>

      <!-- Tabel Jadwal -->
      <table>
        <tr>
          <th>Hari</th>
          <th>Jam</th>
          <th>Ruangan</th>
          <th>Kelas</th>
          <th>Dosen</th>
        </tr>
        <?php while ($row = $jadwal->fetch_assoc()): ?>
          <tr>
            <td><?= $row['day'] ?></td>
            <td><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td>
            <td><?= $row['building_name'] . ' - ' . $row['room_number'] ?></td>
            <td><?= $row['subject_name'] ?></td>
            <td><?= $row['lecturer_name'] ?></td>
          </tr>
        <?php endwhile; ?>
      </table>

      <!-- Form Booking -->
      <h2>Form Booking Kelas</h2>
      <form action="booking_submit.php" method="POST">
        <label for="room">Pilih Ruangan:</label>
        <select name="room_id" required>
          <option value="">-- Pilih Ruangan --</option>
          <?php while($r = $rooms_for_form->fetch_assoc()): ?>
            <option value="<?= $r['id'] ?>"><?= $r['building_name'] . ' - ' . $r['room_number'] ?></option>
          <?php endwhile; ?>
        </select>

        <label for="day">Day:</label>
        <select name="day" required>
          <option value="Monday">Monday</option>
          <option value="Tuesday">Tuesday</option>
          <option value="Wednesday">Wednesday</option>
          <option value="Thursday">Thursday</option>
          <option value="Friday">Friday</option>
        </select>

        <label for="start_time">Jam Mulai:</label>
        <select name="start_time" id="start_time" onchange="setEndTime()" required>
          <option value="">-- Pilih Jam --</option>
          <?php foreach ($times as $time): ?>
            <option value="<?= $time ?>"><?= $time ?></option>
          <?php endforeach; ?>
        </select>

        <label for="end_time">Jam Selesai (otomatis):</label>
        <select name="end_time" id="end_time" readonly required>
          <option value="">-- Otomatis --</option>
        </select>

        <label for="subject_name">Nama Kelas:</label>
        <input type="text" name="subject_name" required>

        <?php if ($_SESSION['role'] === 'admin'): ?>
          <label for="lecturer_name">Nama Dosen:</label>
          <input type="text" name="lecturer_name" required>
        <?php endif; ?>

        <button type="submit">Booking Sekarang</button>
      </form>
    </div>
  </div>
</div>

<script>
function setEndTime() {
  const startTime = document.getElementById('start_time').value;
  const endTimeSelect = document.getElementById('end_time');

  if (!startTime) return;

  const [hour, minute] = startTime.split(":").map(Number);
  let endHour = hour;
  let endMinute = minute + 90;

  if (endMinute >= 60) {
    endHour += Math.floor(endMinute / 60);
    endMinute = endMinute % 60;
  }

  const formattedHour = endHour.toString().padStart(2, '0');
  const formattedMinute = endMinute.toString().padStart(2, '0');
  const endTime = `${formattedHour}:${formattedMinute}`;

  endTimeSelect.innerHTML = `<option value="${endTime}" selected>${endTime}</option>`;
}
</script>
</body>
</html>
