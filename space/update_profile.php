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
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$profile_pic = '';

// Handle file upload
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
  $fileTmp = $_FILES['profile_pic']['tmp_name'];
  $fileName = basename($_FILES['profile_pic']['name']);
  $targetDir = "uploads/";
  $targetFile = $targetDir . time() . "_" . $fileName;

  if (move_uploaded_file($fileTmp, $targetFile)) {
    $profile_pic = basename($targetFile);
  }
}

// Update database
if ($profile_pic) {
  $stmt = $conn->prepare("UPDATE users SET fullname=?, phone=?, profile_pic=? WHERE username=?");
  $stmt->bind_param("ssss", $fullname, $phone, $profile_pic, $username);
} else {
  $stmt = $conn->prepare("UPDATE users SET fullname=?, phone=? WHERE username=?");
  $stmt->bind_param("sss", $fullname, $phone, $username);
}

if ($stmt->execute()) {
  header("Location: dashboard.php");
  exit();
} else {
  echo "Error updating profile.";
}
?>
