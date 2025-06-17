<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "space_db"; // Use your actual DB password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
