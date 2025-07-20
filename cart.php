<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Check if customer already booked
$hasBooking = isset($_SESSION['customer_name'], $_SESSION['customer_phone'], $_SESSION['customer_date'], $_SESSION['customer_time'], $_SESSION['customer_guests']);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Your Cart - DineSmart</title>
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
    .btn {
      background: #c0392b;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-main {
      background: #2980b9;
      padding: 12px 25px;
      font-size: 16px;
      margin-top: 20px;
      text-decoration: none;
      display: inline-block;
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
      <th>Action</th>
    </tr>
    <?php foreach ($cart as $index => $item): ?>
      <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= number_format($item['price'], 2) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($subtotal, 2) ?></td>
        <td>
          <form method="POST" action="remove_from_cart.php" style="display:inline;">
            <input type="hidden" name="index" value="<?= $index ?>">
            <button class="btn" type="submit">Remove</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr class="total">
      <td colspan="3">Total</td>
      <td colspan="2">Rs. <?= number_format($total, 2) ?></td>
    </tr>
    <tr class="total">
      <td colspan="3">Prepayment (50%)</td>
      <td colspan="2">Rs. <?= number_format($total * 0.5, 2) ?></td>
    </tr>
  </table>

  <?php if (!$hasBooking): ?>
    <div class="center">
      <a href="booktable.html" class="btn btn-main">🪑 Book Table Now</a>
    </div>
  <?php else: ?>
    <div class="center">
      <h3>Scan to Pay (Prepayment)</h3>
      <img src="images/QRcode.jpg" alt="QR Code" style="width: 250px; height: 250px; object-fit: contain; border: 2px solid #ccc; border-radius: 10px;">
      <p style="margin-top: 10px; color: #444;">Please scan this QR to pay 50% and confirm your booking.</p>

      <form action="confirm-booking.php" method="POST">
        <input type="hidden" name="name" value="<?= $_SESSION['customer_name'] ?>">
        <input type="hidden" name="phone" value="<?= $_SESSION['customer_phone'] ?>">
        <input type="hidden" name="date" value="<?= $_SESSION['customer_date'] ?>">
        <input type="hidden" name="time" value="<?= $_SESSION['customer_time'] ?>">
        <input type="hidden" name="guests" value="<?= $_SESSION['customer_guests'] ?>">
        <input type="hidden" name="total" value="<?= $total ?>">
        <input type="hidden" name="prepayment" value="<?= $total * 0.5 ?>">
        <button class="btn btn-main" type="submit">✔ I have paid, Confirm Booking</button>
      </form>
    </div>
  <?php endif; ?>

<?php endif; ?>

</body>
</html>