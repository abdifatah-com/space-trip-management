<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "space_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  echo "❌ Database error.";
  exit();
}

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($fullname && $email && $username && $password) {
  $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $check->bind_param("s", $username);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    echo "⚠️ Username already exists.";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $email, $username, $hash);
    $stmt->execute();
    echo "✅ User added successfully!";
  }
} else {
  echo "⚠️ Please fill in all fields.";
}
?>
