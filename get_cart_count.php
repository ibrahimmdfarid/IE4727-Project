<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'project');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email'];
$stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if ($user_id) {
    $stmt = $conn->prepare("SELECT SUM(quantity) FROM Cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($cart_count);
    $stmt->fetch();
    $stmt->close();

    echo json_encode(['count' => $cart_count]);
} else {
    echo json_encode(['count' => 0]);
}
?>
