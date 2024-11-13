<?php
// Start the session
session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header("Location: loginpage.html");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated profile data
    $updated_name = $_POST['name'];
    $updated_address = $_POST['address'];
    $updated_card_details = $_POST['card_details'];

    // Update the database with the new profile data
    $sql = "UPDATE users SET name = ?, address = ?, card_details = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $updated_name, $updated_address, $updated_card_details, $_SESSION['user_email']);

    if ($stmt->execute()) {
        // Update session variables with new values
        $_SESSION['user_name'] = $updated_name;
        $_SESSION['user_address'] = $updated_address;
        $_SESSION['user_card_details'] = $updated_card_details;

        echo "<script>alert('Profile updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Information</title>
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
            padding: 10px 15px; /* Vertical and horizontal padding */
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
    
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    
        .section-title {
            font-size: 20px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    
        .form-group {
            margin-bottom: 15px;
        }
    
        .label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
    
        .input-field {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
    
        .notification-section {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    
        .notification {
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            background-color: #f1f1f1;
        }
    
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    
        .edit-button, .save-button, .cancel-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            color: white;
        }
    
        .edit-button {
            background-color: #28a745; /* Edit button color */
        }
    
        .save-button {
            background-color: #28a745; /* Save button color */
        }
    
        .cancel-button {
            background-color: #dc3545; /* Cancel button color */
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
    <!-- Profile Information Section -->
    <div class="section-title">Profile Information</div>
    
    <!-- Form for updating profile -->
    <form method="POST" onsubmit="return validateForm(event)"> <!-- Attach validation to form submission -->
        <div class="form-group">
            <label class="label" for="name">Name:</label>
            <input type="text" name="name" id="name" class="input-field" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" readonly>
        </div>
        <div class="form-group">
            <label class="label" for="email">Email:</label>
            <input type="email" name="email" id="email" class="input-field" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" readonly>
        </div>
        <div class="form-group">
            <label class="label" for="address">Address:</label>
            <textarea name="address" id="address" class="input-field" rows="3" readonly><?= htmlspecialchars($_SESSION['user_address'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label class="label" for="card_details">Card Details:</label>
            <input type="text" name="card_details" id="card_details" class="input-field" value="<?= htmlspecialchars($_SESSION['user_card_details'] ?? '') ?>" readonly>
        </div>

        <!-- Button Group -->
        <div class="button-group">
            <button type="button" id="editButton" class="edit-button" onclick="toggleEditMode()">Edit</button>
            <button type="button" id="cancelButton" class="cancel-button" onclick="cancelEdit()" style="display: none;">Cancel</button>
            <button type="submit" id="saveButton" class="save-button" style="display: none;">Save Changes</button>
        </div>
    </form>

    <!-- Notification Section -->
    <!-- <div class="notification-section">
        <div class="section-title">Notifications</div>
        <div class="notification">
            <p><strong>Order #12345</strong> - Shipped on Oct 1, 2024</p>
        </div>
        <div class="notification">
            <p><strong>Order #12346</strong> - Delivered on Sep 28, 2024</p>
        </div>
    </div> -->
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
        }

        let isEditMode = false;  // Flag to track whether in edit mode or not
        let initName = '';
        let initAddress = '';
        let initCardDetails = '';

        // Set maximum length for Name
        const MAX_NAME_LENGTH = 50;

        // Function to validate Name
        function validateName(name) {
            const namePattern = /^[A-Za-z\s]+$/; // Only letters and spaces allowed
            if (!name.match(namePattern)) {
                return 'Name must contain only letters and spaces.';
            }
            if (name.length > MAX_NAME_LENGTH) {
                return `Name cannot be longer than ${MAX_NAME_LENGTH} characters.`;
            }
            return true;
        }

        // Function to validate Card Details
        function validateCardDetails(cardDetails) {
            const cardPattern = /^\d{16}$/;  // Only digits and exactly 16 digits
            if (!cardDetails.match(cardPattern)) {
                return 'Card details must be exactly 16 digits.';
            }
            return true;
        }

        // Function to validate Address
        function validateAddress(address) {
            if (!address.trim()) {
                return 'Address cannot be empty.';
            }
            return true;
        }

        // Function to toggle edit mode
        function toggleEditMode() {
            initName = document.getElementById('name').value;
            initAddress = document.getElementById('address').value;
            initCardDetails = document.getElementById('card_details').value;
            
            const inputs = document.querySelectorAll('.input-field');
            const editButton = document.getElementById('editButton');
            const cancelButton = document.getElementById('cancelButton');
            const saveButton = document.getElementById('saveButton');
            
            const emailInput = document.getElementById('email');
            emailInput.setAttribute('readonly', true);  // Ensure email remains readonly

            if (isEditMode) {  // If currently in edit mode
                inputs.forEach(input => input.setAttribute('readonly', true));
                editButton.style.display = 'inline-block';
                cancelButton.style.display = 'none';
                saveButton.style.display = 'none';
            } else {  // If currently not in edit mode
                inputs.forEach(input => {
                    if (input !== emailInput) {  // Allow all fields except email to be editable
                        input.removeAttribute('readonly');
                    }
                });
                editButton.style.display = 'none';
                cancelButton.style.display = 'inline-block';
                saveButton.style.display = 'inline-block';
            }

            // Toggle the flag
            isEditMode = !isEditMode;
        }

        // Function to cancel editing and revert to original values
        function cancelEdit() {
            // Revert back to original values if cancel is clicked
            document.getElementById('name').value = initName;
            document.getElementById('address').value = initAddress;
            document.getElementById('card_details').value = initCardDetails;
            toggleEditMode();  // Close edit mode
        }

        // Function to handle form submission
        function validateForm(event) {
            // Get values from the form
            const name = document.getElementById('name').value;
            const address = document.getElementById('address').value;
            const cardDetails = document.getElementById('card_details').value;

            // Validate each field
            let isValid = true;
            let errorMessage = '';

            // Validate Name
            const nameValidation = validateName(name);
            if (nameValidation !== true) {
                isValid = false;
                errorMessage += nameValidation + '\n';
            }

            // Validate Address
            const addressValidation = validateAddress(address);
            if (addressValidation !== true) {
                isValid = false;
                errorMessage += addressValidation + '\n';
            }

            // Validate Card Details
            const cardValidation = validateCardDetails(cardDetails);
            if (cardValidation !== true) {
                isValid = false;
                errorMessage += cardValidation + '\n';
            }

            // If any validation fails, show alert and prevent form submission
            if (!isValid) {
                alert(errorMessage);
                event.preventDefault();  // Prevent form submission
            }
        }

        // Attach form validation to the form submit event
        document.querySelector('form').addEventListener('submit', validateForm);

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
