<?php
$token = strtoupper($_GET['token'] ?? '');
$conn = new mysqli("localhost", "root", "", "dinesmart");

if ($conn->connect_error) {
    echo json_encode(['valid' => false]);
    exit;
}

$stmt = $conn->prepare("SELECT name, phone FROM bookings WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'valid' => true,
        'name' => $row['name'],
        'phone' => $row['phone']
    ]);
} else {
    echo json_encode(['valid' => false]);
}
?>
