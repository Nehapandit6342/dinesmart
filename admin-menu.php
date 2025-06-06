<?php
include 'config.php';

// Fetch menu items from database
$sql = "SELECT * FROM `menu_items`";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Error: " . mysqli_error($conn)); // Shows exact error
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Menu - DineSmart Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        a.btn { display: inline-block; padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        a.btn:hover { background-color: #218838; }
        .actions a { margin-right: 10px; }
    </style>
</head>
<body>

    <h1>🍽️ Manage Menu</h1>
    <a href="add-menu-item.php" class="btn">➕ Add New Item</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price (Rs)</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['category']; ?></td>
            <td><?= $row['description']; ?></td>
            <td><?= $row['price']; ?></td>
            <td>
             <?php if (!empty($row['image'])): ?>
              <img src="images/<?= $row['image']; ?>" width="60">
             <?php else: ?>
               No Image
             <?php endif; ?>
            </td>

            <td class="actions">
                <a href="edit-menu-item.php?id=<?= $row['id']; ?>" class="btn" style="background-color:#ffc107;">✏️ Edit</a>
                <a href="delete-menu-item.php?id=<?= $row['id']; ?>" class="btn" style="background-color:#dc3545;" onclick="return confirm('Are you sure you want to delete this item?');">🗑️ Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
