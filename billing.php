<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dinesmart";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$items = $conn->query("SELECT id, name, price FROM menu_items ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Billing - DineSmart</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .billing-container {
      max-width: 800px;
      margin: 50px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    .billing-container h2 {
      text-align: center;
      color: #c0392b;
      margin-bottom: 20px;
    }

    select, input {
      padding: 10px;
      margin: 10px 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 10px;
      text-align: center;
    }

    .btn {
      background: #27ae60;
      color: white;
      padding: 10px 15px;
      border: none;
      margin-top: 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-pdf {
      background: #2980b9;
    }

    .total {
      font-weight: bold;
      font-size: 18px;
      text-align: right;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<header>
  <h1>Billing</h1>
  <nav>
    <ul>
      <li><a href="index.html">Home</a></li>
      <li><a href="menu.php">View Menu</a></li>
      <li><a href="booktable.html">Book Table</a></li>
      <li><a href="admin-login.html">Admin Login</a></li>
    </ul>
  </nav>
</header>

<main>
  <div class="billing-container">
    <h2>Generate Customer Bill</h2>

    <div>
      <select id="item-select">
        <option value="">-- Select Item --</option>
        <?php while ($row = $items->fetch_assoc()) {
          echo "<option value='{$row['id']}' data-price='{$row['price']}'>{$row['name']} (Rs. {$row['price']})</option>";
        } ?>
      </select>
      <input type="number" id="quantity" placeholder="Quantity" min="1">
      <button class="btn" onclick="addItem()">Add Item</button>
    </div>

    <table id="bill-table">
      <thead>
        <tr>
          <th>Item</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Remove</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <div class="total" id="grand-total">Grand Total: Rs. 0.00</div>

    <button class="btn btn-pdf" onclick="downloadPDF()">Download PDF</button>
  </div>
</main>

<footer>
  <p>&copy; 2025 DineSmart Restaurant. All rights reserved.</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
let billItems = [];

function addItem() {
  const select = document.getElementById("item-select");
  const qty = parseInt(document.getElementById("quantity").value);
  const id = select.value;
  const name = select.options[select.selectedIndex].text;
  const price = parseFloat(select.options[select.selectedIndex].getAttribute("data-price"));

  if (!id || !qty || qty <= 0) return alert("Select item and enter quantity.");

  const total = price * qty;
  billItems.push({ name, price, qty, total });
  updateTable();
}

function updateTable() {
  const tbody = document.querySelector("#bill-table tbody");
  tbody.innerHTML = "";
  let grandTotal = 0;

  billItems.forEach((item, index) => {
    grandTotal += item.total;
    tbody.innerHTML += `
      <tr>
        <td>${item.name}</td>
        <td>Rs. ${item.price.toFixed(2)}</td>
        <td>${item.qty}</td>
        <td>Rs. ${item.total.toFixed(2)}</td>
        <td><button onclick="removeItem(${index})">X</button></td>
      </tr>
    `;
  });

  document.getElementById("grand-total").textContent = "Grand Total: Rs. " + grandTotal.toFixed(2);
}

function removeItem(index) {
  billItems.splice(index, 1);
  updateTable();
}

function downloadPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  let y = 20;

  doc.setFontSize(16);
  doc.text("DineSmart Restaurant - Customer Bill", 20, y); y += 10;
  doc.setFontSize(12);

  billItems.forEach((item, i) => {
    doc.text(`${i + 1}. ${item.name} - Rs. ${item.price} x ${item.qty} = Rs. ${item.total.toFixed(2)}`, 20, y);
    y += 8;
  });

  const grand = billItems.reduce((sum, i) => sum + i.total, 0);
  doc.text(`-----------------------------------------`, 20, y); y += 8;
  doc.text(`Grand Total: Rs. ${grand.toFixed(2)}`, 20, y);

  doc.save("DineSmart_Bill.pdf");
}
</script>

</body>
</html>
