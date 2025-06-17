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
  die("Database connection failed!");
}

$sql = "SELECT fullname, email, Passport, phone, username FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>👁️ View Users</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .table-wrapper {
      margin-top: 30px;
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      font-size: 0.95rem;
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    th {
      background-color: rgba(0, 210, 255, 0.3);
    }
    .back-btn {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 20px;
      background: #00d2ff;
      color: #000;
      font-weight: bold;
      text-decoration: none;
      border-radius: 10px;
      transition: 0.3s ease;
    }
    .back-btn:hover {
      background: #00a8cc;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>👨‍🚀 Registered Astronauts</h2>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Passport</th>
            <th>Phone</th>
            <th>Username</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['Passport']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5">No astronauts registered yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
