<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "dinesmart";

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get values from POST
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $guests = $_POST['guests'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (!$name || !$date || !$time || !$guests || !$phone) {
        die("Please fill in all required fields.");
    }

    // Generate token ID (6-character random string)
    $token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

    // Insert with token
    $stmt = $conn->prepare("INSERT INTO bookings (name, date, time, guests, phone, token) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $name, $date, $time, $guests, $phone, $token);

    if ($stmt->execute()) {
        echo "<h3>✅ Booking saved successfully!</h3>";
        echo "<p>Your Reservation Token is: <strong>$token</strong></p>";
        echo "<p>Please keep this token safe and show it at the restaurant.</p>";
    } else {
        echo "❌ Error: " . $conn->error;
    }

    $conn->close();
}
?>
