<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Astronaut Trip Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  
  <div class="container">
    <div class="dashboard-header">
      <h2>🚀 Astronaut Trip Dashboard</h2>
      <p>Welcome, <?php echo $_SESSION['username']; ?> 👨‍🚀</p>
    </div>

 <!-- Wrap main buttons -->
  <div class="button-grid main-buttons">
 <div class="button-grid main-buttons">
  <a href="user_manage.php" class="dashboard-button">➕ Add / Delete User</a>
  <a href="schdule_trip.php" class="dashboard-button">🛰️ Schedule a Trip</a>
  <a href="reports.php" class="dashboard-button">📋 Reports</a>
</div>
  </div> 

    <div class="logout-container">
      <a href="logout.php" class="logout-button">🚪 Logout</a>

    </div>
  </div>
</body>
</html>
