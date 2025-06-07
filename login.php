<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = ['name' => $user['name'], 'email' => $user['email']];
    header("Location: index.php");
    exit;
  } else {
    $error = "Invalid email or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Login</h2>
  <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
  <form method="POST">
    <div class="mb-3"><label>Email:</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Password:</label><input type="password" name="password" class="form-control" required></div>
    <button class="btn btn-primary">Login</button>
    <a href="register.php" class="btn btn-link">Don't have an account?</a>
  </form>
</div>
</body>
</html>
