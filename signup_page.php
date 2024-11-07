<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
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

        header .buttons a {
            text-decoration: none; /* Remove underline from links */
        }

        header .buttons button:disabled {
            background-color: #a5d8a3; /* Light grayish-green for disabled state */
            cursor: not-allowed; /* Not-allowed cursor for disabled button */
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

        header .buttons a {
            text-decoration: none; /* Remove underline from links */
        }

        header .buttons button:disabled {
            background-color: #a5d8a3; /* Light grayish-green for disabled state */
            cursor: not-allowed; /* Not-allowed cursor for disabled button */
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

        header .buttons a {
            text-decoration: none; /* Remove underline from links */
        }

        header .buttons button:disabled {
            background-color: #a5d8a3; /* Light grayish-green for disabled state */
            cursor: not-allowed; /* Not-allowed cursor for disabled button */
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .centered-text {
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }
        .label {
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .sign-up-in-button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #232F3E;
            color: #FFFFFF; 
            border-top: 1px solid #ddd;
        }

        footer a {
            color: #FFFFFF; /* Soft green color for the text */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Make the text bold */
            padding: 5px 10px; /* Add some padding */
            border-radius: 5px; /* Rounded corners */
        }

        footer a:hover {
            text-decoration: underline; /* Underline the text on hover */
            color: #A8D08D; /* Change text color to white on hover */
        }

    </style>
</head>
<body>

<header>
    <a href="index.php"><img src="images/store_logo.png" alt="Store Logo"></a>
    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Search for products...">
        <button class="search-button">
            <img src="images/magnifying_glass_icon.png" alt="Search" class="search-icon"> <!-- Use the correct path for the image -->
        </button>
    </div>
    <div class="buttons">
        <a href="loginpage.html"><button>Login</button></a>
        <a href="cartpage.php"><button>Cart</button></a>
    </div>
</header>
    
<div class="container">
    <div class="centered-text">Sign Up</div>
    <form method="POST" action="">
        <div class="label">E-Mail</div>
        <input type="email" class="input-field" id="email" name="email" placeholder="Enter your email" required>
        
        <div class="label">Password</div>
        <input type="password" class="input-field" id="password" name="password" placeholder="Enter your password" required>
        
        <div class="label">Confirm Password</div>
        <input type="password" class="input-field" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
        
        <button type="submit" name="signup" class="sign-up-in-button">Sign Up</button>
    </form>

    <div style="text-align: center; margin-top: 15px;">
        <a href="loginpage.html">
            <button class="sign-up-in-button">Already have an account? Sign in here!</button>
        </a>
    </div>
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.html">Contact Us!</a></p>
</footer>

<?php
if (isset($_POST['signup'])) {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<p style='color: red;'>Passwords do not match.</p>";
        exit();
    }

    // Hash the password for secure storage
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Create database connection
    $conn = new mysqli('localhost', 'root', '', 'project');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email already exists
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "<script>alert('This email is already registered. Please use a different email');</script>";
    } else {
        // Insert data into Users table
        $sql = "INSERT INTO Users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Sign up successful!');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }
    // Close connections
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
