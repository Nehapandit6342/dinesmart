<?php
include 'config.php';

$sql = "SELECT * FROM bills ORDER BY bill_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - View Bills</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fefefe;
      padding: 20px;
    }
    h2 {
      color: #c0392b;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #d35400;
      color: white;
    }
  </style>
</head>
<body>

<h2>All Bills</h2>

<table>
  <tr>
    <th>ID</th>
    <th>Token</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Items</th>
    <th>Total (Rs)</th>
    <th>Date</th>
    <th>PDF</th>
  </tr>
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['token'] ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= $row['phone'] ?></td>
        <td><?= htmlspecialchars($row['items']) ?></td>
        <td><?= $row['total_amount'] ?></td>
        <td><?= $row['bill_date'] ?></td>
        <td><a href="bills/<?= $row['token'] ?>.pdf" target="_blank">View</a></td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr><td colspan="8">No bills found.</td></tr>
  <?php endif; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>