<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "dinesmart";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve POST data
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$guests = $_POST['guests'] ?? '';
$total_amount = $_POST['total'] ?? 0;
$prepayment = $_POST['prepayment'] ?? 0;
$cart = $_SESSION['cart'] ?? [];

// Validate required data
if (!$name || !$phone || !$date || !$time || !$guests || count($cart) == 0) {
    die("❌ Missing customer or cart information. Please book again.");
}

// Generate unique token
$token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

// Save to DB
$stmt = $conn->prepare("INSERT INTO bookings (name, date, time, guests, phone, total_amount, prepayment, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssissds", $name, $date, $time, $guests, $phone, $total_amount, $prepayment, $token);

if ($stmt->execute()) {
  $_SESSION['booking_confirmed'] = true;
  // After successful insert
$_SESSION['bill_token'] = $token;
$_SESSION['bill_cart'] = $cart;
$_SESSION['bill_total'] = $total_amount;
$_SESSION['bill_prepayment'] = $prepayment;

    // Clear session
    unset($_SESSION['cart']);
    unset($_SESSION['customer_name']);
    unset($_SESSION['customer_phone']);
    unset($_SESSION['customer_date']);
    unset($_SESSION['customer_time']);
    unset($_SESSION['customer_guests']);
    
} else {
    die("❌ Error saving booking: " . $conn->error);
}
?>

<!-- HTML Confirmation Page -->
<!DOCTYPE html>
<html>
<head>
  <title>Booking Confirmed - DineSmart</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      padding: 30px;
      text-align: center;
    }
    .confirmation {
      background: #fff;
      padding: 30px;
      max-width: 600px;
      margin: 0 auto;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .confirmation h2 {
      color: #27ae60;
    }
    .confirmation p {
      font-size: 16px;
      margin: 10px 0;
    }
    .token-box {
      background: #ecf0f1;
      padding: 15px;
      margin: 20px auto;
      font-size: 24px;
      font-weight: bold;
      letter-spacing: 2px;
      border-radius: 8px;
      width: fit-content;
      color: #c0392b;
    }
    .btn {
      background: #2980b9;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 20px;
      display: inline-block;
    }
    .screenshot {
      margin-top: 20px;
      color: #e67e22;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="confirmation">
  <h2>🎉 Your Booking Has Been Confirmed!</h2>
  <p>Thank you <strong><?= htmlspecialchars($name) ?></strong> for your prepayment of Rs. <?= number_format($prepayment, 2) ?>.</p>
  <p>Your table for <?= $guests ?> guest(s) on <strong><?= $date ?></strong> at <strong><?= $time ?></strong> is booked.</p>

  <div class="token-box">
    TOKEN: <?= $token ?>
  </div>

  <p class="screenshot">📸 Please take a screenshot of this token and show it at the restaurant counter.</p>
  <a href="index.html" class="btn">Back to Home</a>
</div>

</body>
</html>