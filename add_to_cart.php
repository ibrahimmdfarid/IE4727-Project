<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    echo "<script>
            alert('Please log in first');
            window.location.href = 'loginpage.html';
          </script>";
    exit();
}

// Connect to the database
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

$product_id = $_POST['product_id'];
$quantity_to_add = $_POST['quantity'];

// Check if the product already exists in the cart
$sql = "SELECT quantity FROM Cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->bind_result($existing_quantity);
$product_exists = $stmt->fetch();
$stmt->close();

if ($product_exists) {
    // Update with the new quantity instead of adding
    $sql = "UPDATE Cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity_to_add, $user_id, $product_id);
} else {
    // Insert new entry if product is not in the cart
    $sql = "INSERT INTO Cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity_to_add);
}

$stmt->execute();
$stmt->close();
$conn->close();

echo "<script>alert('Product added to cart successfully!');</script>";
echo "<script>window.location.href = 'index.php';</script>";
?>
