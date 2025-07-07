<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "space_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Total users count
$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Total trips count
$tripCount = $conn->query("SELECT COUNT(*) as count FROM trips")->fetch_assoc()['count'];

// Upcoming trips count (next 30 days)
$upcomingTrips = $conn->query("SELECT COUNT(*) as count FROM trips WHERE trip_date >= CURDATE() AND trip_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetch_assoc()['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Reports</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container">
    <h2>📋 Reports Summary</h2>
    <ul style="color: white; font-size: 1.2rem;">
      <li>Total Users: <?= $userCount ?></li>
      <li>Total Trips Booked: <?= $tripCount ?></li>
      <li>Upcoming Trips (Next 30 days): <?= $upcomingTrips ?></li>
    </ul>

    <a href="dashboard.php" style="color:#00d2ff; display:inline-block; margin-top:20px;">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
