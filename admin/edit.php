<?php
require_once '../includes/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $desc = $_POST['description'];
  $image = $product['image'];

  if ($_FILES['image']['name']) {
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");
  }

  $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ?, description = ? WHERE id = ?");
  $stmt->execute([$name, $price, $image, $desc, $id]);
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Edit Product</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label>Name:</label><input name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>"></div>
    <div class="mb-3"><label>Price:</label><input name="price" class="form-control" value="<?= $product['price'] ?>"></div>
    <div class="mb-3"><label>Description:</label><textarea name="description" class="form-control"><?= $product['description'] ?></textarea></div>
    <div class="mb-3"><label>Image:</label><input type="file" name="image" class="form-control"></div>
    <p>Current Image: <img src="../assets/images/<?= $product['image'] ?>" width="80"></p>
    <button class="btn btn-primary">Update</button>
  </form>
</div>
</body>
</html>
