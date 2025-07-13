<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['token'];
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $items = $_POST['items'];
  $total = $_POST['total'];
  $bill_date = date('Y-m-d');

  $stmt = $conn->prepare("INSERT INTO bills (token, customer_name, phone, items, total_amount, bill_date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssds", $token, $name, $phone, $items, $total, $bill_date);
  $stmt->execute();
}
?>