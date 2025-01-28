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

// Check if there are any errors connecting to the database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];

    // Insert the new post into the database
    $sql = "INSERT INTO products (product_name, price) VALUES ('$product_name', '$price')";

    if ($conn->query($sql)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Save Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#c0caad] text-[#654caf] font-sans px-6">
    <nav class="bg-blue-600 text-white p-4">
        <a href="dashboard.php" class="font-bold">Dashboard</a>
        <a href="logout.php" class="float-right">Log Out</a>
    </nav>
    <div class="flex items-center justify-center min-h-screen">
        <form action="" method="POST" class="bg-white shadow-lg p-6 rounded-lg w-[350px]">
            <h2 class="text-2xl font-bold text-center mb-4">Add Product</h2>
            <div class="mb-4">
                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Name:</label>
                <input type="text" id="product_name" name="product_name" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#654caf]">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price:</label>
                <input type="number" id="price" name="price" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#654caf]">
            </div>
            <button type="submit" 
                    class="w-full bg-[#b26e63] text-white py-2 rounded-lg hover:bg-[#9a5b52] focus:outline-none focus:ring-2 focus:ring-[#654caf]">
                Save
            </button>
        </form>
    </div>
</body>
</html>


