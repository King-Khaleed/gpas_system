<?php
// Start a session
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'gpas');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete the product
$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$sql = "DELETE FROM products WHERE id = $product_id AND author_id = $user_id";

if ($conn->query($sql)) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}
?>
