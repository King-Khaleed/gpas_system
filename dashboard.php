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

// Fetch all products created by the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM products WHERE  user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    <!-- Header -->
    <header class="bg-blue-600 text-white">
        <nav class="w-full flex justify-between items-center px-6 py-3">
            <span class="text-xl font-bold">Dashboard</span>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full">Logout</a>
        </nav>
    </header>

    <!-- Summary Section -->
    <section class="py-10 flex items-center justify-around bg-gray-200">
        <div class="bg-white shadow-lg rounded-lg p-6 w-60 text-center">
            <h2 class="text-2xl font-semibold mb-2 text-blue-600">Total Products</h2>
            <p class="text-3xl font-bold"><?php echo $result->num_rows; ?></p>
        </div>
    </section>

    <!-- Products Table -->
    <section class="py-8">
        <div class="flex items-center justify-between px-6">
            <h2 class="text-2xl font-bold text-gray-700">Products</h2>
            <a href="add.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Product</a>
        </div>
        <div class="container mx-auto px-4 mt-6">
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-800 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Interest</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td class="px-6 py-4">₦<?php echo htmlspecialchars($row['product_price']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['interest_rate']); ?>%</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md mr-2 hover:bg-blue-600">Edit</a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-4 mt-8 text-center">
        <p>&copy; 2025 GPAS. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
