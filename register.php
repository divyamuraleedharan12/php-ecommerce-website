<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  
  try {
    $stmt->execute([$name, $email, $password]);
    $_SESSION['user'] = ['name' => $name, 'email' => $email];
    header("Location: index.php");
    exit;
  } catch (PDOException $e) {
    $error = "Email already registered!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Register</h2>
  <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
  <form method="POST">
    <div class="mb-3"><label>Name:</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label>Email:</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Password:</label><input type="password" name="password" class="form-control" required></div>
    <button class="btn btn-success">Register</button>
    <a href="login.php" class="btn btn-link">Already have an account?</a>
  </form>
</div>
</body>
</html>
