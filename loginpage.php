<?php
if (isset($_POST['login'])) {
    // Database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";

    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch name, hashed password, email, address, and card details from the database
    $sql = "SELECT name, password, email, address, card_details FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if a matching email was found
    if ($stmt->num_rows > 0) {
        // Bind the retrieved values to variables
        $stmt->bind_result($name, $hashed_password, $user_email, $address, $card_details);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Start session and store user data
            session_start();
            $_SESSION['user_name'] = $name;          // Store the user's name
            $_SESSION['user_email'] = $user_email;   // Store the user's email
            $_SESSION['user_address'] = $address;    // Store the user's address
            $_SESSION['user_card_details'] = $card_details; // Store the user's card details

            // Redirect to the homepage
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color: red;'>No account found with that email.</p>";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>