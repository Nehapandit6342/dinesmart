<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dinesmart";

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch all menu items
$sql = "SELECT * FROM menu_items ORDER BY category, name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Our Menu - DineSmart</title>
  <link rel="stylesheet" href="style.css">
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
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
      height: 170px;
      object-fit: cover;
    }

    .menu-card-body {
      padding: 15px;
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
        if (!empty($row['image'])) {
          echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
        } else {
          echo "<img src='images/default.jpg' alt='No image'>";
        }

        echo "<div class='menu-card-body'>";
        echo "<div class='menu-item-name'>" . htmlspecialchars($row['name']) . "</div>";
        echo "<div class='menu-item-desc'>" . htmlspecialchars($row['description']) . "</div>";
        echo "<div class='menu-item-price'>Rs. " . number_format($row['price'], 2) . "</div>";
        echo "</div></div>";
      }
      echo "</div></div>"; // Close last grid and category
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
