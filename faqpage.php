<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
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
            width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    
        h1 {
            text-align: left;
        }

        .faq-item {
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
    
        .question {
            font-weight: bold;
            cursor: pointer;
            color: #28a745;
        }
    
        .answer {
            display: none;
            margin-top: 10px;
            font-size: 14px;
        }
    
        .answer.visible {
            display: block;
        }
    
        footer {
            text-align: center;
            padding: 20px;
            background-color: #232F3E;
            color: #FFFFFF;
            border-top: 1px solid #ddd;
            margin-top: auto; /* Pushes footer to bottom of the page */
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
            // Function to toggle answer visibility
            function toggleAnswer(id) {
                const answerElement = document.getElementById(id);
                answerElement.classList.toggle('visible');
            }

            // Function to initialize FAQ questions with numbering
            function initializeFAQs() {
                const questions = document.querySelectorAll('.faq-item .question');
                questions.forEach((question, index) => {
                    question.textContent = `${index + 1}) ${question.textContent}`;
                });
            }

            // Initialize FAQs when the page loads
            window.onload = initializeFAQs;
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
            <!-- Dropdown Button -->
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn"><?= htmlspecialchars($_SESSION['user_name']) ?></button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="profilepage.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <a href="cartpage.php"><button>Cart</button></a>
        <?php else: ?>
            <!-- Show login button if not logged in -->
            <a href="loginpage.html"><button>Login</button></a>
            <a href="signup_page.php"><button>Sign Up</button></a>
        <?php endif; ?>
    </div>
</header>
    
<div class="container">
    <h1>Frequently Asked Questions</h1>

    <!-- FAQ Items - Placeholders for dynamic content -->
    <div class="faq-item">
        <p class="question" onclick="toggleAnswer('answer1')">How do I create an account?</p>
        <div id="answer1" class="answer">
            <p>To create an account, click on the "Sign Up" button at the top of the page, fill out the form with your details, and submit.</p>
        </div>
    </div>

    <div class="faq-item">
        <p class="question" onclick="toggleAnswer('answer2')">This is question 2?</p>
        <div id="answer2" class="answer">
            <p>This is the answer to question 2, which provides details and instructions for users.</p>
        </div>
    </div>
    <!-- End of Example Structure -->
</div>

<footer>
    <p>Store Address: 123 Main Street, City, Country</p>
    <p>Contact Number: +123 456 7890</p>
    <p><a href="contactpage.php">Contact Us!</a></p>
</footer>

</body>
</html>
