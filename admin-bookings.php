<?php
include 'config.php';

$sql = "SELECT * FROM bookings ORDER BY date DESC, time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage Bookings</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #fdfcfb;
    }
    h2 {
      text-align: center;
      color: #c0392b;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table th, table td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    table th {
      background-color: #d35400;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>

<h2>Manage Table Bookings</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Date</th>
    <th>Time</th>
    <th>Guests</th>
    <th>Phone</th>
    <th>Token</th>
    <th>Action</th> <!-- New column for Delete button -->
  </tr>
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['time'] ?></td>
        <td><?= $row['guests'] ?></td>
        <td><?= $row['phone'] ?></td>
        <td><?= $row['token'] ?></td>
        <td>
         <form method="POST" action="delete-booking.php" onsubmit="return confirm('Are you sure you want to delete this booking?');" style="display:inline;">
  <input type="hidden" name="id" value="<?= $row['id'] ?>">
  <button type="submit" style="background-color:#e74c3c; color:white; border:none; padding:5px 10px; border-radius:5px;">Delete</button>
</form>

        </td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr><td colspan="8">No bookings found.</td></tr>
  <?php endif; ?>
</table>


</body>
</html>

<?php $conn->close(); ?>
