<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dinesmart";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "DELETE FROM `menu_items` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo "<script>alert('Item deleted successfully'); window.location.href='admin-menu.php';</script>";
} else {
  echo "Error deleting item: " . $conn->error;
}

$conn->close();
?>