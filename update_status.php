<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'electromart@localhost') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the order ID and new status from the POST request
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$new_status = isset($_POST['new_status']) ? $_POST['new_status'] : '';

if ($order_id > 0 && !empty($new_status)) {
    // Update the order status in the database
    $stmt = $conn->prepare("UPDATE Orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        // Get the user ID for the order
        $stmt = $conn->prepare("SELECT user_id FROM Orders WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        // Retrieve the user's email based on user_id
        $stmt = $conn->prepare("SELECT email FROM Users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($user_email);
        $stmt->fetch();
        $stmt->close();

        // Send email notification
        if (!empty($user_email)) {
            $subject = "Electro Mart Order ID {$order_id} Status Update";
            $message = "Dear Customer,\n\nYour order has been updated to the status: {$new_status}.\n\nThank you for shopping with us!";
            $headers = "From: electromart@localhost\r\n";

            if (mail($user_email, $subject, $message, $headers)) {
                echo json_encode(['success' => true, 'message' => 'Status updated and email sent successfully']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Status updated, but email could not be sent']);
            }
        }
    }
}

$conn->close();
?>
