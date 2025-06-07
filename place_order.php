<?php
session_start();

// Get user data
$name = $_POST['name'] ?? 'Customer';
$address = $_POST['address'] ?? '';
$total = $_POST['total'] ?? 0;

// (Optional) You can insert the order into a database here using PDO

// Store in session for thankyou page
$_SESSION['name'] = $name;
$_SESSION['address'] = $address;

// Clear the cart
unset($_SESSION['cart']);

// Redirect to thank you page
header("Location: thankyou.php?method=cod");
exit;
?>
