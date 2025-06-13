<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to admin-bookings page
        header("Location: admin-bookings.php");
        exit();
    } else {
        // Redirect with error message (optional)
        header("Location: admin-bookings.php?error=delete_failed");
        exit();
    }
}
?>
