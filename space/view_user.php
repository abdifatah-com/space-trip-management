<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "space_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, fullname, username, email, phone FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>View Users - Astronaut Trip</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      color: #fff;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      text-align: left;
    }
    th {
      background: rgba(0, 210, 255, 0.3);
    }
    tr:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    .back-btn {
      margin-top: 20px;
      display: inline-block;
      padding: 10px 20px;
      background: #00d2ff;
      color: #000;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .back-btn:hover {
      background: #00a8cc;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>👁️ Registered Astronauts</h2>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['fullname']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No astronauts found.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
