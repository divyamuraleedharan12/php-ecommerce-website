<?php
session_start();
require_once 'includes/db.php';

// Require login
if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=login_required");
    exit;
}

// Require non-empty cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Cart is empty.";
    exit;
}

// Load products and calculate total
$total = 0;
$products = [];
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$results = $stmt->fetchAll();

foreach ($results as $product) {
    $qty = $_SESSION['cart'][$product['id']];
    $subtotal = $qty * $product['price'];
    $total += $subtotal;
    $products[] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'qty' => $qty,
        'subtotal' => $subtotal
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark px-4">
  <a class="navbar-brand" href="index.php">🛍️ Sample Ecommerce Website</a>
</nav>

<div class="container mt-5">
  <h2 class="mb-4">🧾 Checkout</h2>

  <div class="row">
    <!-- Order Summary -->
    <div class="col-md-6">
      <h5>🛒 Order Summary</h5>
      <ul class="list-group mb-4">
        <?php foreach ($products as $item): ?>
          <li class="list-group-item d-flex justify-content-between">
            <span><?= htmlspecialchars($item['name']) ?> x <?= $item['qty'] ?></span>
            <span>£<?= number_format($item['subtotal'], 2) ?></span>
          </li>
        <?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between fw-bold">
          <span>Total</span>
          <span>£<?= number_format($total, 2) ?></span>
        </li>
      </ul>
    </div>

    <!-- Payment Forms -->
    <div class="col-md-6">
      <h5>🚚 Cash on Delivery</h5>
      <form action="place_order.php" method="POST" class="mb-4">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="address" class="form-label">Shipping Address</label>
          <input type="text" name="address" class="form-control" required>
        </div>
        <input type="hidden" name="total" value="<?= $total ?>">
        <button type="submit" class="btn btn-success w-100">Place Order (Cash on Delivery)</button>
      </form>

      <hr>

      <h5>💳 Pay with PayPal (Sandbox)</h5>
      <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="business" value="sb-wuv3l43436178@business.example.com">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="item_name" value="YourCartOrder">
        <input type="hidden" name="amount" value="<?= $total ?>">
        <input type="hidden" name="currency_code" value="GBP">
        <input type="hidden" name="return" value="http://localhost/ecommerce/thankyou.php">
        <input type="hidden" name="cancel_return" value="http://localhost/ecommerce/cart.php">
        <input type="submit" class="btn btn-primary w-100" value="Pay with PayPal Sandbox">
      </form>
    </div>
  </div>
</div>
</body>
</html>
