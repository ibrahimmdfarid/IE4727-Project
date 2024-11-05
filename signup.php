<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>User Sign Up</h2>
    <form method="POST" action="signup.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        
        <button type="submit" name="signup">Sign Up</button>
    </form>

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

        // Insert data into Users table
        $sql = "INSERT INTO Users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Sign up successful!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        // Close connections
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
