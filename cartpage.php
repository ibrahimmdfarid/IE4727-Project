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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <a href="index.php"><img src="images/store_logo.png" alt="Store Logo"></a>
    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Search for products...">
        <button class="search-button">
            <img src="images/magnifying_glass_icon.png" alt="Search" class="search-icon"> <!-- Use the correct path for the image -->
        </button>
    </div>
    <div class="buttons">
        <a href="loginpage.html"><button>Login</button></a>
        <a href="cartpage.html"><button>Cart</button></a>
    </div>
</header>

<div class="container">
    <h1>Your Shopping Cart</h1>
    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="cart-items">
            <?php while ($row = $result->fetch_assoc()): 
                $subtotal = $row['price'] * $row['quantity'];
                $total_price += $subtotal;
            ?>
            <tr>
                <td>INSERT IMAGE</td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <!-- Form to update quantity -->
                    <form method="POST" action="cart.php" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <button type="submit" name="quantity" value="<?php echo $row['quantity'] - 1; ?>">-</button>
                        <?php echo htmlspecialchars($row['quantity']); ?>
                        <button type="submit" name="quantity" value="<?php echo $row['quantity'] + 1; ?>">+</button>
                    </form>
                </td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
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
            </tbody>
        </table>
        
        <div class="summary">
            <h3>Cart Summary</h3>
            <p id="total-price">Total Price: $<?php echo number_format($total_price, 2); ?></p>
            <p id="shipping-fee">Shipping Fee: $0.00</p>
            <p id="grand-total">Grand Total: $0.00</p>
            <?php $_SESSION['total_price'] = $total_price; ?>
            <form method="POST" action="checkout.php">
                <button class="checkout-btn" id="checkout-btn" type="submit">Proceed to Checkout</button>
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</div>
<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.html">Contact Us!</a></p>
</footer>
</body>
</html>
