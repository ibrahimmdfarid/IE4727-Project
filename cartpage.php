<?php
// Start the session
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
<style>
    /* Dropdown container styling */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown button styling */
    .dropbtn {
        padding: 10px 15px;
        background-color: #369836;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    /* Dropdown content styling */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 5px;
    }

    /* Individual link styling */
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        border-radius: 5px;
    }

    /* Hover effect on links */
    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .show {
        display: block;
    }
</style>

<script>
    // Toggle dropdown visibility
    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close dropdown if clicked outside
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            const dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

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
            <!-- Dropdown Button -->
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn"><?= htmlspecialchars($_SESSION['user_name']) ?></button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="profilepage.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Show login button if not logged in -->
            <a href="loginpage.html"><button>Login</button></a>
        <?php endif; ?>
        <a href="cartpage.php"><button>Cart</button></a>
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
                    <form method="POST" action="cartpage.php" style="display: inline;">
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
                    <form method="POST" action="cartpage.php" style="display: inline;">
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
            <form method="POST" action="checkoutpage.php">
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
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>


</body>
</html>