<?php
    if (isset($_POST['login'])) {
        // Database credentials
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "project";

        // Get form data
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Create database connection
        $conn = new mysqli('localhost', 'root', '', 'project');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
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
                echo "<p style='color: green;'>Login successful! Welcome back.</p>";
                // Here you can start a session and redirect the user to their dashboard
                session_start();
                $_SESSION['user_email'] = $email;
                header("Location: index.html");
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