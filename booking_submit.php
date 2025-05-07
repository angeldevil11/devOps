<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit();
}

$user_id = $_SESSION['user_id'];
$room_id = $_POST['room_id'];
$day = $_POST['day'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$subject_name = $_POST['subject_name'];

// ambil nama dosen sesuai peran
$lecturer_name = ($_SESSION['role'] === 'admin') ? $_POST['lecturer_name'] : $_SESSION['name'];

// validasi bentrok jadwal
$stmt = $conn->prepare("SELECT * FROM bookings WHERE room_id=? AND day=? AND (
  (start_time <= ? AND end_time > ?) OR
  (start_time < ? AND end_time >= ?) OR
  (start_time >= ? AND end_time <= ?)
)");
$stmt->bind_param("isssssss", $room_id, $day, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo "<script>alert('Ruangan sudah terbooking di waktu tersebut!'); window.location.href='booking.php';</script>";
  exit();
}

// simpan ke database
$stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, day, start_time, end_time, subject_name, lecturer_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssss", $user_id, $room_id, $day, $start_time, $end_time, $subject_name, $lecturer_name);

if ($stmt->execute()) {
  $redirect = ($_SESSION['role'] === 'admin') ? 'dashboard_admin.php' : 'dashboard_dosen.php';
  echo "<script>alert('Booking berhasil!'); window.location.href='$redirect';</script>";
} else {
  echo "<script>alert('Gagal menyimpan booking.'); window.location.href='booking.php';</script>";
}
?>
