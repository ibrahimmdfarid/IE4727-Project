<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
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
    </style>
        <script>
        function validateForm() {
            const bodyField = document.getElementById("body");
            if (bodyField.value.length > 1000) {
                alert("Body cannot exceed 1000 characters.");
                return false;
            }
            return true;
        }
    </script>
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
    <!-- FAQ Link -->
    <a href="faqpage.php" class="faq-link">FAQ</a>

    <!-- Contact Form Section -->
    <div class="section-title">Contact Us</div>
    
    <form onsubmit="return validateForm()">
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
