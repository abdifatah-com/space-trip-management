<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
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

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Astronaut';

// Prepare statement to get trips for this user
$stmt = $conn->prepare("SELECT company, destination, trip_date, created_at FROM trips WHERE user_id = ? ORDER BY trip_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Scheduled Trips</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #121212;
      color: white;
      padding: 20px;
      min-height: 100vh;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
    }
    h2 {
      margin-bottom: 15px;
    }
    .welcome {
      margin-bottom: 30px;
      font-weight: 600;
      font-size: 1.2rem;
      color: #00d2ff;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #333;
      text-align: left;
    }
    th {
      background-color: #1e1e1e;
    }
    tr:hover {
      background-color: #222;
    }
    .no-trips {
      font-style: italic;
      color: #888;
      margin-bottom: 20px;
    }
    .btn-back {
      display: inline-block;
      padding: 10px 20px;
      background-color: #00d2ff;
      color: #121212;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }
    .btn-back:hover {
      background-color: #0099cc;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🚀 Your Scheduled Trips</h2>
    <p class="welcome">Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>! Here are the Available Trips :</p>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Company</th>
            <th>Destination</th>
            <th>Trip Date</th>
            <th>Booked On</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['company']); ?></td>
            <td><?php echo htmlspecialchars($row['destination']); ?></td>
            <td><?php echo date("M d, Y", strtotime($row['trip_date'])); ?></td>
            <td><?php echo date("M d, Y H:i", strtotime($row['created_at'])); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-trips">You have no scheduled trips yet. Go book one! 🚀</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn-back">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
