<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'gpas');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Pagination logic
$products_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

$total_products_query = "SELECT COUNT(*) AS total_products FROM products WHERE user_id = $user_id";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

$sql = "SELECT * FROM products WHERE user_id = $user_id LIMIT $products_per_page OFFSET $offset";
$result = $conn->query($sql);

$total_pages = ceil($total_products / $products_per_page);

$total_price_query = "SELECT SUM(product_price) AS total_price FROM products WHERE user_id = $user_id";
$total_price_result = $conn->query($total_price_query);
$total_price = $total_price_result->fetch_assoc()['total_price'] ?? 0;
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
    <header class="bg-blue-600 text-white">
        <nav class="w-full flex flex-col md:flex-row justify-between items-center px-6 py-3">
            <div class="flex flex-col md:flex-row gap-4 md:gap-8 items-center">
                <h1 class="text-2xl font-bold text-red-500">
                    <a href="index.php">GPAS</a>
                </h1>
                <div>
                    <a href="dashboard.php" class="text-xl font-bold">Dashboard</a>
                </div>
            </div>
            <a href="logout.php" class="mt-4 md:mt-0 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full">Logout</a>
        </nav>
    </header>

    <?php if (isset($_GET['message'])): ?>
        <script>alert('<?php echo $_GET['message']; ?>');</script>
    <?php endif; ?>

    <section class="flex flex-col md:flex-row justify-evenly bg-gray-200">
        <div class="py-10 flex flex-col md:flex-row items-center justify-around">
            <div class="bg-white shadow-lg rounded-lg p-6 w-60 text-center mb-4 md:mb-0">
                <h2 class="text-2xl font-semibold mb-2 text-green-600">Total Products</h2>
                <p class="text-3xl font-bold"><?php echo $result->num_rows; ?></p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6 w-60 text-center">
                <h2 class="text-2xl font-semibold mb-2 text-green-600">Total Price</h2>
                <p class="text-3xl font-bold">₦<?php echo number_format($total_price, 2); ?></p>
            </div>
        </div>
    </section>

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
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['interest_rate'] ?? 0); ?>%</td>
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
            <!-- Pagination Links -->
            <div class="flex justify-center mt-6">
                <nav class="inline-flex rounded-md shadow">
                    <?php if ($current_page > 1): ?>
                        <a href="dashboard.php?page=<?php echo $current_page - 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-l-md hover:bg-blue-600">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="dashboard.php?page=<?php echo $i; ?>" class="px-4 py-2 bg-white border-t border-b border-gray-200 text-blue-500 hover:bg-gray-100 <?php echo ($i == $current_page) ? 'font-bold bg-gray-100' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <a href="dashboard.php?page=<?php echo $current_page + 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">Next</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </section>

    <footer class="bg-gray-800 text-gray-400 py-4 mt-8 text-center">
        <p>&copy; 2025 GPAS. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>