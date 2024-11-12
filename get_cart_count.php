<?php
session_start();

// Ensure that the cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate the item count
$cart_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['quantity']; // Assuming each item in cart has a 'quantity' field
}

// Return the count as JSON
header('Content-Type: application/json');
echo json_encode(['count' => $cart_count]);
?>
