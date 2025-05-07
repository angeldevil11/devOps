<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'admin') {
  $query = "DELETE FROM bookings WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
} else {
  $query = "DELETE FROM bookings WHERE id = ? AND user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $id, $user_id);
}
if ($stmt->execute()) {
  echo "<script>alert('Booking dibatalkan.');window.location='history.php';</script>";
} else {
  echo "<script>alert('Gagal membatalkan booking.');window.location='history.php';</script>";
}
?>