<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "space_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  echo "Database connection failed.";
  exit();
}

$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$passport = $_POST['passport_number'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($fullname && $email && $phone && $passport && $username && $password) {
  // check if user already exists
  $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $check->bind_param("s", $username);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    echo "Username already exists.";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, passport_number, username, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullname, $email, $phone, $passport, $username, $hash);
    $stmt->execute();
    echo "✅ User created successfully! You can now log in.";
  }
} else {
  echo "Please fill in all fields.";
}
?>
