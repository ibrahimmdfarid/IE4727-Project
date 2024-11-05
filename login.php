<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>User Login</h2>
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit" name="login">Login</button>
    </form>

    <?php
    if (isset($_POST['login'])) {
        // Database credentials
        $servername = "localhost";
        $username = "your_database_username";
        $password = "your_database_password";
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
                header("Location: product_page.html");
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
</body>
</html>