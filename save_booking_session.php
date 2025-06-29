<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['customer_name'] = $_POST['name'] ?? '';
    $_SESSION['customer_phone'] = $_POST['phone'] ?? '';
    $_SESSION['customer_date'] = $_POST['date'] ?? '';
    $_SESSION['customer_time'] = $_POST['time'] ?? '';
    $_SESSION['customer_guests'] = $_POST['guests'] ?? '';

    if (!$_SESSION['customer_name'] || !$_SESSION['customer_phone'] || !$_SESSION['customer_date'] || !$_SESSION['customer_time'] || !$_SESSION['customer_guests']) {
        die("❌ Missing booking details.");
    }

    // Mark booking info saved
    $_SESSION['booking_info_saved'] = true;

    header("Location: cart.php");
    exit;
} else {
    echo "❌ Invalid access method.";
}