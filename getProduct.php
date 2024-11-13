<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'project');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product_id from the query string
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

// Prepare and execute the SQL query
$sql = "SELECT product_id, name, price, image_url, description, stock_quantity FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch product data
    $product = $result->fetch_assoc();
    echo json_encode($product);  // Return product data as JSON
} else {
    // If no product is found, return an error message
    echo json_encode(["error" => "Product not found"]);
}

$stmt->close();
$conn->close();
?>
