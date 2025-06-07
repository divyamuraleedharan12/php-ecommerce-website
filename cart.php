<?php
session_start();
require_once 'includes/db.php';

// Handle POST add-to-cart (secure with stock check)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = intval($_POST['product_id']);
    $qty = max(1, intval($_POST['quantity'] ?? 1));

    // Check stock first
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product && $product['stock'] >= $qty) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    } else {
        $_SESSION['error'] = "Sorry, this product is out of stock or not enough stock available.";
    }

    header("Location: cart.php");
    exit;
}

// Handle remove
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// Load cart items
$products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $results = $stmt->fetchAll();

    foreach ($results as $product) {
        $product['qty'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['qty'] * $product['price'];
        $products[] = $product;
        $total += $product['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-4">
  <a class="navbar-brand" href="index.php">🛍️ Sample Ecommerce Website</a>
</nav>

<div class="container mt-5">
  <h2 class="mb-4">🛒 Your Shopping Cart</h2>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <?php if (!empty($products)): ?>
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $item): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" class="product-img" alt="<?= htmlspecialchars($item['name']) ?>">
                <strong><?= htmlspecialchars($item['name']) ?></strong>
              </div>
            </td>
            <td><?= $item['qty'] ?></td>
            <td>£<?= number_format($item['price'], 2) ?></td>
            <td>£<?= number_format($item['subtotal'], 2) ?></td>
            <td>
              <a href="?remove=<?= $item['id'] ?>" onclick="return confirm('Remove this item?')" class="btn btn-sm btn-outline-danger">Remove</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" class="text-end"><strong>Total:</strong></td>
          <td colspan="2"><strong>£<?= number_format($total, 2) ?></strong></td>
        </tr>
      </tbody>
    </table>

    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    <a href="index.php" class="btn btn-secondary">← Continue Shopping</a>

  <?php else: ?>
    <div class="alert alert-info">Your cart is currently empty.</div>
    <a href="index.php" class="btn btn-outline-primary">← Continue Shopping</a>
  <?php endif; ?>
</div>
</body>
</html>
