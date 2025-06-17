<?php
session_start();

// If already logged in, go to dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>🚀 Space Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h2>🚀 Astronaut Login</h2>

      <?php if (!empty($error)) : ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" action="login_process.php" autocomplete="off">
        <input type="text" placeholder="🆔 Space ID Name" name="username" required />
        <input type="password" placeholder="🔒 Secure Launch Key" name="password" required />
        <button type="submit">Launch 🚀</button>
        <p>Don’t have an account? <a href="register.html">Enroll now</a></p>
      </form>
    </div>
  </div>
</body>
</html>
