<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'project');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert iPad product
$sql = "INSERT INTO Products (product_id, name, price, stock_quantity) VALUES (1, 'iPad', 999.00, 1000)";

if ($conn->query($sql) === TRUE) {
    echo "Product 'iPad' added successfully!";
} else {
    echo "Error: " . $conn->error;
}

// Close connection
$conn->close();
?>
