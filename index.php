<?php
// Start the session
session_start();

$conn = new mysqli('localhost', 'root', '', 'project');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from the database
$sql_categories = "SELECT category_id, category_name FROM categories";
$result_categories = $conn->query($sql_categories);

// Store categories in an array
$categories = [];
if ($result_categories->num_rows > 0) {
    while($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
} else {
    echo "No categories found";
}

// Fetch products from the database
$sql = "SELECT product_id, name, description, price, stock_quantity, category_id, image_url FROM products";
$result = $conn->query($sql);

// Store products in an array
$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "No products found";
}

$conn->close();
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
    
<div class="categories">
    <?php foreach ($categories as $category): ?>
        <span class="category" data-category-id="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_name']) ?></span>
    <?php endforeach; ?>
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

<script>
    // Pass the PHP products array to JavaScript
    const products = <?php echo json_encode($products); ?>;

    // Function to dynamically create product elements
    function displayProducts(products, searchQuery) {
        const mainContent = document.querySelector('.main-content');
        mainContent.innerHTML = ''; // Clear current content

        // Display the search result message
        const resultMessage = document.createElement('p');
        if (searchQuery) {
            const numProducts = products.length;
            resultMessage.innerHTML = `${numProducts} product(s) found for "${searchQuery}".<br>`;
            mainContent.appendChild(resultMessage);
        }

        // If no products match the search query, show the message
        if (products.length === 0) {
            const noResultsMessage = document.createElement('p');
            noResultsMessage.innerHTML = "Sorry, no products were found with those keywords.<br>";
            mainContent.appendChild(noResultsMessage);
        } else {
            // Otherwise, display the filtered products
            products.forEach(product => {

                // Create product div
                const productDiv = document.createElement('div');
                productDiv.className = 'product';

                // Create anchor element for product link
                const productLink = document.createElement('a');
                productLink.href = `productpage.php?product_id=${product.product_id}`;

                // Create image element
                const img = document.createElement('img');
                img.src = product.image_url;
                img.alt = product.name;

                // Create name element
                const productName = document.createElement('h3');
                productName.textContent = product.name;

                // Create price element
                const productPrice = document.createElement('p');
                productPrice.textContent = `Price: $${product.price}`;

                // Append elements to the product link
                productLink.appendChild(img);
                productLink.appendChild(productName);
                productLink.appendChild(productPrice);

                // Append productLink to productDiv
                productDiv.appendChild(productLink);

                // Append productDiv to mainContent
                mainContent.appendChild(productDiv);
            });
        }
    }

    // Function to filter products based on search query and category
    function filterProducts(searchQuery, categoryId) {
        let filteredProducts = products;

        // If a category is selected, filter by category_id
        if (categoryId) {
            console.log('Category_id:', categoryId); // Check category_id
            filteredProducts = filteredProducts.filter(product => parseInt(product.category_id) === parseInt(categoryId));
        }

        // If a search query is provided, filter by product name (or description)
        if (searchQuery) {
            console.log('Search:', searchQuery); // Check searchQuery
            filteredProducts = filteredProducts.filter(product => 
                product.name.toLowerCase().includes(searchQuery.toLowerCase()));
        }

        return filteredProducts;
    }


    // Function to initialize rotating banner functionality
    function initBannerAnimation() {
        const bannerTrack = document.querySelector('.banner-track');
        const banners = document.querySelectorAll('.banner-track img');
        const dots = document.querySelectorAll('.dot');
        let currentIndex = 0;

        // Function to update the banner position based on currentIndex
        function rotateBanner() {
            const currentBannerWidth = banners[currentIndex].width;
            const offset = -currentIndex * currentBannerWidth;
            bannerTrack.style.transform = `translateX(${offset}px)`;

            // Update active dot
            document.querySelector('.dot.active').classList.remove('active');
            dots[currentIndex].classList.add('active');
        }

        // Set interval to rotate banners every 5 seconds
        setInterval(() => {
            currentIndex = (currentIndex + 1) % banners.length;
            rotateBanner();
        }, 5000);

        // Event listener for dots to change banner on click
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                currentIndex = parseInt(dot.getAttribute('data-index'));
                rotateBanner();
            });
        });
    }

    // Event listener for category clicks
    document.querySelectorAll('.category').forEach(category => {
        category.addEventListener('click', function() {
            const categoryId = category.getAttribute('data-category-id');
            
            // Get the search query from the URL (if any)
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search') || '';

            // Filter products based on the category ID (and search query if provided)
            const filteredProducts = filterProducts(searchQuery, categoryId);

            // Display the filtered products
            displayProducts(filteredProducts, searchQuery);
        });
    });

    // Call functions to initialize content and banner animation on page load
    document.addEventListener('DOMContentLoaded', () => {
        // Get the search query and category from the URL (if any)
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search');
        const categoryId = urlParams.get('category_id');

        // Filter products based on the search query and category ID
        const filteredProducts = filterProducts(searchQuery, categoryId);

        // Display filtered products
        displayProducts(filteredProducts, searchQuery);

        // Initialize banner animation
        initBannerAnimation();
    });

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

    document.addEventListener("DOMContentLoaded", function() {
    // Function to update the cart count bubble
    function updateCartCount() {
        fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                const count = data.count || 0;
                const cartBubble = document.querySelector('.cart-count-bubble');
                cartBubble.textContent = count;
                cartBubble.style.display = count > 0 ? 'block' : 'none';
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }

    // Update the count when the page loads
    updateCartCount();
    
    // Optional: Set interval to periodically refresh the count
    // setInterval(updateCartCount, 30000); // Updates every 30 seconds
    });


</script>

</body>
</html>
