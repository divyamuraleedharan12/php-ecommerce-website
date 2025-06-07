<?php
require_once 'includes/db.php';
session_start();

// Fetch distinct categories for filter
$categories = $pdo->query("SELECT DISTINCT category FROM products")->fetchAll();

// Get filters
$filter = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
if ($filter && $search) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND name LIKE ?");
    $stmt->execute([$filter, "%$search%"]);
} elseif ($filter) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->execute([$filter]);
} elseif ($search) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}

$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sample Ecommerce Website</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><span class="me-2">🛍️</span>Sample Ecommerce Website</a>
    <ul class="navbar-nav ms-auto flex-row gap-4">
      <li class="nav-item"><a class="nav-link text-light" href="cart.php">🛒 Cart</a></li>
      <li class="nav-item"><a class="nav-link text-light" href="orders.php">📦 My Orders</a></li>
      <li class="nav-item"><a class="nav-link text-light" href="admin/index.php">👜 Admin</a></li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="text-center mb-4">🛒 Our Products</h2>

  <!-- Filter Form -->
  <form method="GET" class="mb-4 text-center d-flex justify-content-center gap-2 flex-wrap">
    <select name="category" class="form-select w-auto">
      <option value="">All Categories</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= htmlspecialchars($c['category']) ?>" <?= $filter === $c['category'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['category']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search products..." class="form-control w-auto" style="max-width: 200px;">
    <button class="btn btn-outline-primary">Apply</button>
  </form>

  <!-- Product Grid -->
  <div class="row">
    <?php foreach ($products as $p): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="assets/images/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" height="250" style="object-fit: cover;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
            <p class="card-text text-muted"><?= htmlspecialchars($p['description']) ?></p>
            <p class="fw-bold mb-1">£<?= number_format($p['price'], 2) ?></p>
            <p class="text-<?= $p['stock'] > 0 ? 'success' : 'danger' ?>">
              <?= $p['stock'] > 0 ? "In Stock ({$p['stock']})" : 'Out of Stock' ?>
            </p>
            <a href="product_detail.php?id=<?= $p['id'] ?>" class="btn btn-primary mt-auto">View</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
