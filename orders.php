<?php
session_start();
require_once 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
  header("Location: login.php?error=login_required");
  exit;
}

$email = $_SESSION['user']['email'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_email = ? ORDER BY created_at DESC");
$stmt->execute([$email]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark px-4">
  <a class="navbar-brand" href="index.php">🛍️ Sample Ecommerce Website</a>
</nav>

<div class="container mt-5">
  <h2>📦 My Orders</h2>

  <?php if ($orders): ?>
    <table class="table table-bordered mt-3">
      <thead class="table-light">
        <tr>
          <th>Order ID</th>
          <th>Name</th>
          <th>Address</th>
          <th>Total</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
          <td>#<?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['name']) ?></td>
          <td><?= htmlspecialchars($order['address']) ?></td>
          <td>£<?= number_format($order['total'], 2) ?></td>
          <td><?= $order['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info mt-4">You have not placed any orders yet.</div>
  <?php endif; ?>
</div>
</body>
</html>
