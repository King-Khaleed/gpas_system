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

// Fetch the product details
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Ensure $product_id is valid
if ($product_id <= 0) {
    die("Invalid product ID.");
}

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT product_name, product_price, interest_rate FROM products WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if product exists
if ($result->num_rows === 0) {
    die("Product not found or unauthorized access.");
}

$product = $result->fetch_assoc() ?? [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $product_price = $_POST['product_price'] ?? '';
    $interest_rate = $_POST['interest_rate'] ?? '';

    // Update the product using a prepared statement
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_price = ?, interest_rate = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssdii", $product_name, $product_price, $interest_rate, $product_id, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#c0caad] text-[#654caf] font-sans px-6">
    <div class="flex items-center justify-center min-h-screen">
        <form action="" method="POST" class="bg-white shadow-lg p-6 rounded-lg w-[350px]">
            <h2 class="text-2xl font-bold text-center mb-4">Edit Product</h2>
            
            <div class="mb-4">
                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Name:</label>
                <input type="text" id="product_name" name="product_name" 
                       value="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#654caf]">
            </div>

            <div class="mb-4">
                <label for="product_price" class="block text-sm font-medium text-gray-700 mb-2">Price:</label>
                <input type="text" id="product_price" name="product_price" 
                       value="<?php echo htmlspecialchars($product['product_price'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#654caf]">
            </div>

            <div class="mb-4">
                <label for="interest_rate" class="block text-sm font-medium text-gray-700 mb-2">Interest:</label>
                <input type="text" id="interest_rate" name="interest_rate" 
                       value="<?php echo htmlspecialchars($product['interest_rate'] ?? ''); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#654caf]">
            </div>

            <button type="submit" 
                    class="w-full bg-[#b26e63] text-white py-2 rounded-lg hover:bg-[#9a5b52] focus:outline-none focus:ring-2 focus:ring-[#654caf]">
                Update
            </button>
        </form>
    </div>
</body>
</html>
