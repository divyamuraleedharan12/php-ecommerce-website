<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $desc = $_POST['description'];
  $image = $_FILES['image']['name'];

  // Move uploaded image
  move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");

  $stmt = $pdo->prepare("INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)");
  $stmt->execute([$name, $price, $image, $desc]);
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Add Product</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label>Name:</label><input name="name" class="form-control" required></div>
    <div class="mb-3"><label>Price (£):</label><input type="number" step="0.01" name="price" class="form-control" required></div>
    <div class="mb-3"><label>Description:</label><textarea name="description" class="form-control" required></textarea></div>
    <div class="mb-3"><label>Image:</label><input type="file" name="image" class="form-control" required></div>
    <button class="btn btn-success">Add Product</button>
  </form>
</div>
</body>
</html>
