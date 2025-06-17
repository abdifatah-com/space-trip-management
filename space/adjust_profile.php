<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "space_db");
if ($conn->connect_error) {
  die("Connection failed.");
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT fullname, phone, profile_pic FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($fullname, $phone, $profile_pic);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Adjust Profile</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .form-wrapper {
      margin-top: 30px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
    }
    .form-wrapper input, .form-wrapper button {
      width: 80%;
    }
    .profile-pic {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 10px;
      border: 2px solid #fff;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>👤 Adjust Your Profile</h2>
    
    <form class="form-wrapper" action="update_profile.php" method="POST" enctype="multipart/form-data">
      <?php if ($profile_pic): ?>
        <img src="uploads/<?= $profile_pic ?>" class="profile-pic" alt="Profile Picture">
      <?php else: ?>
        <img src="uploads/default.png" class="profile-pic" alt="No Picture">
      <?php endif; ?>

      <input type="text" name="fullname" placeholder="Full Name" value="<?= htmlspecialchars($fullname) ?>" required />
      <input type="tel" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($phone) ?>" required />
      <input type="file" name="profile_pic" accept="image/*" />
      
      <button type="submit">💾 Save Changes</button>
    </form>

    <a href="dashboard.php" style="margin-top: 20px; color: #82e9ff;">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
