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
if ($conn->connect_error) die("Connection failed.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $delete_id = intval($_POST['user_id']);

  // Prevent deleting yourself accidentally
  if ($delete_id === $_SESSION['user_id']) {
    die("You cannot delete your own account here.");
  }

  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $delete_id);
  $stmt->execute();
  $stmt->close();

  header("Location: user_manage.php");
  exit();
}
?>
