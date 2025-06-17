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

// Prepare statement to get mission history for logged in user
$sql = "
  SELECT trips.destination, trips.company, trips.trip_date, bookings.booked_at
  FROM bookings
  JOIN trips ON bookings.trip_id = trips.id
  WHERE bookings.user_id = ?
  ORDER BY bookings.booked_at DESC
  LIMIT 25
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>🚀 Mission History</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container">
    <h2>🚀 Your Mission History</h2>

    <?php if ($result->num_rows > 0): ?>
      <table style="width:100%; border-collapse: collapse; color: white;">
        <thead>
          <tr>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Company</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Destination</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Trip Date</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Booked On</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($row['company']); ?></td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($row['destination']); ?></td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($row['trip_date']); ?></td>
            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($row['booked_at']); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>You haven't booked any missions yet. Time to explore the stars! 🚀</p>
    <?php endif; ?>

    <a href="dashboard.php" style="display:inline-block; margin-top:20px; color:#00d2ff;">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
