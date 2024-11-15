<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $email = $_POST['email'];
    $card_details = $_POST['card_details'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Step 1: Check if the email exists
    $sql = "SELECT card_details FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_card_details);
        $stmt->fetch();

        // Step 2: Check if the entered card number matches
        if ($db_card_details === $card_details) {
            // Step 3: Check if new password and confirm password match
            if ($new_password === $confirm_password) {
                // Step 4: Hash the new password and update it in the database
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                
                $update_sql = "UPDATE users SET password = ? WHERE email = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $hashed_password, $email);
                
                if ($update_stmt->execute()) {
                    echo "<script>
                            alert('Password updated successfully.');
                          </script>";
                } else {
                    echo "<script>
                            alert('Error updating password.');
                          </script>";
                }
                
                $update_stmt->close();
                } else {
                    echo "<script>
                            alert('Passwords do not match.');
                          </script>";
                }
                } else {
                    echo "<script>
                            alert('Card number is incorrect.');
                          </script>";
                }
                } else {
                    echo "<script>
                            alert('Email does not exist.');
                          </script>";
                }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        .forgot-password {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .sign-in-button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        /* Styling for the sign-up button */
        .sign-up-container {
            text-align: center;
            margin-top: 20px;
        }
        .sign-up-button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none; /* Make it look like a button */
        }
        .sign-up-button:hover {
            background-color: #218838; /* Darker green on hover */
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
    <form class="search-container" method="GET" action="index.php">
        <input type="text" class="search-bar" name="search" placeholder="Search for products...">
        <button type="submit" class="search-button">
            <img src="images/magnifying_glass_icon.png" alt="Search" class="search-icon">
        </button>
    </form>
    
    <div class="buttons">
        <a href="loginpage.html"><button>Login</button></a>
        <a href="signup_page.php"><button>Sign Up</button></a>
    </div>
</header>
    
<form method="POST">
    <div class="container">
        <div class="centered-text">Change Password</div>

        <div class="label">E-Mail</div>
        <input type="email" name="email" class="input-field" placeholder="Enter your email" required>
        <div class="label">Card Details</div>
        <input type="text" name="card_details" class="input-field" placeholder="Enter card number for verification" required>
        <div class="label">New Password</div>
        <input type="password" class="input-field" id="password" name="password" placeholder="Enter your password" required>
        <div class="label">Confirm Password</div>
        <input type="password" class="input-field" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
        <button type="submit" name="login" class="sign-in-button">Change Password</button>
    </div>
</form>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>
</body>
</html>