<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "dinesmart");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $category = $_POST["category"];
  $description = $_POST["description"];
  $price = $_POST["price"];

  // Handle image upload
  $image_name = $_FILES["image"]["name"];
  $image_tmp = $_FILES["image"]["tmp_name"];
  $image_path = "images/" . $image_name;

  move_uploaded_file($image_tmp, $image_path);

  // Insert into DB
$stmt = $conn->prepare("INSERT INTO menu_items (name, category, description, price, image) VALUES (?, ?, ?, ?, ?)");


if ($stmt === false) {
  die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssds", $name, $category, $description, $price, $image_name);
$stmt->execute();


  echo "<p style='color: green; text-align:center;'>Menu item added successfully!</p>";
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
    }
    h2 {
      text-align: center;
      color: #c0392b;
    }
    form {
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
    }
    button:hover {
      background: #219150;
    }
  </style>
</head>
<body>

<h2>Add Menu Item</h2>
<form action="" method="POST" enctype="multipart/form-data">
  <label>Dish Name:</label>
  <input type="text" name="name" required>

  <label>Category:</label>
  <select name="category" required>
    <option value="Veg">Veg</option>
    <option value="Chicken">Chicken</option>
    <option value="Beverage">Beverage</option>
    <option value="Dessert">Dessert</option>
  </select>

  <label>Description:</label>
  <textarea name="description" required></textarea>

  <label>Price (Rs):</label>
  <input type="number" step="0.01" name="price" required>

  <label>Upload Image:</label>
  <input type="file" name="image" accept="image/*" required>

  <button type="submit">Add Item</button>
</form>

</body>
</html>
