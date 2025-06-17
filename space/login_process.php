<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "space_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed.");
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $password) {
  // Select id and password for the given username
  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $hashed);
    $stmt->fetch();

    if (password_verify($password, $hashed)) {
      // Save both user_id and username in session
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;

      header("Location: dashboard.php");
      exit();
    } else {
      // Wrong password
      header("Location: user_not_found.php");
      exit();
    }
  } else {
    // User not found
    header("Location: user_not_found.php");
    exit();
  }
} else {
  // Missing fields
  header("Location: user_not_found.php");
  exit();
}

$_SESSION['user_id'] = $user_id_from_db;  // The user’s ID from your DB
$_SESSION['username'] = $username;        // The username from form or DB

?>
