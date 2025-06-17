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
$packed = $_POST['packed'] ?? [];

// Clear previous selections
$conn->query("DELETE FROM user_equipment WHERE user_id = $user_id");

// Save new selections
if (!empty($packed)) {
    $stmt = $conn->prepare("INSERT INTO user_equipment (user_id, equipment_id, is_packed) VALUES (?, ?, 1)");
    foreach ($packed as $equip_id) {
        $stmt->bind_param("ii", $user_id, $equip_id);
        $stmt->execute();
    }
    $stmt->close();
}

// ✅ Redirect with success flag
header("Location: equipment_checklist.php?saved=1");
exit();

?>
