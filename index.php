<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Homepage</title>
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
<script src="index.js"></script>

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
    
<div class="categories">
    <span class="category" data-category-id="1">Category A</span>
    <span class="category" data-category-id="2">Category B</span>
    <span class="category" data-category-id="3">Category C</span>
</div>

<div class="banner-container">
    <div class="banner-track">
        <img src="images/banner1.png" alt="Banner 1">
        <img src="images/banner2.jfif" alt="Banner 2">
        <img src="images/banner3.jfif" alt="Banner 3">
    </div>
</div>
<div class="banner-dots">
    <span class="dot active" data-index="0"></span>
    <span class="dot" data-index="1"></span>
    <span class="dot" data-index="2"></span>
</div>

<div class="main-content">
    <!-- Products will be dynamically inserted here -->
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>



</body>
</html>
