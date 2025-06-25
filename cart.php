<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cart - DineSmart</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fff8f0;
      padding: 20px;
    }
    h2 {
      color: #c0392b;
      text-align: center;
    }
    table {
      width: 100%;
      max-width: 800px;
      margin: 30px auto;
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid #ccc;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #d35400;
      color: white;
    }
    .total {
      font-weight: bold;
      color: #27ae60;
    }
    .center {
      text-align: center;
      margin-top: 30px;
    }
    .qr-img {
      width: 200px;
      border: 4px solid #ddd;
      padding: 8px;
      background: white;
      margin: 15px auto;
    }
    .btn {
      background: #2980b9;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<h2>🛒 Your Cart</h2>

<?php if (count($cart) === 0): ?>
  <p class="center">Your cart is empty. <a href="menu.php">Go to Menu</a></p>
<?php else: ?>
  <table>
    <tr>
      <th>Item Name</th>
      <th>Price (Rs)</th>
      <th>Qty</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach ($cart as $item): ?>
      <?php
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
      ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= number_format($item['price'], 2) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($subtotal, 2) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr class="total">
      <td colspan="3">Total</td>
      <td>Rs. <?= number_format($total, 2) ?></td>
    </tr>
    <tr class="total">
      <td colspan="3">Prepayment (50%)</td>
      <td>Rs. <?= number_format($total * 0.5, 2) ?></td>
    </tr>
  </table>

  <div class="center">
    <h3>📱 Scan to Pay (Prabhu Bank)</h3>
    <img class="qr-img" src="QRcode.jpg" alt="QR Code for payment">

    <form action="confirm-booking.php" method="POST">
      <input type="hidden" name="total" value="<?= $total ?>">
      <input type="hidden" name="prepayment" value="<?= $total * 0.5 ?>">
      <button class="btn" type="submit">✔️ I have paid, Confirm Booking</button>
    </form>
  </div>
<?php endif; ?>

</body>
</html>
