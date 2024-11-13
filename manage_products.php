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

// Handle product addition
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $image_url = $_POST['image_url'];

    // Insert the product into the database
    $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, image_url) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiis", $product_name, $description, $price, $stock_quantity, $category_id, $image_url);
    $stmt->execute();
    $stmt->close();

    echo "<script>
            alert('Product added successfully!');
            window.location.href = 'manage_products.php'
            </script>";
}

// Handle product deletion
if (isset($_GET['delete_product_id'])) {
    $product_id = $_GET['delete_product_id'];

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
            alert('Product deleted successfully!');
            window.location.href = 'manage_products.php'
            </script>";
}

// Handle product update
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $image_url = $_POST['image_url'];

    // Update the product details
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, category_id = ?, image_url = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiisi", $product_name, $description, $price, $stock_quantity, $category_id, $image_url, $product_id);
    $stmt->execute();
    $stmt->close();

    // Reset session variable
    $_SESSION['is_editing'] = false;

    echo "<script>alert('Product updated successfully!');
            window.location.href = 'manage_products.php'
            </script>";
}

// Retrieve all products to display on the page
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Fetch the product to edit if an edit button is clicked
$product_to_edit = null;
if (isset($_GET['edit_product_id'])) {
    $product_id = $_GET['edit_product_id'];
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product_to_edit = $result->fetch_assoc();
    $stmt->close();

    // Set session variable to indicate edit mode
    $_SESSION['is_editing'] = true;
} else {
    $_SESSION['is_editing'] = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
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
        // Check if the session variable indicates that we are editing a product
        window.onload = function() {
            if (<?php echo $_SESSION['is_editing'] ? 'true' : 'false'; ?>) {
                const addProductButton = document.getElementById('addProductButton');
                addProductButton.style.display = 'none';  // Hide the Add Product button
            }
        };

        // Toggle the Add Product form
        function toggleAddProductForm() {
            const form = document.getElementById('addProductForm');
            const addProductButton = document.getElementById('addProductButton');
            
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';  // Show the form
                addProductButton.style.display = 'none';  // Hide the button
            } else {
                form.style.display = 'none';  // Hide the form
                addProductButton.style.display = 'inline-block';  // Show the button
            }
        }

        function cancelAddProduct() {
        const form = document.getElementById('addProductForm');
        const addProductButton = document.getElementById('addProductButton');
        
        form.style.display = 'none';  // Hide the form
        addProductButton.style.display = 'inline-block';  // Show the button
        }

        // Hide the Add Product button when the Edit Product button is clicked
        function hideAddProductButton() {
            const addProductButton = document.getElementById('addProductButton');
            addProductButton.style.display = 'none';
        }

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
    <h1>Manage Products</h1>

    <!-- Button to show the Add Product form -->
    <button id="addProductButton" class="red-button" onclick="toggleAddProductForm()">Add Product</button>

    <!-- Add New Product Form (Initially Hidden) -->
    <div id="addProductForm" style="display:none;">
        <h2>Add New Product</h2>
        <form method="POST" action="">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" required>
            
            <label for="description">Description<br></label>
            <textarea name="description" rows="5" cols="100" required></textarea>

            <label for="price"><br><br>Price</label>
            <input type="number" name="price" step="0.01" required>
            
            <label for="stock_quantity">Stock Quantity</label>
            <input type="number" name="stock_quantity" required>
            
            <label for="category_id">Category</label>
            <select name="category_id" required>
                <?php
                // Fetch all categories
                $categories = $conn->query("SELECT * FROM categories");
                while ($category = $categories->fetch_assoc()) {
                    echo "<option value='" . $category['category_id'] . "'>" . htmlspecialchars($category['category_name']) . "</option>";
                }
                ?>
            </select>

            <label for="image_url"><br><br>Image URL</label>
            <input type="text" name="image_url" required>
            
            <!-- Cancel and Confirm buttons -->
            <button type="button" onclick="cancelAddProduct()" style="background-color: #dc3545;">Cancel</button>
            <button type="submit" name="add_product">Confirm</button>
        </form>
    </div>

    <!-- Form to edit an existing product -->
    <?php if ($product_to_edit): ?>
        <h2>Edit Product</h2>
        <form method="POST" action="">
            <input type="hidden" name="product_id" value="<?= $product_to_edit['product_id'] ?>">

            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" value="<?= htmlspecialchars($product_to_edit['name']) ?>" required>

            <label for="description">Description<br></label>
            <textarea name="description" rows="5" cols="100" required><?= htmlspecialchars($product_to_edit['description']) ?></textarea>

            <label for="price"><br><br>Price</label>
            <input type="number" name="price" value="<?= htmlspecialchars($product_to_edit['price']) ?>" step="0.01" required>

            <label for="stock_quantity">Stock Quantity</label>
            <input type="number" name="stock_quantity" value="<?= htmlspecialchars($product_to_edit['stock_quantity']) ?>" required>

            <label for="category_id">Category</label>
            <select name="category_id" required>
                <?php
                // Fetch all categories
                $categories = $conn->query("SELECT * FROM categories");
                while ($category = $categories->fetch_assoc()) {
                    echo "<option value='" . $category['category_id'] . "'>" . htmlspecialchars($category['category_name']) . "</option>";
                }
                ?>
            </select>

            <label for="image_url"><br><br>Image URL</label>
            <input type="text" name="image_url" value="<?= htmlspecialchars($product_to_edit['image_url']) ?>" required>

            <!-- Cancel and Confirm buttons -->
            <button type="button" style="background-color: #dc3545;" onclick="window.location.href='manage_products.php';">Cancel</button>
            <button type="submit" name="update_product">Confirm</button>
        </form>
    <?php endif; ?>

    <!-- Display existing products -->
    <h2>Existing Products</h2>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th>Category ID</th>
                <th>Image URL</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['price']) ?></td>
                <td><?= htmlspecialchars($row['stock_quantity']) ?></td>
                <td><?= htmlspecialchars($row['category_id']) ?></td>
                <td><img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="50" height="50"></td>
                <td>
                    <!-- Edit Product Button -->
                    <a href="?edit_product_id=<?= $row['product_id'] ?>" onclick="hideAddProductButton()">
                        <button>Edit</button>
                    </a>
                    <!-- Delete Product Button -->
                    <a href="?delete_product_id=<?= $row['product_id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">
                        <button style="background-color: #dc3545;">Delete</button>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
