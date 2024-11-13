<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: loginpage.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'project');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user_id based on the session email
$user_email = $_SESSION['user_email'];
$sql = "SELECT user_id FROM Users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Get all cart items for the user to calculate the total amount and prepare order items
$sql = "SELECT Cart.product_id, Cart.quantity, Products.price, Products.name 
        FROM Cart JOIN Products ON Cart.product_id = Products.product_id 
        WHERE Cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
$order_items = [];

// Initialize email message with order summary
$email_message = "Thank you for your purchase! Here are the details of your order:\n\n";
$email_message .= "Items:\n";

// Calculate total amount and prepare data for order items
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total_amount += $subtotal;

    // Prepare order item details
    $order_items[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'name' => $row['name']
    ];

    // Append product details to email message
    $email_message .= "Product: " . $row['name'] . "\n";
    $email_message .= "Quantity: " . $row['quantity'] . "\n";
    $email_message .= "Price: $" . number_format($row['price'], 2) . "\n";
    $email_message .= "Subtotal: $" . number_format($subtotal, 2) . "\n\n";
}

$email_message .= "Total Amount: $" . number_format($total_amount, 2) . "\n";
$stmt->close();

// Insert a new record into the Orders table
date_default_timezone_set('Asia/Singapore');
$order_date = date("Y-m-d H:i:s");
$status = 'pending';

$sql = "INSERT INTO Orders (user_id, order_date, status, total_amount) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issd", $user_id, $order_date, $status, $total_amount);
$stmt->execute();
$order_id = $stmt->insert_id;  // Get the last inserted order ID for use in Order_Items table
$stmt->close();

// Insert each product in cart into Order_Items table with the generated order_id
$sql = "INSERT INTO Order_Items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

foreach ($order_items as $item) {
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

$stmt->close();

// Deduct purchased quantity from stock_quantity in Products table
$sql = "UPDATE Products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
$stmt = $conn->prepare($sql);

foreach ($order_items as $item) {
    $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
    $stmt->execute();
}

$stmt->close();

// Clear the user's cart after recording the order
$sql = "DELETE FROM Cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$stmt->close();
$conn->close();

// Use JavaScript to display alert and redirect
echo "<script>
        alert('Thank you for your purchase! Your order has been placed with Order ID: $order_id');
        window.location.href = 'index.php';
      </script>";

// EMAIL      
// Set the email subject
$subject = "Electro Mart Order ID: $order_id";

// Set additional headers
$headers = "From: electromart@localhost\r\n";

// Send the email with detailed product information
if (mail($user_email, $subject, $email_message, $headers)) {
    echo 'Email sent successfully!';
} else {
    echo 'Failed to send email.';
}
?>
