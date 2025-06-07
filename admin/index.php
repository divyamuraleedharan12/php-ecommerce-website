<?php require_once '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Product List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
</head>
<body>
<div class="container mt-5">
  <h2>Admin - Manage Products</h2>
  <a href="add.php" class="btn btn-success mb-3">➕ Add New Product</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
      while ($row = $stmt->fetch()):
      ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>£<?= $row['price'] ?></td>
          <td><img src="../assets/images/<?= $row['image'] ?>" width="50"></td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
