<?php
$conn = new mysqli('localhost', 'root', 'Devops123!', 'booking_class');
if ($conn->connect_error) {
  die('Koneksi gagal: ' . $conn->connect_error);
}
?>