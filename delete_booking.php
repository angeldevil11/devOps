<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit();
}

$id = $_GET['id'];
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Admin bisa hapus semua, dosen hanya boleh hapus booking miliknya
if ($role === 'admin') {
  $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
  $stmt->bind_param("i", $id);
} else {
  $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $id, $user_id);
}

if ($stmt->execute()) {
  echo "<script>alert('Booking berhasil dihapus'); window.location='history.php';</script>";
} else {
  echo "<script>alert('Gagal menghapus booking'); window.location='history.php';</script>";
}
?>
