<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && $password === $user['password'] && $user['role'] === $role) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];
    header("Location: " . ($user['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_dosen.php'));
    exit();
  } else {
    echo "<script>alert('Login gagal! Pastikan data dan role sesuai.');window.location.href='login.php';</script>";
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Booking Kelas</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      margin: 0;
      background-color: #e9eff5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
      text-align: center;
      color: #2f4050;
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-weight: bold;
      color: #2f4050;
    }

    input, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 25px;
      background-color: #2f4050;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #3e556b;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login Sistem</h2>
    <form action="login.php" method="POST">
      <label for="role">Masuk sebagai:</label>
        <select name="role" id="role" required>
          <option value="">-- Pilih Role --</option>
          <option value="admin">Admin</option>
          <option value="dosen">Dosen</option>
        </select>
        
      <label for="email">Email</label>
      <input type="text" name="email" id="email" required>

      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>

      <button type="submit">Masuk</button>
    </form>
  </div>
</body>
</html>
