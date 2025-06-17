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

// Fetch users for dropdown
$users_result = $conn->query("SELECT id, fullname FROM users ORDER BY fullname ASC");

$companies = ["SpaceX", "Blue Origin", "Virgin Galactic", "NASA", "Roscosmos"];

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'] ?? '';
  $company = $_POST['company'] ?? '';
  $destination = trim($_POST['destination'] ?? '');
  $trip_date = $_POST['trip_date'] ?? '';

  if (!$user_id || !$company || !$destination || !$trip_date) {
    $errors[] = "Please fill all fields.";
  } else {
    $stmt = $conn->prepare("INSERT INTO trips (user_id, company, destination, trip_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $company, $destination, $trip_date);
    if ($stmt->execute()) {
      $success = "Trip scheduled successfully!";
    } else {
      $errors[] = "Failed to schedule trip.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Schedule a Trip - Astronaut Trip</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
      max-width: 500px;
      margin: auto;
      margin-top: 30px;
      color: #fff;
    }
    select, input[type="date"], input[type="text"], button {
      padding: 12px 15px;
      border-radius: 10px;
      border: none;
      background: rgba(255, 255, 255, 0.2);
      color: #000;
      font-size: 1rem;
      width: 100%;
    }
    button {
      background: #00d2ff;
      color: #000;
      font-weight: 700;
      cursor: pointer;
      transition: 0.3s ease;
    }
    button:hover {
      background: #00a8cc;
    }
    .message {
      text-align: center;
      font-weight: 600;
    }
    .error {
      color: #ff6b6b;
    }
    .success {
      color: #6bff8c;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🛰️ Schedule a Trip</h2>

    <?php if ($errors): ?>
      <div class="message error"><?= implode("<br>", $errors) ?></div>
    <?php elseif ($success): ?>
      <div class="message success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="user_id">Select Astronaut:</label>
      <select name="user_id" id="user_id" required>
        <option value="">-- Choose an Astronaut --</option>
        <?php while ($user = $users_result->fetch_assoc()): ?>
          <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['fullname']) ?></option>
        <?php endwhile; ?>
      </select>

      <label for="company">Space Company:</label>
      <select name="company" id="company" required>
        <option value="">-- Select Company --</option>
        <?php foreach ($companies as $c): ?>
          <option value="<?= $c ?>"><?= $c ?></option>
        <?php endforeach; ?>
      </select>

      <label for="destination">Destination / Mission Name:</label>
      <input type="text" name="destination" id="destination" placeholder="e.g. Mars, Moon Base Alpha" required />

      <label for="trip_date">Trip Date:</label>
      <input type="date" name="trip_date" id="trip_date" required />

      <button type="submit">Schedule Trip 🚀</button>
    </form>

    <a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
