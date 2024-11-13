<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    echo "<script>
            alert('Please log in first');
            window.location.href = 'loginpage.html';
          </script>";
    exit();
}

// Only process the form submission if there's a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'project');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user_id based on the session email
    $user_email = $_SESSION['user_email'];
    $sql = "SELECT user_id FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // Capture and format form inputs
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $enquiry = "Subject: " . $subject . "\nBody: " . $body;

    // Insert enquiry into the contact_enquiries table
    $sql = "INSERT INTO contact_enquiries (user_id, enquiry) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $enquiry);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Display a success message and redirect
    echo "<script>alert('Your enquiry has been submitted successfully!');</script>";
    echo "<script>window.location.href = 'contactpage.php';</script>";

    $to = "electromart@localhost";
    $headers = "From: " . $user_email . "\r\n";
    
    // Send the email
    if (mail($to, $subject, $body, $headers)) {
        echo "Your message has been sent successfully!";
    } else {
        echo "There was an error sending your message. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
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
            background-color: #369836;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-size: 16px;
        }
    
        header .buttons button:hover {
            background-color: #2a7323;
            transform: translateY(-2px);
        }
    
        header .buttons button:active {
            transform: translateY(1px);
        }
    
        header .buttons button:disabled {
            background-color: #a5d8a3;
            cursor: not-allowed;
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
            width: 1200px; /* Set to 1200px as per your requirement */
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            position: relative;
        }
    
        .faq-link {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 15px;
            border: 1px solid #28a745;
            border-radius: 5px;
            background-color: #f1f1f1;
            color: #28a745;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
    
        .faq-link:hover {
            background-color: #28a745;
            color: #FFFFFF;
        }
    
        .section-title {
            font-size: 24px;
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
    
        .input-field,
        .textarea-field {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
    
        .textarea-field {
            resize: vertical;
        }
    
        .submit-button {
            padding: 10px 20px;
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
            color: #FFFFFF;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
    
        footer a:hover {
            text-decoration: underline;
            color: #A8D08D;
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
    
            function validateForm() {
                const bodyField = document.getElementById("body");
                if (bodyField.value.length > 1000) {
                    alert("Body cannot exceed 1000 characters.");
                    return false;
                }
                return true;
            }

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
    <!-- FAQ Link -->
    <a href="faqpage.php" class="faq-link">FAQ</a>

    <!-- Contact Form Section -->
    <div class="section-title">Contact Us</div>
    
<form onsubmit="return validateForm()" method="POST" action="contactpage.php">
    <div class="form-group">
        <label class="label" for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" class="input-field" placeholder="Enter subject" required>
    </div>
    <div class="form-group">
        <label class="label" for="body">Body:</label>
        <textarea name="body" id="body" class="textarea-field" rows="5" placeholder="Describe your issue (max 1000 characters)" maxlength="1000" required></textarea>
    </div>
    <button type="submit" class="submit-button">Submit</button>
</form>
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>

</body>
</html>
