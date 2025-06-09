<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dinesmart";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';

if (!$date || !$time) {
  echo "invalid";
  exit;
}

$sql = "SELECT COUNT(*) as total FROM bookings WHERE date = ? AND time = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $date, $time);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$max_tables = 20;

if ($row['total'] < $max_tables) {
  echo "available";
} else {
  echo "full";
}

$conn->close();
?>
