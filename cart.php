<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
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

// Update quantity if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];

    // Check if this request is for updating quantity or removing the product
    if (isset($_POST['quantity'])) {
        $new_quantity = $_POST['quantity'];
        // Ensure quantity is at least 1
        $new_quantity = max(1, (int)$new_quantity);

        $sql = "UPDATE Cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['remove'])) {
        // Remove the product from the cart
        $sql = "DELETE FROM Cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Get cart items for the user
$sql = "SELECT Cart.product_id, Cart.quantity, Products.name, Products.price 
        FROM Cart JOIN Products ON Cart.product_id = Products.product_id 
        WHERE Cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    <h2>Your Cart</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): 
                $subtotal = $row['price'] * $row['quantity'];
                $total_price += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <!-- Form to update quantity -->
                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <button type="submit" name="quantity" value="<?php echo $row['quantity'] - 1; ?>">-</button>
                        <?php echo htmlspecialchars($row['quantity']); ?>
                        <button type="submit" name="quantity" value="<?php echo $row['quantity'] + 1; ?>">+</button>
                    </form>
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <!-- Form to remove product -->
                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <button type="submit" name="remove" value="1">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><strong>Total Price: $<?php echo number_format($total_price, 2); ?></strong></p>
        <form method="POST" action="purchase.php">
            <button type="submit">Purchase</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
