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
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

$conn->close();
?>