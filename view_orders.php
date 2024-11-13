<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email'])) {
    header("Location: loginpage.html");
    exit();
}

// Fetch user details
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user name to check if the user is an admin
$user_email = $_SESSION['user_email'];
$sql = "SELECT name FROM Users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();

// Check if the logged-in user is an admin
if ($user_name !== 'admin') {
    echo "<script>alert('You are not authorized to access this page'); window.location.href = 'index.php';</script>";
    exit();
}

// Simulated database of orders (replace this with actual database queries in the future)
$orders = [
    [
        'order_id' => 101,
        'user_id' => 5,
        'order_date' => '2024-11-07',
        'status' => 'Shipped',
        'total_amount' => 299.99,
        'shipping_address' => '1234 Elm Street, City, Country'
    ],
    [
        'order_id' => 102,
        'user_id' => 8,
        'order_date' => '2024-11-06',
        'status' => 'Processing',
        'total_amount' => 89.99,
        'shipping_address' => '5678 Oak Avenue, City, Country'
    ],
    // Add more simulated orders as needed
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Orders</title>
    <link rel="stylesheet" href="styles.css">

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

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styles for the cart count bubble */
        .cart-container {
            position: relative;
            display: inline-block;
        }

        .cart-count-bubble {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            display: none; /* Hide by default */
        }
    </style>
    <script>
        // Open the modal with the order details
        function openModal(orderId, currentStatus) {
            document.getElementById('modal').style.display = "block";
            document.getElementById('order_id').value = orderId;
            document.getElementById('current_status').innerText = currentStatus;
        }

        // Close the modal
        function closeModal() {
            document.getElementById('modal').style.display = "none";
        }

        // Update the order status
        function updateStatus() {
            const orderId = document.getElementById('order_id').value;
            const newStatus = document.getElementById('statusSelect').value;
            const currentStatus = document.getElementById('current_status').innerText;

            if (newStatus === currentStatus) {
                alert('No changes were made. The status is the same as the current one.');
                return;
            }

            const confirmation = confirm(`Are you sure you want to change the status to "${newStatus}"?`);
            if (confirmation) {
                // Call backend code to update the status in the database
                console.log(`Status of order ${orderId} updated to ${newStatus}`);
                // After updating the status, close the modal
                closeModal();
            }
        }
    </script>
</head>
<body>

<header>
    <a href="index.php"><img src="images/store_logo.png" class="store_logo" alt="Store Logo"></a>
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
            <a href="cartpage.php" class="cart-container">
                <img src="images/cart_icon.png" alt="Cart" class="cart-icon" style="width: 32px; height: 32px;">
                <span class="cart-count-bubble">0</span>
            </a>
        <?php else: ?>
            <!-- Show login button if not logged in -->
            <a href="loginpage.html"><button>Login</button></a>
            <a href="signup_page.php"><button>Sign Up</button></a>
        <?php endif; ?>
        
    </div>
</header>

<div class="container">
    <button type="button" onclick="window.location.href='adminpage.php';">Back to Admin Page</button>
    <h1>View Orders</h1>

    <!-- Display existing orders -->
    <h2>Existing Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Shipping Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['user_id']) ?></td>
                <td><?= htmlspecialchars($order['order_date']) ?></td>
                <td><?= htmlspecialchars($order['status']) ?></td>
                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                <td>
                    <button onclick="openModal(<?= $order['order_id'] ?>, '<?= $order['status'] ?>')">Update Status</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal for status update -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Update Order Status</h2>
        <form onsubmit="event.preventDefault(); updateStatus();">
            <input type="hidden" id="order_id">
            <p>Current Status: <span id="current_status"></span></p>
            <label for="statusSelect">New Status:</label>
            <select id="statusSelect">
                <option value="Pending">Pending</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
            </select>
            <br><br>
            <button type="submit">Update Status</button>
        </form>
    </div>
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
