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
$sql = "SELECT Cart.product_id, Cart.quantity, Products.price 
        FROM Cart JOIN Products ON Cart.product_id = Products.product_id 
        WHERE Cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
$order_items = [];

// Calculate total amount and prepare data for order items
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total_amount += $subtotal;

    // Prepare order item details
    $order_items[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}

$stmt->close();

// Insert a new record into the Orders table
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
?>
