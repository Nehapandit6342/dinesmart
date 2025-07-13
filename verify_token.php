<?php
header('Content-Type: application/json');

$token = strtoupper(trim($_GET['token'] ?? ''));
if (empty($token)) {
    echo json_encode(['valid' => false, 'error' => 'Token is empty']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "dinesmart");
if ($conn->connect_error) {
    echo json_encode(['valid' => false, 'error' => 'DB connection failed']);
    exit;
}

$stmt = $conn->prepare("SELECT name, phone, items, prepayment FROM bookings WHERE token = ?");

if (!$stmt) {
    echo json_encode(['valid' => false, 'error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($stmt->errno) {
    echo json_encode(['valid' => false, 'error' => 'Execute failed: ' . $stmt->error]);
    exit;
}

if ($row = $result->fetch_assoc()) {
    $items = json_decode($row['items'], true);
    echo json_encode([
        'valid' => true,
        'name' => $row['name'],
        'phone' => $row['phone'],
        'items' => $items,
        'prepaid' => floatval($row['prepayment'])
    ]);
} else {
    echo json_encode(['valid' => false, 'error' => 'Token not found']);
}

$stmt->close();
$conn->close();