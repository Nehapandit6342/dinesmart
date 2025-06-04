<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "dinesmart";

    // Connect to DB
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Safely fetch values
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $guests = $_POST['guests'] ?? '';

    // Check all fields filled
    if (!$name || !$date || !$time || !$guests) {
        die("Please fill in all required fields.");
    }

    // Insert into DB
    $sql = "INSERT INTO bookings (name, date, time, guests)
            VALUES ('$name', '$date', '$time', '$guests')";

    if ($conn->query($sql) === TRUE) {
      echo "✅ Booking saved successfully!";
    } else {
      echo "❌ Error: " . $conn->error;
    }

    $conn->close();

} else {
    echo "Invalid access method.";
}
?>
