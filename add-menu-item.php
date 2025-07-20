<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "dinesmart");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock_status = $_POST["stock_status"];

  $image_name = basename($_FILES["image"]["name"]);
$image_tmp = $_FILES["image"]["tmp_name"];
$image_path = "images/" . $image_name;

if (move_uploaded_file($image_tmp, $image_path)) {
    // store only file name in DB
    $stmt = $conn->prepare("INSERT INTO menu_items (name, category, description, price, image, stock_status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $category, $description, $price, $image_name, $stock_status);

        if ($stmt->execute()) {
            echo "<p style='color: green; text-align:center;'>✅ Menu item added successfully!</p>";
        } else {
            echo "<p style='color: red; text-align:center;'>❌ Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Failed to upload image.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Menu Item</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
      max-width: 600px;
      margin: auto;
      background: #f5f5f5;
    }
    h2 {
      text-align: center;
      color: #c0392b;
    }
    form {
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    input, textarea, select {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      padding: 12px;
      background: #27ae60;
      color: white;
      border: none;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #219150;
    }
  </style>
</head>
<body>

<h2>Add New Menu Item</h2>
<form action="" method="POST" enctype="multipart/form-data">
  <label>Dish Name:</label>
  <input type="text" name="name" required>

  <label>Category:</label>
  <select name="category" required>
    <option value="Starter">Starter</option>
    <option value="Main Course">Main Course</option>
    <option value="Dessert">Dessert</option>
    <option value="Beverages">Beverages</option>
  </select>

  <label>Description:</label>
  <textarea name="description" required></textarea>

  <label>Price (Rs):</label>
  <input type="number" step="0.01" name="price" required>

  <label>Upload Image:</label>
  <input type="file" name="image" accept="image/*" required>

  <label>Stock Status:</label>
  <select name="stock_status" required>
    <option value="available">Available</option>
    <option value="out_of_stock">Out of Stock</option>
  </select>

  <button type="submit">➕ Add Item</button>
</form>

</body>
</html>