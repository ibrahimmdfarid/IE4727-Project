<?php
if (isset($_POST['login'])) {
    // Database credentials
    $servername = "localhost";
    $username = "your_database_username";
    $password = "your_database_password";
    $dbname = "project";

    // Get form data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        echo "<p style='color: red;'>Something went wrong. Please try again later.</p>";
        exit();
    }

    // Fetch the hashed password from the database
    $sql = "SELECT password FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if a matching email was found
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Start session and regenerate ID to prevent session fixation
            session_start();
            session_regenerate_id(true);
            $_SESSION['user_email'] = $email;
            
            // Redirect to the product page
            header("Location: product_page.html");
            exit();  // Ensure no further code is executed after the redirect
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
