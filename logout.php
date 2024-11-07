<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page (or homepage)
header("Location: index.php"); // Adjust the location if you want to redirect to a different page
exit();
?>
