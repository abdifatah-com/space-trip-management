<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$host = "localhost"; $user = "root"; $pass = ""; $dbname = "space_db";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch all trips
$result = $conn->query("SELECT id, user_id, destination, trip_date, spacecraft FROM trips ORDER BY trip_date DESC");

// List of spacecrafts (you can later load this from a table too)
$spacecrafts = ["Falcon X", "Orion Explorer", "Starliner", "Galactic Cruiser"];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trip_id'], $_POST['spacecraft'])) {
    $trip_id = $_POST['trip_id'];
    $spacecraft = $_POST['spacecraft'];

    $stmt = $conn->prepare("UPDATE trips SET spacecraft = ? WHERE id = ?");
    $stmt->bind_param("si", $spacecraft, $trip_id);
    $stmt->execute();
    $stmt->close();

    header("Location: assign_spacecraft.php?assigned=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>🛰️ Assign Spacecraft</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <h2>🛰️ Assign Spacecraft to Trips</h2>

    <?php if (isset($_GET['assigned'])): ?>
      <p style="color: #2be0af; font-weight: 500;">✅ Spacecraft assigned successfully!</p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
      <table style="width:100%; color: white;">
        <thead>
          <tr>
            <th>Destination</th>
            <th>Date</th>
            <th>Current Spacecraft</th>
            <th>Assign</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['destination']); ?></td>
            <td><?php echo htmlspecialchars($row['trip_date']); ?></td>
            <td><?php echo htmlspecialchars($row['spacecraft'] ?? 'None'); ?></td>
            <td>
              <form method="POST" style="display: flex; gap: 8px;">
                <input type="hidden" name="trip_id" value="<?php echo $row['id']; ?>">
                <select name="spacecraft" required>
                  <option value="">Select</option>
                  <?php foreach ($spacecrafts as $s): ?>
                    <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit">Assign</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No trips found.</p>
    <?php endif; ?>

    <a href="dashboard.php" style="color: #00d2ff; margin-top: 20px; display: inline-block;">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
