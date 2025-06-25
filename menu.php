<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "dinesmart";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
  $item_id = $_POST['item_id'];
  $quantity = intval($_POST['quantity']);
  $query = "SELECT * FROM menu_items WHERE id = $item_id";
  $result = $conn->query($query);
  if ($row = $result->fetch_assoc()) {
    $item = [
      'id' => $row['id'],
      'name' => $row['name'],
      'price' => $row['price'],
      'quantity' => $quantity
    ];

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    $found = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
      if ($cartItem['id'] == $item['id']) {
        $cartItem['quantity'] += $quantity;
        $found = true;
        break;
      }
    }
    if (!$found) {
      $_SESSION['cart'][] = $item;
    }
  }
}

$sql = "SELECT * FROM menu_items ORDER BY category, name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Our Menu - DineSmart</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #c0392b;
      color: white;
      padding: 20px 0;
      text-align: center;
    }

    nav ul {
      list-style: none;
      padding: 0;
      display: flex;
      justify-content: center;
      gap: 25px;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    .menu-container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .menu-category {
      margin-bottom: 40px;
    }

    .menu-category h2 {
      font-size: 28px;
      border-left: 6px solid #c0392b;
      padding-left: 12px;
      color: #c0392b;
      margin-bottom: 20px;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 25px;
    }

    .menu-card {
      background: #fafafa;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }

    .menu-card:hover {
      transform: scale(1.02);
    }

    .menu-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .menu-card-body {
      padding: 15px;
      background-color: #fff;
    }

    .menu-item-name {
      font-size: 20px;
      font-weight: bold;
      color: #2c3e50;
    }

    .menu-item-price {
      color: #27ae60;
      font-size: 18px;
      font-weight: bold;
      margin-top: 8px;
    }

    .menu-item-desc {
      font-size: 14px;
      color: #666;
      margin-top: 5px;
    }

    .add-form {
      margin-top: 10px;
    }

    .add-form input[type='number'] {
      width: 60px;
      padding: 5px;
    }

    .add-form button {
      background: #27ae60;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 5px;
      margin-top: 8px;
      cursor: pointer;
    }

    footer {
      text-align: center;
      padding: 20px;
      margin-top: 40px;
      background: #eee;
    }
  </style>
</head>
<body>

<header>
  <h1>Our Delicious Menu</h1>
  <nav>
    <ul>
      <li><a href="index.html">Home</a></li>
      <li><a href="booktable.html">Book Table</a></li>
      <li><a href="admin-login.html">Admin Login</a></li>
    </ul>
  </nav>
</header>

<div style="text-align:center; margin-top:20px;">
  <a href="cart.php" style="background:#2980b9; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">🛒 View Cart</a>
</div>

<main>
  <div class="menu-container">
    <?php
    $currentCategory = "";
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if ($row["category"] !== $currentCategory) {
          if ($currentCategory !== "") echo "</div>"; // Close previous grid
          $currentCategory = $row["category"];
          echo "<div class='menu-category'><h2>" . htmlspecialchars($currentCategory) . "</h2><div class='menu-grid'>";
        }

        echo "<div class='menu-card'>";
        echo "<img src='" . (!empty($row['image']) ? htmlspecialchars($row['image']) : 'images/default.jpg') . "' alt='" . htmlspecialchars($row['name']) . "'>";
        echo "<div class='menu-card-body'>";
        echo "<div class='menu-item-name'>" . htmlspecialchars($row['name']) . "</div>";
        echo "<div class='menu-item-desc'>" . htmlspecialchars($row['description']) . "</div>";
        echo "<div class='menu-item-price'>Rs. " . number_format($row['price'], 2) . "</div>";
        echo "<form method='POST' class='add-form'>";
        echo "<input type='hidden' name='item_id' value='" . $row['id'] . "'>";
        echo "<label>Qty: <input type='number' name='quantity' min='1' value='1' required></label><br>";
        echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
        echo "</form>";
        echo "</div></div>";
      }
      echo "</div>"; // Close last menu-grid
    } else {
      echo "<p>No menu items found.</p>";
    }
    $conn->close();
    ?>
  </div>
</main>

<footer>
  <p>&copy; 2025 DineSmart Restaurant. All rights reserved.</p>
</footer>

</body>
</html>
