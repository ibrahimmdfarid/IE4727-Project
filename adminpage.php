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
if ($user_email !== 'admin@project.com') {
    echo "<script>alert('You are not authorized to access this page'); window.location.href = 'index.php';</script>";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
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
    <h1>Welcome, Admin!</h1>
    <p>Select an option below:</p>
    
    <!-- Sales Report Button -->
    <!-- <a href="sales_report.php">
        <button class="btn">Sales Report</button>
    </a> -->
    
    <!-- Manage Products Button -->
    <a href="manage_products.php">
        <button class="btn">Manage Products</button>
    </a>

    <!-- Manage Products Button -->
    <a href="view_orders.php">
        <button class="btn">View Orders</button>
    </a>

    <!-- Add other admin functionalities here -->
    <!-- Example for future additions -->
    <!-- <a href="manage_users.php"><button>Manage Users</button></a> -->
    <!-- <a href="view_orders.php"><button>View Orders</button></a> -->
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>

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
    };

</script>


</body>
</html>