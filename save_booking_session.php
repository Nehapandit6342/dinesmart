<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['customer_name'] = $_POST['name'] ?? '';
    $_SESSION['customer_phone'] = $_POST['phone'] ?? '';
    $_SESSION['customer_date'] = $_POST['date'] ?? '';
    $_SESSION['customer_time'] = $_POST['time'] ?? '';
    $_SESSION['customer_guests'] = $_POST['guests'] ?? '';

    // Redirect to cart if cart is not empty, else go to menu
    if (!empty($_SESSION['cart'])) {
        header("Location: cart.php");
    } else {
        header("Location: menu.php");
    }
    exit;
} else {
    echo "❌ Invalid request.";
}
?>
