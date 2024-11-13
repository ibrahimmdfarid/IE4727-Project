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

            // Check if the user is an admin
            if ($name == "admin") {
                // Redirect to admin page if the user is an admin
                header("Location: adminpage.php");
                exit();
            } else {
                // Redirect to the regular homepage for regular users
                header("Location: index.php");
                exit();
            }
        } else {
            echo "<script>
                    alert('Invalid password!');
                    window.location.href = 'loginpage.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('No account found with that email.');
                window.location.href = 'loginpage.html';
              </script>";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
