<?php
$conn = new mysqli('localhost', 'root', 'DevOps123!', 'booking_class');
if ($conn->connect_error) {
  die('Koneksi gagal: ' . $conn->connect_error);
}
?>