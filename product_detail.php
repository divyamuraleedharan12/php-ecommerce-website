<?php
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
  echo "Product not found!";
  exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
  echo "Product not found!";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <a href="index.php" class="btn btn-secondary mb-3">← Back</a>
  <div class="row">
    <div class="col-md-6">
      <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid" alt="Product Image">
    </div>
    <div class="col-md-6">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <p class="lead">£<?= number_format($product['price'], 2) ?></p>
      <p><?= htmlspecialchars($product['description']) ?></p>
      <p class="text-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
        <?= $product['stock'] > 0 ? "In Stock ({$product['stock']})" : 'Out of Stock' ?>
      </p>
      <?php if ($product['stock'] > 0): ?>
        <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-success">Add to Cart</button>
        </form>

      <?php else: ?>
        <button class="btn btn-secondary" disabled>Out of Stock</button>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
