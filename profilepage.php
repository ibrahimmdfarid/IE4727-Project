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
    <script src="profilepage.js" defer></script>
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
    <div class="notification-section">
        <div class="section-title">Notifications</div>
        <div class="notification">
            <p><strong>Order #12345</strong> - Shipped on Oct 1, 2024</p>
        </div>
        <div class="notification">
            <p><strong>Order #12346</strong> - Delivered on Sep 28, 2024</p>
        </div>
    </div>
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>

</body>
</html>
