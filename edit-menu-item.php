<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dinesmart";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  $sql = "UPDATE `menu_items` SET name=?, category=?, description=?, price=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssdi", $name, $category, $description, $price, $id);
  if ($stmt->execute()) {
   echo "<script>alert('Item updated successfully'); window.location.href='admin-menu.php';</script>";

  } else {
    echo "Error updating item: " . $conn->error;
  }
  exit();
}

// Load existing data
$result = $conn->query("SELECT * FROM `menu_items` WHERE id=$id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Menu Item</title>
  <style>
    form {
      width: 400px;
      margin: 50px auto;
      padding: 30px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #f7f7f7;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
    }
    button {
      background-color: #2980b9;
      color: white;
      border: none;
      padding: 10px;
      margin-top: 15px;
      width: 100%;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Edit Menu Item</h2>
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>

    <label>Category:</label>
    <input type="text" name="category" value="<?php echo $row['category']; ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?php echo $row['description']; ?></textarea>

    <label>Price:</label>
    <input type="number" name="price" value="<?php echo $row['price']; ?>" required>

    <button type="submit">Update Item</button>
  </form>
</body>
</html>

<?php $conn->close(); ?>
