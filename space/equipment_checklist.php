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
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch equipment list
$sql = "SELECT * FROM equipment ORDER BY category, name";
$result = $conn->query($sql);

// Fetch user packed equipment if user_equipment table exists
$userEquip = [];
$resUserEquip = $conn->prepare("SELECT equipment_id, is_packed FROM user_equipment WHERE user_id = ?");
if (!$resUserEquip) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$resUserEquip->bind_param("i", $user_id);
$resUserEquip->execute();
$resUserEquip->bind_result($equip_id, $is_packed);
while ($resUserEquip->fetch()) {
  $userEquip[$equip_id] = $is_packed;
}
$resUserEquip->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>🔧 Equipment Checklist</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  
  <div class="container">
<!-- Success message -->
     <?php if (isset($_GET['saved']) && $_GET['saved'] == 1): ?>
  <div style="margin-bottom: 10px; color: #2be0af; font-size: 14px; font-weight: 500; text-align: center;">
    ✅ Checklist saved successfully.
  </div>
<?php endif; ?>
     <!-- ENds Here -->
    <h2>🔧 Equipment Checklist</h2>
    <form action="save_equipment.php" method="POST">
      <?php if ($result->num_rows > 0): ?>
        <table style="width:100%; color:white;">
          <thead>
            <tr>
              <th>Category</th><th>Equipment</th><th>Packed</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $lastCategory = '';
            while ($row = $result->fetch_assoc()): 
              if ($row['category'] !== $lastCategory) {
                echo "<tr><td colspan='3' style='padding-top: 10px; font-weight: bold;'>" . htmlspecialchars($row['category']) . "</td></tr>";
                $lastCategory = $row['category'];
              }
            ?>
            <tr>
              <td></td>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td>
                <input type="checkbox" name="packed[]" value="<?php echo $row['id']; ?>" 
                <?php echo (isset($userEquip[$row['id']]) && $userEquip[$row['id']]) ? "checked" : ""; ?> />
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No equipment found.</p>
      <?php endif; ?>
      <button type="submit" style="margin-top: 15px;">Save Checklist</button>
    </form>
    <a href="dashboard.php" style="color:#00d2ff; margin-top:20px; display:inline-block;">⬅️ Back to Dashboard</a>
  </div>

 
    
</body>
</html>
