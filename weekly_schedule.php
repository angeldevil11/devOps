<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

// Jika admin yang login
if ($_SESSION['role'] === 'admin') {
    $stmt = $conn->prepare("
        SELECT bookings.*, rooms.building_name, rooms.room_number, users.name AS lecturer_name 
        FROM bookings
        JOIN rooms ON bookings.room_id = rooms.id
        JOIN users ON bookings.user_id = users.id
        ORDER BY bookings.day, bookings.start_time
    ");
} else { 
    // Jika dosen yang login, hanya tampilkan jadwal milik dosen tersebut
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT bookings.*, rooms.building_name, rooms.room_number, bookings.lecturer_name 
        FROM bookings
        JOIN rooms ON bookings.room_id = rooms.id
        WHERE bookings.user_id = ?
        ORDER BY bookings.day, bookings.start_time
    ");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($b = $result->fetch_assoc()) {
    // Mengelompokkan jadwal berdasarkan hari
    $key = $b['day'];

    // Menyusun informasi untuk setiap booking
    $info = $b['subject_name'] . ' (' . $b['building_name'] . ' ' . $b['room_number'] . ')';
    
    if ($_SESSION['role'] === 'admin') {
        $info .= '<br><small><i>' . htmlspecialchars($b['lecturer_name']) . '</i></small>';
    }

    // Menambahkan data ke dalam array sesuai hari
    $data[$key][] = $info;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Mingguan</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #dfe6ec;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2f4050;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
            vertical-align: middle;
        }

        th {
            background-color: #e0e6ed;
            font-weight: bold;
            color: #2f4050;
        }

        .dashboard-button {
            margin-top: 30px;
            text-align: center;
        }

        .dashboard-button button {
            padding: 10px 30px;
            background-color: #2f4050;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
        }

        .dashboard-button button:hover {
            background-color: #3e556b;
        }
    </style>
</head>
<body>
    <h2>Jadwal Mingguan</h2>
    <div class="container">

        <table>
            <tr>
                <th>Hari</th>
                <th>Jadwal</th>
            </tr>

            <?php foreach ($days as $day): ?>
                <tr>
                    <td><?= $day ?></td>
                    <td>
                        <?php 
                        // Menampilkan data jika ada untuk hari tersebut
                        echo isset($data[$day]) ? implode('<br><hr>', $data[$day]) : '-';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="dashboard-button">
            <form action="<?= $_SESSION['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_dosen.php' ?>" method="get">
                <button type="submit">Dashboard</button>
            </form>
        </div>
    </div>
</body>
</html>