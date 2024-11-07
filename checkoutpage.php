<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : 0;

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

// Get cart items for the user
$sql = "SELECT Cart.product_id, Cart.quantity, Products.name, Products.price 
        FROM Cart JOIN Products ON Cart.product_id = Products.product_id 
        WHERE Cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <a href="index.php"><img src="images/store_logo.png" alt="Store Logo"></a>
    <form class="search-container" method="GET" action="index.php">
        <input type="text" class="search-bar" name="search" placeholder="Search for products...">
        <button type="submit" class="search-button">
            <img src="images/magnifying_glass_icon.png" alt="Search" class="search-icon">
        </button>
    </form>
    
    <div class="buttons">
        <?php if (isset($_SESSION['user_email'])): ?>
            <!-- Show user-specific content if logged in -->
            <a href="profilepage.php"><button><?= htmlspecialchars($_SESSION['user_name']) ?></button></a>
        <?php else: ?>
            <!-- Show login button if not logged in -->
            <a href="loginpage.html"><button>Login</button></a>
        <?php endif; ?>
        <a href="cartpage.php"><button>Cart</button></a>
    </div>
</header>

<div class="container">
    <h1>Enter Payment Details</h1>
    <p><strong>Total Price: $<?php echo number_format($total_price, 2); ?></strong></p>
    <form method="POST" action="purchase.php">
        <div class="form-group">
            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" id="card_number" placeholder="Enter your card number" required>
        </div>
        <div class="form-group">
            <label for="billing_address">Billing Address:</label>
            <input type="text" name="billing_address" id="billing_address" placeholder="Enter your billing address" required>
        </div>
        <button type="submit" class="checkout-btn">Confirm Purchase</button>
    </form>
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
