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
    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            margin: 0;
            padding: 0;
        }
    
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 20px;
            background-color: #369836;
            border-bottom: 1px solid #ddd;
            min-height: 100px;
        }
    
        header img {
            height: 50px;
        }
    
        header .buttons {
            display: flex;
            gap: 15px;
        }
    
        header .buttons button {
            padding: 10px 15px;
            background-color: #369836; /* Primary color for buttons */
            color: white; /* Text color */
            border: none; /* Remove default border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transitions */
            font-size: 16px; /* Font size */
        }
    
        header .buttons button:hover {
            background-color: #2a7323; /* Darker shade for hover effect */
            transform: translateY(-2px); /* Slight lift effect on hover */
        }
    
        header .buttons button:active {
            transform: translateY(1px); /* Button presses down on click */
        }
    
        header .buttons button:disabled {
            background-color: #a5d8a3; /* Light grayish-green for disabled state */
            cursor: not-allowed; /* Not-allowed cursor for disabled button */
        }
    
        header .buttons a {
            text-decoration: none; /* Remove underline from links */
        }
    
        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        h1 {
            text-align: left;
        }
    
        .product-image {
            width: 420px; /* Fixed width for the image */
            height: auto;
            object-fit: cover;
        }
    
        .product-details {
            margin-left: 20px; /* Space between image and details */
            flex-grow: 1; /* Allow details to take remaining space */
        }
    
        .product-details h1 {
            font-size: 24px;
            margin: 0;
        }
    
        .product-details p {
            font-size: 18px;
        }
    
        .add-to-cart {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }
        
        .add-to-cart-btn {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 20px;
        }
    
        .quantity-controls {
            display: flex;
            align-items: center;
        }
    
        .quantity-controls button {
            background-color: #2a7323;
            border: 1px solid #ddd;
            padding: 5px 10px;
            cursor: pointer;
        }
    
        .quantity-controls input {
            width: 50px; 
            text-align: center; 
            margin: 0 10px;
            border: 1px solid #3a3333; 
        }
    
        footer {
            text-align: center;
            padding: 20px;
            background-color: #232F3E;
            color: #FFFFFF; 
            border-top: 1px solid #ddd;
        }
    
        footer a {
            color: #FFFFFF; /* Text color */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Make the text bold */
            padding: 5px 10px; /* Add some padding */
            border-radius: 5px; /* Rounded corners */
        }
    
        footer a:hover {
            text-decoration: underline; /* Underline the text on hover */
            color: #A8D08D; /* Change text color on hover */
        }
    </style>
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
    <img id="product-image" class="product-image" src="" alt="Product Image">
    <div class="product-details">
        <h1 id="product-name">Product Name</h1>
        <p id="product-description">Product description goes here.</p>
        <p id="product-stock">In Stock: 0</p>
        <p id="product-price">Price: $0</p>
        
        <div class="add-to-cart">
            <div class="quantity-controls">
                <button id="decrement">-</button>
                <input type="text" id="quantity" value="1" style="margin: 0 5px; width: 50px;" maxlength="3">
                <button id="increment">+</button>
            </div>
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="1"> <!-- Change this -->
                <input type="hidden" name="product_name" value="iPad">
                <input type="hidden" name="product_price" value="999">
                <input type="hidden" id="selected_quantity" name="quantity" value="1">
                <button type="submit" class="add-to-cart-btn" onclick="document.getElementById('selected_quantity').value = document.getElementById('quantity').value;">Add to Cart</button>
            </form>
            <!-- <button id="add-to-cart-btn" style="margin-left: 20px;">Add to Cart</button> -->
        </div>
    </div>
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>

<script src="productpage.js"></script>

</body>
</html>