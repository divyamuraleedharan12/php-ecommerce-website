<?php
session_start();
require_once 'includes/db.php';

// Detect PayPal return
if (!isset($_POST['name']) && isset($_SESSION['user'])) {
    // Assume PayPal return - insert dummy order if not already saved
    $email = $_SESSION['user']['email'];
    $name = $_SESSION['user']['name'] ?? 'PayPal Buyer';
    $address = 'PayPal Address';
    $total = 0;

    if (!empty($_SESSION['cart'])) {
        $ids = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll();
        foreach ($products as $p) {
            $total += $_SESSION['cart'][$p['id']] * $p['price'];
        }
    }

    // Check if order already exists (optional)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_email = ? AND total = ? AND created_at > NOW() - INTERVAL 5 MINUTE");
    $stmt->execute([$email, $total]);
    $exists = $stmt->fetchColumn();

    if (!$exists) {
        $insert = $pdo->prepare("INSERT INTO orders (user_email, name, address, total) VALUES (?, ?, ?, ?)");
        $insert->execute([$email, $name, $address, $total]);
    }

    // Clear the cart
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank You</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 text-center">
  <h1>🎉 Thank You for Your Order!</h1>
  <p>Your payment was successful. A confirmation will be sent to your PayPal email.</p>
  <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
</div>
</body>
</html>
