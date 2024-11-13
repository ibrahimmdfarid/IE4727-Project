<?php
if (isset($_POST['login'])) {
    $conn = new mysqli('localhost', 'root', '', 'project');

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch name, hashed password, email, address, and card details from the database
    $sql = "SELECT user_id, name, password, email, address, card_details FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if a matching email was found
    if ($stmt->num_rows > 0) {
        // Bind the retrieved values to variables
        $stmt->bind_result($user_id, $name, $hashed_password, $user_email, $address, $card_details);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Start session and store user data
            session_start();
            $_SESSION['user_id'] = $user_id;         // Store the user's id
            $_SESSION['user_name'] = $name;          // Store the user's name
            $_SESSION['user_email'] = $user_email;   // Store the user's email
            $_SESSION['user_address'] = $address;    // Store the user's address
            $_SESSION['user_card_details'] = $card_details; // Store the user's card details

            // Fetch and store the user's cart items in the session without calculating the count
            $sql_cart = "SELECT cart_item_id, product_id, quantity FROM cart WHERE user_id = ?";
            $stmt_cart = $conn->prepare($sql_cart);
            $stmt_cart->bind_param("i", $user_id);
            $stmt_cart->execute();
            $result_cart = $stmt_cart->get_result();

            // Initialize the cart session variable to hold cart items
            $_SESSION['cart'] = [];

            // Store each cart item in the session
            while ($row = $result_cart->fetch_assoc()) {
                $_SESSION['cart'][] = $row;
            }

            // Check if the user is an admin
            if ($user_email == "electromart@localhost") {
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
